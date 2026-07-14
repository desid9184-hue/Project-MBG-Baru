package com.alhuwyd.mbg_project_app;

import android.Manifest;
import android.content.pm.PackageManager;
import android.location.Location;
import android.os.Bundle;
import android.util.Log;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import androidx.annotation.NonNull;
import androidx.appcompat.app.AppCompatActivity;
import androidx.core.app.ActivityCompat;
import androidx.core.content.ContextCompat;

import com.alhuwyd.mbg_project_app.network.ApiClient;
import com.google.android.gms.location.FusedLocationProviderClient;
import com.google.android.gms.location.LocationCallback;
import com.google.android.gms.location.LocationRequest;
import com.google.android.gms.location.LocationResult;
import com.google.android.gms.location.LocationServices;
import com.google.android.gms.location.Priority;

import org.osmdroid.config.Configuration;
import org.osmdroid.tileprovider.tilesource.TileSourceFactory;
import org.osmdroid.util.GeoPoint;
import org.osmdroid.views.MapView;
import org.osmdroid.views.overlay.Marker;
import org.osmdroid.views.overlay.Polyline;

import java.util.HashMap;
import java.util.List;

import okhttp3.ResponseBody;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

/**
 * Halaman live tracking GPS driver.
 * - Menampilkan peta OpenStreetMap (osmdroid)
 * - Marker sekolah tujuan (statis) dan marker driver (bergerak)
 * - Garis rute lurus dari driver ke sekolah (update tiap lokasi berubah)
 * - Mengirim lokasi driver ke server tiap 10 detik selama tracking aktif
 */
public class TrackingActivity extends AppCompatActivity {

    public static final String EXTRA_DELIVERY_ID = "extra_delivery_id";
    public static final String EXTRA_SCHOOL_NAME = "extra_school_name";
    public static final String EXTRA_SCHOOL_LAT = "extra_school_lat";
    public static final String EXTRA_SCHOOL_LNG = "extra_school_lng";
    public static final String EXTRA_GURU_NAME = "extra_guru_name";
    public static final String EXTRA_STATUS = "extra_status";

    private static final int REQUEST_LOCATION_PERMISSION = 100;
    private static final long UPDATE_INTERVAL_MS = 10_000; // 10 detik

    private MapView mapView;
    private TextView tvNamaSekolah, tvGuru, tvStatus, tvJarak;
    private Button btnMulai, btnSampai;

    private SessionManager sessionManager;

    private FusedLocationProviderClient fusedLocationClient;
    private LocationCallback locationCallback;
    private boolean isTracking = false;

    private int deliveryId;
    private String schoolName;
    private double schoolLat;
    private double schoolLng;
    private String guruName;
    private String currentStatus;

