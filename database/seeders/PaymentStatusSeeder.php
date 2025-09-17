<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cập nhật các đơn hàng hiện có để có trạng thái thanh toán mặc định
        DB::table('orders')->whereNull('payment_status')->update([
            'payment_status' => 'pending',
            'payment_method' => 'cod'
        ]);

        $this->command->info('Đã cập nhật trạng thái thanh toán cho các đơn hàng hiện có!');
    }
}
