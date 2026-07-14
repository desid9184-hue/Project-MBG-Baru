package com.alhuwyd.mbg_project_app.network;

import com.alhuwyd.mbg_project_app.model.DeliveryListResponse;
import com.alhuwyd.mbg_project_app.model.LoginResponse;

import java.util.HashMap;
import okhttp3.ResponseBody;
import retrofit2.Call;
import retrofit2.http.Body;
import retrofit2.http.GET;
import retrofit2.http.Header;
import retrofit2.http.POST;
import retrofit2.http.Path;
import retrofit2.http.Query;

public interface ApiService {

    @POST("api/login")
    Call<LoginResponse> login(@Body HashMap<String, String> fields);

    @GET("api/deliveries")
    Call<DeliveryListResponse> getDeliveries(
            @Header("Authorization") String token,
            @Query("tanggal") String tanggal
    );

    @POST("api/deliveries/{id}/status")
    Call<ResponseBody> updateStatus(
            @Header("Authorization") String token,
            @Path("id") int id,
            @Body HashMap<String, String> statusField
    );

    @POST("api/deliveries/{id}/location")
    Call<ResponseBody> updateLocation(
            @Header("Authorization") String token,
            @Path("id") int id,
            @Body HashMap<String, Double> locationFields
    );
}