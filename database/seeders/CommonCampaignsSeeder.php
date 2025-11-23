<?php

namespace Database\Seeders;

use App\Models\WhatsAppCampaign;
use Illuminate\Database\Seeder;

/**
 * Common Campaigns Seeder
 *
 * Seeds the database with commonly used WhatsApp campaign templates
 *
 * Run with:
 * php artisan db:seed --class=CommonCampaignsSeeder
 *
 * @package Database\Seeders
 */
class CommonCampaignsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $campaigns = [
            [
                'name' => 'Welcome Campaign',
                'description' => 'Welcome message sent to new users with verification code',
                'company_name' => 'Pamulihan App',
                'template' => "Selamat datang [user_name]!\n\nTerima kasih telah mendaftar di [Name_Company].\nKode verifikasi Anda: [code]\n\nGunakan kode ini untuk mengaktifkan akun Anda.",
                'variables' => ['user_name', 'code'],
                'is_active' => true,
            ],
            [
                'name' => 'OTP Campaign',
                'description' => 'OTP verification code for authentication',
                'company_name' => 'Pamulihan App',
                'template' => "Kode OTP Anda: [code]\n\nBerlaku selama [expiry_minutes] menit.\nJangan bagikan kode ini kepada siapapun.\n\n[Name_Company]",
                'variables' => ['code', 'expiry_minutes'],
                'is_active' => true,
            ],
            [
                'name' => 'Order Confirmation',
                'description' => 'Confirmation message sent when order is created',
                'company_name' => 'Pamulihan App',
                'template' => "Halo [customer_name],\n\nPesanan Anda telah diterima!\n\nOrder ID: [order_id]\nTotal: Rp [total]\nJumlah item: [items_count]\n\nTerima kasih telah berbelanja di [Name_Company].",
                'variables' => ['customer_name', 'order_id', 'total', 'items_count'],
                'is_active' => true,
            ],
            [
                'name' => 'Order Processing',
                'description' => 'Notification when order status changes to processing',
                'company_name' => 'Pamulihan App',
                'template' => "Halo [customer_name],\n\nPesanan Anda sedang diproses.\n\nOrder ID: [order_id]\nStatus: Sedang Diproses\n\nKami akan mengirimkan update segera.\n\n[Name_Company]",
                'variables' => ['customer_name', 'order_id'],
                'is_active' => true,
            ],
            [
                'name' => 'Order Shipped',
                'description' => 'Notification when order is shipped',
                'company_name' => 'Pamulihan App',
                'template' => "Pesanan Anda telah dikirim!\n\nOrder ID: [order_id]\nKurir: [courier]\nNo. Resi: [tracking_number]\n\nLacak paket Anda di website kurir.\n\nTerima kasih,\n[Name_Company]",
                'variables' => ['order_id', 'courier', 'tracking_number'],
                'is_active' => true,
            ],
            [
                'name' => 'Order Delivered',
                'description' => 'Notification when order is delivered',
                'company_name' => 'Pamulihan App',
                'template' => "Pesanan Anda telah sampai!\n\nOrder ID: [order_id]\n\nTerima kasih telah berbelanja di [Name_Company].\nKami berharap Anda puas dengan pesanan Anda.\n\nJangan lupa untuk memberikan review!",
                'variables' => ['order_id'],
                'is_active' => true,
            ],
            [
                'name' => 'Order Cancelled',
                'description' => 'Notification when order is cancelled',
                'company_name' => 'Pamulihan App',
                'template' => "Pesanan Anda telah dibatalkan.\n\nOrder ID: [order_id]\nAlasan: [cancellation_reason]\n\nJika Anda memiliki pertanyaan, silakan hubungi customer service kami.\n\n[Name_Company]",
                'variables' => ['order_id', 'cancellation_reason'],
                'is_active' => true,
            ],
            [
                'name' => 'Payment Reminder',
                'description' => 'Reminder for pending payment',
                'company_name' => 'Pamulihan App',
                'template' => "Pengingat Pembayaran\n\nOrder ID: [order_id]\nJumlah: Rp [amount_due]\nJatuh tempo: [due_date]\n\nSegera lakukan pembayaran untuk menghindari pembatalan otomatis.\n\n[Name_Company]",
                'variables' => ['order_id', 'amount_due', 'due_date'],
                'is_active' => true,
            ],
            [
                'name' => 'Payment Received',
                'description' => 'Confirmation when payment is received',
                'company_name' => 'Pamulihan App',
                'template' => "Pembayaran Diterima\n\nHalo [customer_name],\n\nPembayaran Anda telah kami terima.\n\nOrder ID: [order_id]\nJumlah: Rp [amount]\n\nPesanan Anda akan segera diproses.\n\n[Name_Company]",
                'variables' => ['customer_name', 'order_id', 'amount'],
                'is_active' => true,
            ],
            [
                'name' => 'Password Reset',
                'description' => 'Password reset link or code',
                'company_name' => 'Pamulihan App',
                'template' => "Reset Password\n\nHalo [user_name],\n\nKode reset password Anda: [reset_code]\n\nBerlaku selama [expiry_minutes] menit.\n\nJika Anda tidak meminta reset password, abaikan pesan ini.\n\n[Name_Company]",
                'variables' => ['user_name', 'reset_code', 'expiry_minutes'],
                'is_active' => true,
            ],
            [
                'name' => 'Account Update',
                'description' => 'Notification when user updates their account',
                'company_name' => 'Pamulihan App',
                'template' => "Akun Anda Diperbarui\n\nHalo [user_name],\n\nInformasi akun Anda telah berhasil diperbarui.\n\nJika bukan Anda yang melakukan perubahan ini, segera hubungi customer service.\n\n[Name_Company]",
                'variables' => ['user_name'],
                'is_active' => true,
            ],
            [
                'name' => 'Product Created Notification',
                'description' => 'Notification when a new product is created',
                'company_name' => 'Pamulihan App',
                'template' => "Produk Baru Ditambahkan\n\nHalo [user_name],\n\nProduk '[product_name]' telah berhasil ditambahkan.\n\nHarga: Rp [price]\nSKU: [sku]\n\nProduk sudah bisa dilihat di katalog.\n\n[Name_Company]",
                'variables' => ['user_name', 'product_name', 'price', 'sku'],
                'is_active' => false, // Inactive by default, activate if needed
            ],
            [
                'name' => 'Product Updated Notification',
                'description' => 'Notification when product is updated',
                'company_name' => 'Pamulihan App',
                'template' => "Produk Diperbarui\n\nHalo [user_name],\n\nProduk '[product_name]' telah diperbarui.\n\nField yang diubah: [updated_field]\n\n[Name_Company]",
                'variables' => ['user_name', 'product_name', 'updated_field'],
                'is_active' => false,
            ],
            [
                'name' => 'Weekly Newsletter',
                'description' => 'Weekly newsletter to subscribed users',
                'company_name' => 'Pamulihan App',
                'template' => "Newsletter Mingguan\n\nHalo [user_name],\n\nBerikut update minggu ini:\n\n[content]\n\nTerima kasih telah berlangganan!\n\n[Name_Company]",
                'variables' => ['user_name', 'content'],
                'is_active' => false,
            ],
            [
                'name' => 'Promotion Campaign',
                'description' => 'Promotional message with discount code',
                'company_name' => 'Pamulihan App',
                'template' => "Promo Spesial untuk Anda!\n\nHalo [user_name],\n\nDapatkan diskon [discount_percent]% dengan kode:\n[discount_code]\n\nBerlaku hingga: [expiry_date]\nMinimal pembelian: Rp [min_purchase]\n\nJangan lewatkan!\n\n[Name_Company]",
                'variables' => ['user_name', 'discount_percent', 'discount_code', 'expiry_date', 'min_purchase'],
                'is_active' => false,
            ],
            [
                'name' => 'Birthday Greeting',
                'description' => 'Birthday greeting message',
                'company_name' => 'Pamulihan App',
                'template' => "Selamat Ulang Tahun!\n\nHalo [user_name],\n\nSelamat ulang tahun yang ke-[age]!\n\nSebagai hadiah, nikmati voucher diskon [discount_percent]% dengan kode: [voucher_code]\n\nBerlaku hari ini!\n\n[Name_Company]",
                'variables' => ['user_name', 'age', 'discount_percent', 'voucher_code'],
                'is_active' => false,
            ],
            [
                'name' => 'Stock Alert',
                'description' => 'Notification when product is back in stock',
                'company_name' => 'Pamulihan App',
                'template' => "Stok Tersedia!\n\nHalo [user_name],\n\nProduk '[product_name]' yang Anda tunggu sudah tersedia kembali!\n\nHarga: Rp [price]\nStok: [stock_qty] unit\n\nBuruan pesan sebelum kehabisan!\n\n[Name_Company]",
                'variables' => ['user_name', 'product_name', 'price', 'stock_qty'],
                'is_active' => false,
            ],
            [
                'name' => 'Review Request',
                'description' => 'Request for product/service review',
                'company_name' => 'Pamulihan App',
                'template' => "Berikan Review Anda\n\nHalo [customer_name],\n\nTerima kasih telah berbelanja!\n\nOrder ID: [order_id]\n\nKami sangat menghargai feedback Anda. Berikan review untuk pesanan ini dan dapatkan poin loyalti!\n\n[Name_Company]",
                'variables' => ['customer_name', 'order_id'],
                'is_active' => false,
            ],
            [
                'name' => 'Heir Certificate Ready',
                'description' => 'Notification sent when heir certificate is ready for pickup',
                'company_name' => 'Pamulihan App',
                'template' => "[Name_Company]. Hello [applicant_name], \n\nYour Heir Certificate has been processed and is ready for pickup.\n\nPlease contact our office to collect your certificate.\n\nThank you.",
                'variables' => ['applicant_name'],
                'is_active' => true,
            ],
        ];

        foreach ($campaigns as $campaignData) {
            WhatsAppCampaign::updateOrCreate(
                ['name' => $campaignData['name']], // Match by name
                array_merge($campaignData, [
                    'created_by' => 1, // Assuming user ID 1 is admin
                    'usage_count' => 0,
                ])
            );
        }

        $this->command->info('Common campaigns seeded successfully!');
        $this->command->info('Total campaigns: ' . count($campaigns));
        $this->command->info('Active campaigns: ' . collect($campaigns)->where('is_active', true)->count());
        $this->command->info('Inactive campaigns: ' . collect($campaigns)->where('is_active', false)->count());
    }
}
