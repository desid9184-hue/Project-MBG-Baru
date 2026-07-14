package com.alhuwyd.mbg_project_app;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;
import androidx.appcompat.app.AppCompatActivity;

import com.alhuwyd.mbg_project_app.model.LoginResponse;
import com.alhuwyd.mbg_project_app.network.ApiClient;

import java.util.HashMap;
import retrofit2.Call;
import retrofit2.Callback;
import retrofit2.Response;

public class MainActivity extends AppCompatActivity {

    // Deklarasi variabel komponen
    private EditText etEmail, etPassword;
    private Button btnLogin;
    private SessionManager sessionManager;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        // 1. Hubungkan variabel Java dengan ID di XML
        etEmail = findViewById(R.id.etEmail);
        etPassword = findViewById(R.id.etPassword);
        btnLogin = findViewById(R.id.btnLogin);

        sessionManager = new SessionManager(this);

        // 2. Aksi ketika tombol login diklik
        btnLogin.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                String email = etEmail.getText().toString().trim();
                String password = etPassword.getText().toString().trim();

                // Validasi sederhana agar tidak ngirim data kosong ke Laravel
                if (email.isEmpty() || password.isEmpty()) {
                    Toast.makeText(MainActivity.this, "Email dan Password tidak boleh kosong", Toast.LENGTH_SHORT).show();
                } else {
                    prosesLogin(email, password);
                }
            }
        });
    }

    private void prosesLogin(String email, String password) {
        // Siapkan data JSON untuk dikirim
        HashMap<String, String> loginData = new HashMap<>();
        loginData.put("email", email);
        loginData.put("password", password);

        // Panggil API
        ApiClient.getApiService().login(loginData).enqueue(new Callback<LoginResponse>() {
            @Override
            public void onResponse(Call<LoginResponse> call, Response<LoginResponse> response) {
                if (response.isSuccessful() && response.body() != null) {
                    // Jika sukses, simpan token
                    String token = response.body().getToken();
                    sessionManager.saveToken(token);

                    String namaDriver = response.body().getUser().getName();
                    Toast.makeText(MainActivity.this, "Selamat datang, " + namaDriver, Toast.LENGTH_SHORT).show();

                    // Pindah ke Halaman HomeActivity
                    Intent intent = new Intent(MainActivity.this, HomeActivity.class);
                    startActivity(intent);
                    finish(); // Menutup halaman login

                } else {
                    Log.e("API_ERROR", "Kode Error: " + response.code());
                    Toast.makeText(MainActivity.this, "Login gagal, periksa email/password", Toast.LENGTH_SHORT).show();
                }
            }

            @Override
            public void onFailure(Call<LoginResponse> call, Throwable t) {
                Log.e("API_ERROR", "Gagal Konek: " + t.getMessage());
                Toast.makeText(MainActivity.this, "Error Jaringan: " + t.getMessage(), Toast.LENGTH_SHORT).show();
            }
        });
    }
}