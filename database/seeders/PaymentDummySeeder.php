<?php

namespace Database\Seeders;

use App\Constants\OrderStatus;
use App\Constants\PaymentMethod;
use App\Constants\PaymentStatus;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\RestaurantTable;
use App\Models\StatusHistory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class PaymentDummySeeder extends Seeder
{
    public function run(): void
    {
        $menus = MenuItem::all();
        $tables = RestaurantTable::all();

        // dummy ini butuh menu dan meja dari DatabaseSeeder
        if ($menus->isEmpty() || $tables->isEmpty()) {
            $this->command->error('Jalankan DatabaseSeeder dulu (php artisan db:seed).');
            return;
        }

        // bersihkan dummy lama supaya seeder bisa dijalankan berulang
        $this->hapusDummyLama();

        // angka acak dikunci biar datanya selalu sama tiap dijalankan
        mt_srand(42);

        DB::transaction(function () use ($menus, $tables) {
            for ($i = 1; $i <= 60; $i++) {
                $this->buatSatuPesanan($i, $menus, $tables);
            }
        });

        $this->command->info('Selesai: 60 pesanan dummy (prefix DUM-) dibuat.');
    }

    private function hapusDummyLama(): void
    {
        $ids = Order::where('order_number', 'like', 'DUM-%')->pluck('id');

        // urutan hapus: anak dulu baru induk (foreign key restrict)
        Payment::whereIn('order_id', $ids)->delete();
        StatusHistory::whereIn('order_id', $ids)->delete();
        OrderItem::whereIn('order_id', $ids)->delete();
        Order::whereIn('id', $ids)->delete();
    }

    private function buatSatuPesanan(int $no, $menus, $tables): void
    {
        // tanggal acak dalam 45 hari terakhir
        $tanggal = Carbon::now()
            ->subDays(mt_rand(0, 45))
            ->setTime(mt_rand(9, 21), mt_rand(0, 59), 0);

        // tentukan skenario: status pesanan + status pembayaran
        $angka = mt_rand(1, 100);
        if ($angka <= 50) {
            $statusPesanan = OrderStatus::COMPLETED;
            $statusBayar = PaymentStatus::PAID;
        } elseif ($angka <= 65) {
            $statusPesanan = OrderStatus::CONFIRMED;
            $statusBayar = PaymentStatus::PAID;
        } elseif ($angka <= 80) {
            $statusPesanan = OrderStatus::PENDING;
            $statusBayar = PaymentStatus::PENDING;
        } elseif ($angka <= 90) {
            $statusPesanan = OrderStatus::PENDING;
            $statusBayar = PaymentStatus::FAILED;
        } else {
            $statusPesanan = OrderStatus::CANCELLED;
            $statusBayar = PaymentStatus::CANCELLED;
        }

        // buat pesanan dulu, total diisi setelah item dihitung
        $order = new Order([
            'order_number' => 'DUM-' . $tanggal->format('Ymd') . '-' . str_pad($no, 4, '0', STR_PAD_LEFT),
            'restaurant_table_id' => $tables->random()->id,
            'status' => $statusPesanan,
            'total_amount' => 0,
        ]);
        $order->created_at = $tanggal;
        $order->updated_at = $tanggal;
        $order->save();

        // 1 sampai 3 menu per pesanan
        $total = 0;
        foreach ($menus->random(mt_rand(1, 3)) as $menu) {
            $qty = mt_rand(1, 3);
            $subtotal = $menu->price * $qty;
            $total += $subtotal;

            OrderItem::create([
                'order_id' => $order->id,
                'menu_item_id' => $menu->id,
                'menu_name' => $menu->name,
                'price' => $menu->price,
                'quantity' => $qty,
                'subtotal' => $subtotal,
            ]);
        }

        $order->update(['total_amount' => $total]);

        // sebagian pesanan yang sukses pernah gagal bayar dulu (tes one-to-many)
        if ($statusBayar === PaymentStatus::PAID && mt_rand(1, 100) <= 10) {
            $this->buatPembayaran($order, $total, PaymentStatus::FAILED, $tanggal);
        }

        $this->buatPembayaran($order, $total, $statusBayar, $tanggal->copy()->addMinutes(5));

        // riwayat status: selalu mulai dari pending
        $this->catatStatus($order, null, OrderStatus::PENDING, $tanggal);
        if ($statusPesanan !== OrderStatus::PENDING) {
            $this->catatStatus($order, OrderStatus::PENDING, $statusPesanan, $tanggal->copy()->addMinutes(10));
        }
    }

    private function buatPembayaran(Order $order, $jumlah, string $status, Carbon $waktu): void
    {
        $metode = mt_rand(0, 1) ? PaymentMethod::QRIS : PaymentMethod::BANK_TRANSFER;

        $payment = new Payment([
            'order_id' => $order->id,
            'payment_method' => $metode,
            'amount' => $jumlah,
            'status' => $status,
            'paid_at' => $status === PaymentStatus::PAID ? $waktu : null,
        ]);
        $payment->created_at = $waktu;
        $payment->updated_at = $waktu;
        $payment->save();
    }

    private function catatStatus(Order $order, ?string $lama, string $baru, Carbon $waktu): void
    {
        $riwayat = new StatusHistory([
            'order_id' => $order->id,
            'old_status' => $lama,
            'new_status' => $baru,
            'note' => 'Data dummy',
        ]);
        $riwayat->created_at = $waktu;
        $riwayat->save();
    }
}