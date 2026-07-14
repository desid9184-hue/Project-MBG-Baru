package com.alhuwyd.mbg_project_app.model;

public class Delivery {
    private int delivery_id;
    private String status_pengiriman;
    private boolean tracking_active;
    private Double current_latitude;
    private Double current_longitude;
    private String delivered_at;
    private String tanggal_pengiriman;
    private int jumlah_porsi_besar;
    private int jumlah_porsi_kecil;
    private Guru guru;
    private SekolahTujuan sekolah_tujuan;

    // Getter
    public int getDeliveryId() { return delivery_id; }
    public String getStatusPengiriman() { return status_pengiriman; }
    public boolean isTrackingActive() { return tracking_active; }
    public Double getCurrentLatitude() { return current_latitude; }
    public Double getCurrentLongitude() { return current_longitude; }
    public String getDeliveredAt() { return delivered_at; }
    public int getJumlahPorsiBesar() { return jumlah_porsi_besar; }
    public int getJumlahPorsiKecil() { return jumlah_porsi_kecil; }
    public Guru getGuru() { return guru; }
    public SekolahTujuan getSekolahTujuan() { return sekolah_tujuan; }

    public static class Guru {
        private String nama;
        private String phone;
        public String getNama() { return nama; }
        public String getPhone() { return phone; }
    }

    public static class SekolahTujuan {
        private String nama;
        private String alamat;
        private Double latitude;
        private Double longitude;
        public String getNama() { return nama; }
        public String getAlamat() { return alamat; }
        public Double getLatitude() { return latitude; }
        public Double getLongitude() { return longitude; }
    }
}