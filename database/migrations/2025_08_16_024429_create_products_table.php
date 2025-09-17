<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');                // Tên sản phẩm
            $table->string('brand');               // Thương hiệu
            $table->string('model')->nullable();   // Model sản phẩm
            $table->integer('capacity');           // Dung tích (lít)
            $table->integer('power');              // Công suất (Watt)
            $table->integer('voltage')->default(220); // Điện áp
            $table->string('color')->nullable();   // Màu sắc
            $table->decimal('weight', 5, 2)->nullable(); // Cân nặng (kg)
            $table->string('dimensions')->nullable();    // Kích thước
            $table->text('functions')->nullable(); // Chức năng
            $table->integer('warranty')->default(12); // Bảo hành (tháng)
            $table->decimal('price', 10, 2);       // Giá
            $table->integer('stock')->default(0);  // Tồn kho
            $table->string('image')->nullable();   // Hình ảnh sản phẩm (đường dẫn)

            // Khóa ngoại liên kết với categories
            $table->foreignId('category_id')
                ->constrained('categories')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
