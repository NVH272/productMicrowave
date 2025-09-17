<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('status'); // cod, momo, bank
            $table->string('payment_status')->default('pending')->after('payment_method'); // pending, paid, failed, cancelled
            $table->string('transaction_id')->nullable()->after('payment_status'); // ID giao dịch từ MoMo
            $table->timestamp('paid_at')->nullable()->after('transaction_id'); // Thời gian thanh toán thành công
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'payment_status', 'transaction_id', 'paid_at']);
        });
    }
};
