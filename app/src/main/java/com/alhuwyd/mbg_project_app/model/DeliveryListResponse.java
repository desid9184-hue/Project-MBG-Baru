package com.alhuwyd.mbg_project_app.model;

import java.util.List;

public class DeliveryListResponse {
    private String tanggal;
    private int total;
    private List<Delivery> data;

    public String getTanggal() { return tanggal; }
    public int getTotal() { return total; }
    public List<Delivery> getData() { return data; }
}