package com.alhuwyd.mbg_project_app;

import android.content.Intent;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.Button;
import android.widget.TextView;
import androidx.annotation.NonNull;
import androidx.recyclerview.widget.RecyclerView;
import com.alhuwyd.mbg_project_app.model.Delivery;
import java.util.List;

public class DeliveryAdapter extends RecyclerView.Adapter<DeliveryAdapter.ViewHolder> {

    private List<Delivery> deliveryList;

    public DeliveryAdapter(List<Delivery> deliveryList) {
        this.deliveryList = deliveryList;
    }

    @NonNull
    @Override
    public ViewHolder onCreateViewHolder(@NonNull ViewGroup parent, int viewType) {
        View view = LayoutInflater.from(parent.getContext()).inflate(R.layout.item_delivery, parent, false);
        return new ViewHolder(view);
    }

    @Override
    public void onBindViewHolder(@NonNull ViewHolder holder, int position) {
        Delivery delivery = deliveryList.get(position);

        if (delivery.getSekolahTujuan() != null) {
            holder.tvNamaSekolah.setText(delivery.getSekolahTujuan().getNama());
        }

        if (delivery.getGuru() != null) {
            holder.tvNamaGuru.setText("Guru: " + delivery.getGuru().getNama() + " (" + delivery.getGuru().getPhone() + ")");
        }

        holder.tvPorsi.setText("Porsi Besar: " + delivery.getJumlahPorsiBesar() + " | Kecil: " + delivery.getJumlahPorsiKecil());
        holder.tvStatus.setText("Status: " + delivery.getStatusPengiriman());

        // Buka halaman TrackingActivity, kirim data delivery via Intent
        holder.btnMulaiAntar.setOnClickListener(v -> {
            Intent intent = new Intent(v.getContext(), TrackingActivity.class);
            intent.putExtra(TrackingActivity.EXTRA_DELIVERY_ID, delivery.getDeliveryId());
            intent.putExtra(TrackingActivity.EXTRA_STATUS, delivery.getStatusPengiriman());

            if (delivery.getSekolahTujuan() != null) {
                intent.putExtra(TrackingActivity.EXTRA_SCHOOL_NAME, delivery.getSekolahTujuan().getNama());
                intent.putExtra(TrackingActivity.EXTRA_SCHOOL_LAT,
                        delivery.getSekolahTujuan().getLatitude() != null ? delivery.getSekolahTujuan().getLatitude() : 0.0);
                intent.putExtra(TrackingActivity.EXTRA_SCHOOL_LNG,
                        delivery.getSekolahTujuan().getLongitude() != null ? delivery.getSekolahTujuan().getLongitude() : 0.0);
            }

            if (delivery.getGuru() != null) {
                intent.putExtra(TrackingActivity.EXTRA_GURU_NAME, delivery.getGuru().getNama());
            }

            v.getContext().startActivity(intent);
        });
    }

    @Override
    public int getItemCount() {
        return deliveryList != null ? deliveryList.size() : 0;
    }

    public static class ViewHolder extends RecyclerView.ViewHolder {
        TextView tvNamaSekolah, tvNamaGuru, tvPorsi, tvStatus;
        Button btnMulaiAntar;

        public ViewHolder(@NonNull View itemView) {
            super(itemView);
            tvNamaSekolah = itemView.findViewById(R.id.tvNamaSekolah);
            tvNamaGuru = itemView.findViewById(R.id.tvNamaGuru);
            tvPorsi = itemView.findViewById(R.id.tvPorsi);
            tvStatus = itemView.findViewById(R.id.tvStatus);
            btnMulaiAntar = itemView.findViewById(R.id.btnMulaiAntar);
        }
    }
}