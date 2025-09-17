<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Orders;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        /**
         * Nếu muốn chỉ tính doanh thu cho đơn đã giao xong,
         * thay whereIn('orders.status', [...]) bằng:
         * ->where('orders.shipping_status', 'completed')
         */
        $paidStatuses = ['đã thanh toán', 'đã đặt (COD)', 'chờ xử lý'];
        // 1) Doanh thu theo danh mục (category_id) từ order_items
        $categoryRevenue = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', $paidStatuses) // hoặc ->where('orders.shipping_status', 'completed')
            ->select(
                'products.category_id',
                'categories.name as category_name',
                DB::raw('SUM(order_items.price * order_items.quantity) AS total_revenue'),
                DB::raw('SUM(order_items.quantity) AS total_qty')
            )
            ->groupBy('products.category_id', 'categories.name')
            ->orderByDesc('total_revenue')
            ->get();
        // 2) Tổng số đơn hàng (tất cả trạng thái)
        $totalOrders = Orders::count();
        // 3) Tổng số khách hàng (role='user' hoặc không phải admin)
        $totalCustomers = DB::table('users')->where('role', '!=', 'admin')->count();
        // 4) Doanh thu theo ngày: SUM(orders.total_price)
        $revenueByDate = Orders::query()
            ->whereIn('status', $paidStatuses) // hoặc ->where('shipping_status', 'completed')
            ->selectRaw('DATE(created_at) AS date, SUM(total_price) AS total_revenue, COUNT(*) AS order_count')
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get();
        // 5) Doanh thu theo tháng (YYYY-MM)
        $revenueByMonth = Orders::query()
            ->whereIn('status', $paidStatuses)
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") AS month, SUM(total_price) AS total_revenue, COUNT(*)

AS order_count')

            ->groupBy('month')
            ->orderBy('month')
            ->get();
        // 6) Doanh thu theo năm
        $revenueByYear = Orders::query()
            ->whereIn('status', $paidStatuses)
            ->selectRaw('YEAR(created_at) AS year, SUM(total_price) AS total_revenue, COUNT(*) AS order_count')
            ->groupBy('year')
            ->orderBy('year')
            ->get();

        // 7) Tổng doanh thu
        $totalRevenue = Orders::whereIn('status', $paidStatuses)->sum('total_price');

        // 8) Đơn hàng đã thanh toán
        $paidOrders = Orders::whereIn('status', $paidStatuses)->count();

        // 9) Khách hàng tiềm năng (khách hàng có nhiều đơn hàng nhất)
        $topCustomers = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->whereIn('orders.status', $paidStatuses)
            ->select(
                'users.name',
                'users.email',
                DB::raw('COUNT(orders.id) as total_orders'),
                DB::raw('SUM(orders.total_price) as total_spent')
            )
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('total_spent')
            ->limit(10)
            ->get();

        // 10) Sản phẩm bán chạy (top 10)
        $topSellingProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', $paidStatuses)
            ->select(
                'products.name',
                'products.image',
                'products.price',
                DB::raw('SUM(order_items.quantity) as total_sold'),
                DB::raw('SUM(order_items.price * order_items.quantity) as total_revenue')
            )
            ->groupBy('products.id', 'products.name', 'products.image', 'products.price')
            ->orderByDesc('total_sold')
            ->limit(10)
            ->get();

        return view('admin.reports.index', compact(
            'categoryRevenue',
            'totalOrders',
            'totalCustomers',
            'revenueByDate',
            'revenueByMonth',
            'revenueByYear',
            'totalRevenue',
            'paidOrders',
            'topCustomers',
            'topSellingProducts'
        ));
    }
    public function charts()
    {
        $paid = ['đã thanh toán', 'đã đặt (COD)', 'chờ xử lý'];

        /* 1) Doanh thu theo danh mục */
        $byCat = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->whereIn('orders.status', $paid)
            ->selectRaw('COALESCE(categories.name, CONCAT("Category #", products.category_id)) AS name')
            ->selectRaw('SUM(order_items.price * order_items.quantity) AS revenue')
            ->groupBy('name')
            ->orderByDesc('revenue')
            ->get();

        $catLabels = $byCat->pluck('name')->toArray();
        $catRevenue = $byCat->pluck('revenue')->map(fn($v) => (float)$v)->toArray();

        /* 2) Doanh thu theo ngày (30 ngày) */
        $startDay = Carbon::now()->subDays(29)->startOfDay();
        $endDay = Carbon::now()->endOfDay();
        $byDate = Orders::whereIn('status', $paid)
            ->whereBetween('created_at', [$startDay, $endDay])
            ->selectRaw('DATE(created_at) d, SUM(total_price) revenue')
            ->groupBy('d')->orderBy('d')->get()->keyBy('d');

        $revDateLabels = [];
        $revDateData = [];
        for ($i = 0; $i < 30; $i++) {
            $d = $startDay->copy()->addDays($i)->toDateString();
            $revDateLabels[] = Carbon::parse($d)->format('d/m');
            $revDateData[] = (float) ($byDate[$d]->revenue ?? 0);
        }

        /* 3) Doanh thu theo tháng (12 tháng) */
        $startMonth = Carbon::now()->subMonths(11)->startOfMonth();
        $byMonth = Orders::whereIn('status', $paid)
            ->selectRaw('DATE_FORMAT(created_at, "%Y-%m") ym, SUM(total_price) revenue')
            ->groupBy('ym')->orderBy('ym')->get()->keyBy('ym');

        $revMonthLabels = [];
        $revMonthData = [];
        for ($i = 0; $i < 12; $i++) {
            $m = $startMonth->copy()->addMonths($i);
            $key = $m->format('Y-m');
            $revMonthLabels[] = $m->format('m/Y');
            $revMonthData[] = (float) ($byMonth[$key]->revenue ?? 0);
        }

        /* 4) Doanh thu theo năm */
        $byYear = Orders::whereIn('status', $paid)
            ->selectRaw('YEAR(created_at) y, SUM(total_price) revenue')
            ->groupBy('y')->orderBy('y')->get();

        $revYearLabels = $byYear->pluck('y')->toArray();
        $revYearData = $byYear->pluck('revenue')->map(fn($v) => (float)$v)->toArray();

        /* 5) Doanh thu theo phương thức thanh toán */
        $paymentMethodLabels = ['Momo', 'COD', 'Chờ xử lý'];
        $paymentMethodRevenue = [
            (float) Orders::where('status', 'đã thanh toán')->sum('total_price'),
            (float) Orders::where('status', 'đã đặt (COD)')->sum('total_price'),
            (float) Orders::where('status', 'chờ xử lý')->sum('total_price'),
        ];

        return view('admin.orders.charts', compact(
            'catLabels',
            'catRevenue',
            'revDateLabels',
            'revDateData',
            'revMonthLabels',
            'revMonthData',
            'revYearLabels',
            'revYearData',
            'paymentMethodLabels',
            'paymentMethodRevenue'
        ));
    }
}
