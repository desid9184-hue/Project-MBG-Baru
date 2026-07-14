package com.alhuwyd.mbg_project_app;

import android.os.Bundle;
import android.util.Log;
import android.widget.Toast;
import androidx.appcompat.app.AppCompatActivity;
import androidx.recyclerview.widget.LinearLayoutManager;
import androidx.recyclerview.widget.RecyclerView;
import androidx.swiperefreshlayout.widget.SwipeRefreshLayout;
import com.alhuwyd.mbg_project_app.model.DeliveryListResponse;
import com.alhuwyd.mbg_project_app.network.ApiClient;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class HomeActivity extends AppCompatActivity {
    private RecyclerView recyclerView;
    private SwipeRefreshLayout swipeRefresh;
    private SessionManager sessionManager;
    // TAMBAHKAN BARIS INI UNTUK MEMPERBAIKI ERROR 'Cannot resolve symbol adapter'
    private DeliveryAdapter adapter;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_home);

        recyclerView = findViewById(R.id.recyclerView);
        swipeRefresh = findViewById(R.id.swipeRefreshLayout);
        sessionManager = new SessionManager(this);

        recyclerView.setLayoutManager(new LinearLayoutManager(this));

        swipeRefresh.setOnRefreshListener(this::loadData);
        loadData();
        Log.d("DEBUG_TOKEN", "Token yang dikirim ke server: " + sessionManager.getToken());
    }

    private void loadData() {
        swipeRefresh.setRefreshing(true);
        String tanggalHariIni = "2026-07-08";

        String tokenAuth = "Bearer " + sessionManager.getToken();

        ApiClient.getApiService().getDeliveries(tokenAuth, "")
                .enqueue(new Callback<DeliveryListResponse>() {
                    @Override
                    public void onResponse(Call<DeliveryListResponse> call, Response<DeliveryListResponse> response) {
                        swipeRefresh.setRefreshing(false);

                        if (response.isSuccessful() && response.body() != null) {
                            // Debugging: Cek isi data di Logcat
                            int jumlahData = (response.body().getData() != null) ? response.body().getData().size() : 0;
                            Log.d("DEBUG_DATA", "Jumlah data diterima: " + jumlahData);

                            if (jumlahData > 0) {
                                adapter = new DeliveryAdapter(response.body().getData());
                                recyclerView.setAdapter(adapter);
                            } else {
                                Toast.makeText(HomeActivity.this, "Data kosong dari server", Toast.LENGTH_SHORT).show();
                            }
                        } else {
                            Log.e("DEBUG_DATA", "Response gagal, kode: " + response.code());
                            Toast.makeText(HomeActivity.this, "Gagal memuat data", Toast.LENGTH_SHORT).show();
                        }
                    }

                    @Override
                    public void onFailure(Call<DeliveryListResponse> call, Throwable t) {
                        swipeRefresh.setRefreshing(false);
                        Log.e("DEBUG_DATA", "Error koneksi: " + t.getMessage());
                        Toast.makeText(HomeActivity.this, "Error: " + t.getMessage(), Toast.LENGTH_SHORT).show();
                    }
                });
    }
}