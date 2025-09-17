<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Orders Model
class Orders extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'address',
        'phone',
        'total_price',
        'status',
        'payment_method',
        'payment_status',
        'transaction_id',
        'paid_at',
        'shipping_status'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    // Một đơn hàng thuộc về một người dùng
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Một đơn hàng có nhiều mục sản phẩm
    public function items()
    {
        return $this->hasMany(OrderItems::class, 'order_id');
    }

    // Helper methods cho trạng thái thanh toán
    public function isPending()
    {
        return $this->payment_status === 'pending';
    }

    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }

    public function isFailed()
    {
        return $this->payment_status === 'failed';
    }

    public function isCancelled()
    {
        return $this->payment_status === 'cancelled';
    }

    // Helper methods cho phương thức thanh toán
    public function isCOD()
    {
        return $this->payment_method === 'cod';
    }

    public function isMoMo()
    {
        return $this->payment_method === 'momo';
    }

    public function isBank()
    {
        return $this->payment_method === 'bank';
    }

    // Lấy tên hiển thị của trạng thái thanh toán
    public function getPaymentStatusTextAttribute()
    {
        $statuses = [
            'pending' => 'Chờ thanh toán',
            'paid' => 'Đã thanh toán',
            'failed' => 'Thanh toán thất bại',
            'cancelled' => 'Đã hủy'
        ];

        return $statuses[$this->payment_status] ?? $this->payment_status;
    }

    // Lấy tên hiển thị của phương thức thanh toán
    public function getPaymentMethodTextAttribute()
    {
        $methods = [
            'cod' => 'Thanh toán khi nhận hàng',
            'momo' => 'Ví MoMo',
            'bank' => 'Chuyển khoản ngân hàng'
        ];

        return $methods[$this->payment_method] ?? $this->payment_method;
    }

    // Lấy class CSS cho badge trạng thái
    public function getPaymentStatusBadgeClassAttribute()
    {
        $classes = [
            'pending' => 'badge bg-warning',
            'paid' => 'badge bg-success',
            'failed' => 'badge bg-danger',
            'cancelled' => 'badge bg-secondary'
        ];

        return $classes[$this->payment_status] ?? 'badge bg-secondary';
    }
}