    private Marker driverMarker;
    private Marker schoolMarker;
    private Polyline routeLine;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        // WAJIB dipanggil SEBELUM setContentView untuk osmdroid
        Configuration.getInstance().setUserAgentValue(getPackageName());
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_tracking);

        sessionManager = new SessionManager(this);

        // Ambil data dari Intent (dikirim dari DeliveryAdapter)
        deliveryId = getIntent().getIntExtra(EXTRA_DELIVERY_ID, -1);
        schoolName = getIntent().getStringExtra(EXTRA_SCHOOL_NAME);
        schoolLat  = getIntent().getDoubleExtra(EXTRA_SCHOOL_LAT, 0);
        schoolLng  = getIntent().getDoubleExtra(EXTRA_SCHOOL_LNG, 0);
        guruName   = getIntent().getStringExtra(EXTRA_GURU_NAME);
        currentStatus = getIntent().getStringExtra(EXTRA_STATUS);

        bindViews();
        setupMap();
        setupLocationClient();
        setupButtons();

        updateStatusUI(currentStatus);
    }

    private void bindViews() {
        mapView   = findViewById(R.id.mapView);
        tvNamaSekolah = findViewById(R.id.tvTrackingNamaSekolah);
        tvGuru    = findViewById(R.id.tvTrackingGuru);
        tvStatus  = findViewById(R.id.tvTrackingStatus);
        tvJarak   = findViewById(R.id.tvJarak);
        btnMulai  = findViewById(R.id.btnMulaiPerjalanan);
        btnSampai = findViewById(R.id.btnSampaiSekolah);

        tvNamaSekolah.setText(schoolName != null ? schoolName : "Sekolah");
        tvGuru.setText("Guru: " + (guruName != null ? guruName : "-"));
    }

    private void setupMap() {
        mapView.setTileSource(TileSourceFactory.MAPNIK);
        mapView.setMultiTouchControls(true);
        mapView.getController().setZoom(15.0);

        GeoPoint schoolPoint = new GeoPoint(schoolLat, schoolLng);
        mapView.getController().setCenter(schoolPoint);

        schoolMarker = new Marker(mapView);
        schoolMarker.setPosition(schoolPoint);
        schoolMarker.setAnchor(Marker.ANCHOR_CENTER, Marker.ANCHOR_BOTTOM);
        schoolMarker.setTitle(schoolName != null ? schoolName : "Sekolah Tujuan");
        mapView.getOverlays().add(schoolMarker);

        mapView.invalidate();
    }

    private void setupLocationClient() {
        fusedLocationClient = LocationServices.getFusedLocationProviderClient(this);

        locationCallback = new LocationCallback() {
            @Override
            public void onLocationResult(@NonNull LocationResult locationResult) {
                Location location = locationResult.getLastLocation();
                if (location != null) {
                    onNewLocation(location);
                }
            }
        };
    }

    private void setupButtons() {
        btnMulai.setOnClickListener(v -> mulaiPerjalanan());
        btnSampai.setOnClickListener(v -> sampaiSekolah());
    }

    /**
     * Tombol "Mulai Perjalanan": update status delivery jadi
     * "dalam_perjalanan" di server, lalu mulai tracking GPS.
     */
    private void mulaiPerjalanan() {
        if (deliveryId == -1) {
            Toast.makeText(this, "ID pengiriman tidak valid", Toast.LENGTH_SHORT).show();
            return;
        }

        HashMap<String, String> body = new HashMap<>();
        body.put("status", "dalam_perjalanan");

        ApiClient.getApiService()
                .updateStatus(sessionManager.getToken(), deliveryId, body)
                .enqueue(new Callback<ResponseBody>() {
                    @Override
                    public void onResponse(Call<ResponseBody> call, Response<ResponseBody> response) {
                        if (response.isSuccessful()) {
                            currentStatus = "dalam_perjalanan";
                            updateStatusUI(currentStatus);
                            startLocationUpdates();
                            Toast.makeText(TrackingActivity.this, "Tracking dimulai", Toast.LENGTH_SHORT).show();
                        } else {
                            Toast.makeText(TrackingActivity.this, "Gagal update status: " + response.code(), Toast.LENGTH_SHORT).show();
                        }
                    }

                    @Override
                    public void onFailure(Call<ResponseBody> call, Throwable t) {
                        Toast.makeText(TrackingActivity.this, "Error: " + t.getMessage(), Toast.LENGTH_SHORT).show();
                    }
                });
    }

    /**
     * Tombol "Sampai di Sekolah": hentikan tracking GPS, update status
     * delivery jadi "selesai" di server.
     */
    private void sampaiSekolah() {
        HashMap<String, String> body = new HashMap<>();
        body.put("status", "selesai");

        ApiClient.getApiService()
                .updateStatus(sessionManager.getToken(), deliveryId, body)
                .enqueue(new Callback<ResponseBody>() {
                    @Override
                    public void onResponse(Call<ResponseBody> call, Response<ResponseBody> response) {
                        if (response.isSuccessful()) {
                            currentStatus = "selesai";
                            updateStatusUI(currentStatus);
                            stopLocationUpdates();
                            Toast.makeText(TrackingActivity.this, "Pengiriman selesai", Toast.LENGTH_SHORT).show();
                            finish();
                        } else {
                            Toast.makeText(TrackingActivity.this, "Gagal update status: " + response.code(), Toast.LENGTH_SHORT).show();
                        }
                    }

                    @Override
                    public void onFailure(Call<ResponseBody> call, Throwable t) {
                        Toast.makeText(TrackingActivity.this, "Error: " + t.getMessage(), Toast.LENGTH_SHORT).show();
                    }
                });
    }

    /**
     * Update tampilan tombol & label status sesuai status delivery saat ini.
     */
    private void updateStatusUI(String status) {
        tvStatus.setText("Status: " + (status != null ? status : "-"));

        if ("dalam_perjalanan".equals(status)) {
            btnMulai.setEnabled(false);
            btnSampai.setEnabled(true);
            startLocationUpdates();
        } else if ("selesai".equals(status) || "sampai_sekolah".equals(status)) {
            btnMulai.setEnabled(false);
            btnSampai.setEnabled(false);
        } else {
            btnMulai.setEnabled(true);
            btnSampai.setEnabled(false);
        }
    }

    /**
     * Cek & minta izin lokasi, lalu mulai request update lokasi
     * dari GPS tiap UPDATE_INTERVAL_MS (10 detik).
     */
    private void startLocationUpdates() {
        if (isTracking) return;

        if (ContextCompat.checkSelfPermission(this, Manifest.permission.ACCESS_FINE_LOCATION)
                != PackageManager.PERMISSION_GRANTED) {
            ActivityCompat.requestPermissions(this,
                    new String[]{
                            Manifest.permission.ACCESS_FINE_LOCATION,
                            Manifest.permission.ACCESS_COARSE_LOCATION
                    },
                    REQUEST_LOCATION_PERMISSION);
            return;
        }

        LocationRequest locationRequest = new LocationRequest.Builder(
                Priority.PRIORITY_HIGH_ACCURACY, UPDATE_INTERVAL_MS)
                .setMinUpdateIntervalMillis(UPDATE_INTERVAL_MS)
                .build();

        fusedLocationClient.requestLocationUpdates(locationRequest, locationCallback, getMainLooper());
        isTracking = true;
    }

    private void stopLocationUpdates() {
        if (!isTracking) return;
        fusedLocationClient.removeLocationUpdates(locationCallback);
        isTracking = false;
    }

    /**
     * Dipanggil setiap kali ada update lokasi baru dari GPS.
     * - Update posisi marker driver di peta
     * - Update garis rute (lurus) ke sekolah
     * - Hitung & tampilkan jarak ke sekolah
     * - Kirim lokasi ke server
     */
    private void onNewLocation(Location location) {
        double lat = location.getLatitude();
        double lng = location.getLongitude();
        GeoPoint driverPoint = new GeoPoint(lat, lng);

        // 🔥 TAMBAHKAN FILTER INI (Tolak koordinat 0,0 atau yang tidak masuk akal)
        if (lat == 0.0 && lng == 0.0) {
            Log.w("GPS_CEK", "Koordinat (0,0) ditolak, menunggu lokasi valid...");
            return; // Jangan update peta
        }
        if (location.getAccuracy() > 200) { // Akurasi lebih dari 200 meter dianggap tidak valid
            Log.w("GPS_CEK", "Akurasi terlalu rendah (" + location.getAccuracy() + "m), lokasi ditahan.");
            return;
        }

        // Update / buat marker driver
        if (driverMarker == null) {
            driverMarker = new Marker(mapView);
            driverMarker.setTitle("Posisi Anda");
            driverMarker.setAnchor(Marker.ANCHOR_CENTER, Marker.ANCHOR_BOTTOM);
            mapView.getOverlays().add(driverMarker);
        }
        driverMarker.setPosition(driverPoint);

        // Update garis rute lurus driver -> sekolah
        if (routeLine != null) {
            mapView.getOverlays().remove(routeLine);
        }
        routeLine = new Polyline();
        routeLine.setPoints(List.of(driverPoint, new GeoPoint(schoolLat, schoolLng)));
        mapView.getOverlays().add(routeLine);

        mapView.invalidate();

        // Hitung jarak ke sekolah (dalam km)
        float[] hasil = new float[1];
        Location.distanceBetween(lat, lng, schoolLat, schoolLng, hasil);
        double jarakKm = hasil[0] / 1000.0;
        tvJarak.setText(String.format("Jarak ke sekolah: %.2f km", jarakKm));

        // Kirim lokasi ke server
        kirimLokasiKeServer(lat, lng);
    }

    private void kirimLokasiKeServer(double lat, double lng) {
        HashMap<String, Double> body = new HashMap<>();
        body.put("latitude", lat);
        body.put("longitude", lng);

        ApiClient.getApiService()
                .updateLocation(sessionManager.getToken(), deliveryId, body)
                .enqueue(new Callback<ResponseBody>() {
                    @Override
                    public void onResponse(Call<ResponseBody> call, Response<ResponseBody> response) {
                        if (!response.isSuccessful()) {
                            Log.e("TRACKING", "Gagal kirim lokasi: " + response.code());
                        }
                    }

                    @Override
                    public void onFailure(Call<ResponseBody> call, Throwable t) {
                        Log.e("TRACKING", "Error kirim lokasi: " + t.getMessage());
                    }
                });
    }

    @Override
    public void onRequestPermissionsResult(int requestCode, @NonNull String[] permissions, @NonNull int[] grantResults) {
        super.onRequestPermissionsResult(requestCode, permissions, grantResults);
        if (requestCode == REQUEST_LOCATION_PERMISSION) {
            if (grantResults.length > 0 && grantResults[0] == PackageManager.PERMISSION_GRANTED) {
                startLocationUpdates();
            } else {
                Toast.makeText(this, "Izin lokasi diperlukan untuk tracking", Toast.LENGTH_LONG).show();
            }
        }
    }

    @Override
    protected void onResume() {
        super.onResume();
        mapView.onResume();
    }

    @Override
    protected void onPause() {
        super.onPause();
        mapView.onPause();
    }

    @Override
    protected void onDestroy() {
        super.onDestroy();
        stopLocationUpdates();
    }
}