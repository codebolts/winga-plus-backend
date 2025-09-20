<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function getSalesReport(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'seller') {
            return ApiResponse::error('Unauthorized', [], 403);
        }

        $period = $request->get('period', 'month');
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');

        // Calculate date range based on period if not provided
        if (!$startDate || !$endDate) {
            $now = Carbon::now();
            switch ($period) {
                case 'week':
                    $startDate = $now->startOfWeek()->format('Y-m-d');
                    $endDate = $now->endOfWeek()->format('Y-m-d');
                    break;
                case 'month':
                    $startDate = $now->startOfMonth()->format('Y-m-d');
                    $endDate = $now->endOfMonth()->format('Y-m-d');
                    break;
                case 'year':
                    $startDate = $now->startOfYear()->format('Y-m-d');
                    $endDate = $now->endOfYear()->format('Y-m-d');
                    break;
                default:
                    $startDate = $now->startOfMonth()->format('Y-m-d');
                    $endDate = $now->endOfMonth()->format('Y-m-d');
            }
        }

        // Get sales data for the seller's products
        $salesData = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.seller_id', $user->id)
            ->where('orders.status', 'completed')
            ->whereBetween('orders.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->select([
                DB::raw('DATE(orders.created_at) as date'),
                DB::raw('SUM(order_items.quantity * order_items.price) as revenue'),
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('COUNT(DISTINCT orders.id) as total_orders')
            ])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $totalRevenue = $salesData->sum('revenue');
        $totalOrders = $salesData->sum('total_orders');
        $totalQuantity = $salesData->sum('total_quantity');

        return ApiResponse::success('Sales report retrieved successfully', [
            'period' => $period,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_revenue' => $totalRevenue,
            'total_orders' => $totalOrders,
            'total_quantity_sold' => $totalQuantity,
            'daily_sales' => $salesData
        ]);
    }

    public function getProductReports(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'seller') {
            return ApiResponse::error('Unauthorized', [], 403);
        }

        $limit = (int) $request->get('limit', 20);
        $sortBy = $request->get('sort_by', 'revenue');
        $sortOrder = $request->get('sort_order', 'desc');

        // Validate sort_by
        $validSortFields = ['revenue', 'quantity', 'orders_count'];
        if (!in_array($sortBy, $validSortFields)) {
            $sortBy = 'revenue';
        }

        // Get product performance data
        $products = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.seller_id', $user->id)
            ->where('orders.status', 'completed')
            ->select([
                'products.id',
                'products.name',
                'products.price',
                'products.image',
                DB::raw('SUM(order_items.quantity * order_items.price) as revenue'),
                DB::raw('SUM(order_items.quantity) as total_quantity_sold'),
                DB::raw('COUNT(DISTINCT orders.id) as orders_count'),
                DB::raw('AVG(order_items.price) as average_price')
            ])
            ->groupBy('products.id', 'products.name', 'products.price', 'products.image')
            ->orderBy($sortBy, $sortOrder)
            ->limit($limit)
            ->get();

        return ApiResponse::success('Product reports retrieved successfully', $products);
    }

    public function getCustomerReport(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'seller') {
            return ApiResponse::error('Unauthorized', [], 403);
        }

        $period = $request->get('period', 'month');

        // Calculate date range
        $now = Carbon::now();
        switch ($period) {
            case 'week':
                $startDate = $now->startOfWeek()->format('Y-m-d');
                $endDate = $now->endOfWeek()->format('Y-m-d');
                break;
            case 'month':
                $startDate = $now->startOfMonth()->format('Y-m-d');
                $endDate = $now->endOfMonth()->format('Y-m-d');
                break;
            case 'year':
                $startDate = $now->startOfYear()->format('Y-m-d');
                $endDate = $now->endOfYear()->format('Y-m-d');
                break;
            default:
                $startDate = $now->startOfMonth()->format('Y-m-d');
                $endDate = $now->endOfMonth()->format('Y-m-d');
        }

        // Get customer data
        $customers = Order::join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('users', 'orders.buyer_id', '=', 'users.id')
            ->where('products.seller_id', $user->id)
            ->where('orders.status', 'completed')
            ->whereBetween('orders.created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->select([
                'users.id',
                'users.name',
                'users.email',
                DB::raw('COUNT(DISTINCT orders.id) as total_orders'),
                DB::raw('SUM(order_items.quantity * order_items.price) as total_spent'),
                DB::raw('MAX(orders.created_at) as last_order_date')
            ])
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderBy('total_spent', 'desc')
            ->get();

        $totalCustomers = $customers->count();
        $newCustomers = $customers->where('last_order_date', '>=', Carbon::now()->subDays(30))->count();
        $returningCustomers = $totalCustomers - $newCustomers;

        return ApiResponse::success('Customer report retrieved successfully', [
            'period' => $period,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'total_customers' => $totalCustomers,
            'new_customers' => $newCustomers,
            'returning_customers' => $returningCustomers,
            'top_customers' => $customers->take(10)
        ]);
    }

    public function getDashboardStats(Request $request)
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'seller') {
            return ApiResponse::error('Unauthorized', [], 403);
        }

        // Total products
        $totalProducts = Product::where('seller_id', $user->id)->count();

        // Total orders (completed)
        $totalOrders = Order::join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.seller_id', $user->id)
            ->where('orders.status', 'completed')
            ->distinct('orders.id')
            ->count('orders.id');

        // Total revenue
        $totalRevenue = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.seller_id', $user->id)
            ->where('orders.status', 'completed')
            ->sum(DB::raw('order_items.quantity * order_items.price'));

        // Monthly revenue (current month)
        $monthlyRevenue = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.seller_id', $user->id)
            ->where('orders.status', 'completed')
            ->whereMonth('orders.created_at', Carbon::now()->month)
            ->whereYear('orders.created_at', Carbon::now()->year)
            ->sum(DB::raw('order_items.quantity * order_items.price'));

        // Recent orders (last 5)
        $recentOrders = Order::join('order_items', 'orders.id', '=', 'order_items.order_id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('users', 'orders.buyer_id', '=', 'users.id')
            ->where('products.seller_id', $user->id)
            ->where('orders.status', 'completed')
            ->select([
                'orders.id',
                'orders.total_price',
                'orders.created_at',
                'users.name as buyer_name'
            ])
            ->distinct('orders.id')
            ->orderBy('orders.created_at', 'desc')
            ->limit(5)
            ->get();

        // Top selling products (top 5)
        $topProducts = OrderItem::join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->where('products.seller_id', $user->id)
            ->where('orders.status', 'completed')
            ->select([
                'products.id',
                'products.name',
                DB::raw('SUM(order_items.quantity) as total_sold')
            ])
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->limit(5)
            ->get();

        return ApiResponse::success('Dashboard stats retrieved successfully', [
            'total_products' => $totalProducts,
            'total_orders' => $totalOrders,
            'total_revenue' => $totalRevenue,
            'monthly_revenue' => $monthlyRevenue,
            'recent_orders' => $recentOrders,
            'top_products' => $topProducts
        ]);
    }
}
