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
    // Orders table
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Liên kết với bảng users
            $table->string('name'); // Tên người nhận
            $table->string('address'); // Địa chỉ giao hàng
            $table->string('phone'); // Số điện thoại
            $table->decimal('total_price', 15, 2); // Tổng tiền
            $table->string('status')->default('đang xử lý'); // Thêm dòng này
            $table->string('shipping_status')->default('chưa giao hàng'); // Trạng thái giao hàng
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
