@extends('layouts.app')

@section('content')
<style>
    .chart-container {
        position: relative;
        height: 350px;
        width: 100%;
    }

    /* Admin specific styles - simplified */
    .admin-header {
        background: #f8f9fa;
        color: #495057;
        padding: 1.5rem 0;
        margin-bottom: 1.5rem;
        border-radius: 0.25rem;
        border: 1px solid #dee2e6;
    }

    .admin-card {
        background: #fff;
        border-radius: 0.375rem;
        border: 1px solid #dee2e6;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: box-shadow 0.15s ease-in-out;
        margin-bottom: 1.5rem;
    }

    .admin-card:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }

    .admin-card-header {
        background: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
        padding: 0.75rem 1rem;
        border-radius: 0.375rem 0.375rem 0 0;
        font-weight: 600;
        color: #495057;
        font-size: 0.9rem;
    }

    .admin-card-body {
        padding: 1rem;
    }

    .btn-admin {
        background: #007bff;
        color: white;
        border: none;
        border-radius: 0.25rem;
        padding: 0.5rem 1rem;
        font-weight: 500;
        transition: background-color 0.15s ease-in-out;
        text-decoration: none;
        display: inline-block;
        font-size: 0.875rem;
    }

    .btn-admin:hover {
        background: #0056b3;
        color: white;
    }

    .btn-outline-admin {
        background: transparent;
        color: #007bff;
        border: 1px solid #007bff;
        border-radius: 0.25rem;
        padding: 0.5rem 1rem;
        font-weight: 500;
        transition: all 0.15s ease-in-out;
        text-decoration: none;
        display: inline-block;
        font-size: 0.875rem;
    }

    .btn-outline-admin:hover {
        background: #007bff;
        color: white;
    }
</style>

<div class="container-fluid">
    <!-- Admin Header -->
    <div class="admin-header text-center">
        <div class="container">
            <h2 class="mb-2">📊 Biểu đồ Doanh thu</h2>
            <p class="mb-3 text-muted">Thống kê và phân tích doanh thu</p>

            <!-- Navigation Buttons -->
            <div class="d-flex justify-content-center gap-2 flex-wrap">
                <a href="{{ route('admin.dashboard') }}" class="btn-admin">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="{{ route('admin.reports.index') }}" class="btn-outline-admin">
                    <i class="fas fa-chart-bar"></i> Báo cáo
                </a>
                <a href="{{ route('admin.orders.index') }}" class="btn-outline-admin">
                    <i class="fas fa-shopping-cart"></i> Đơn hàng
                </a>
            </div>
        </div>
    </div>

    <!-- Charts Row 1 -->
    <div class="row">
        <div class="col-lg-6 mb-3">
            <div class="admin-card h-100">
                <div class="admin-card-header">
                    <i class="fas fa-chart-bar text-primary"></i> Doanh thu theo danh mục
                </div>
                <div class="admin-card-body">
                    <div class="chart-container">
                        <canvas id="categoryRevenueChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-3">
            <div class="admin-card h-100">
                <div class="admin-card-header">
                    <i class="fas fa-chart-line text-success"></i> Doanh thu theo ngày (30 ngày)
                </div>
                <div class="admin-card-body">
                    <div class="chart-container">
                        <canvas id="revenueByDateChart"></canvas>
                    </div>
                </div>
            </div>
            </div>
        </div>

    <!-- Charts Row 2 -->
    <div class="row">
        <div class="col-lg-6 mb-3">
            <div class="admin-card h-100">
                <div class="admin-card-header">
                    <i class="fas fa-chart-bar text-info"></i> Doanh thu theo tháng (12 tháng)
                </div>
                <div class="admin-card-body">
                    <div class="chart-container">
                        <canvas id="revenueByMonthChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6 mb-3">
            <div class="admin-card h-100">
                <div class="admin-card-header">
                    <i class="fas fa-chart-bar text-warning"></i> Doanh thu theo năm
                </div>
                <div class="admin-card-body">
                    <div class="chart-container">
                        <canvas id="revenueByYearChart"></canvas>
                    </div>
                </div>
            </div>
            </div>
        </div>

    <!-- Charts Row 3 -->
    <div class="row">
        <div class="col-lg-12">
            <div class="admin-card">
                <div class="admin-card-header">
                    <i class="fas fa-chart-pie text-danger"></i> Doanh thu theo phương thức thanh toán
                </div>
                <div class="admin-card-body">
                    <div class="chart-container">
                        <canvas id="revenueByPaymentMethodChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <!-- <div class="text-center mt-3">
        <a href="{{ route('admin.reports.index') }}" class="btn-outline-admin">
            <i class="fas fa-arrow-left"></i> Quay lại Báo cáo
        </a>
    </div> -->
</div>
<script>
    window.addEventListener('DOMContentLoaded', () => {
        // Lấy mảng từ Controller (fallback rỗng để không lỗi)
        const catLabels = {!! json_encode($catLabels ?? []) !!};
        const catRevenue = {!! json_encode($catRevenue ?? []) !!}.map(Number);

        const revDateLabels = {!! json_encode($revDateLabels ?? []) !!};
        const revDateData = ({!! json_encode($revDateData ?? []) !!} || []).map(Number);

        const revMonthLabels = {!! json_encode($revMonthLabels ?? []) !!};
        const revMonthData = ({!! json_encode($revMonthData ?? []) !!} || []).map(Number);

        const revYearLabels = {!! json_encode($revYearLabels ?? []) !!};
        const revYearData = ({!! json_encode($revYearData ?? []) !!} || []).map(Number);

        const payLabels = {!! json_encode($paymentMethodLabels ?? []) !!};
        const payRevenue = ({!! json_encode($paymentMethodRevenue ?? []) !!} || []).map(Number);

        // Chart colors
        const colors = {
            primary: '#4e73df',
            success: '#1cc88a',
            info: '#36b9cc',
            warning: '#f6c23e',
            danger: '#e74a3b',
            secondary: '#858796',
            light: '#f8f9fc',
            dark: '#5a5c69'
        };

        const gradientColors = [
            'rgba(78, 115, 223, 0.8)',
            'rgba(28, 200, 138, 0.8)',
            'rgba(54, 185, 204, 0.8)',
            'rgba(246, 194, 62, 0.8)',
            'rgba(231, 74, 59, 0.8)',
            'rgba(133, 135, 150, 0.8)'
        ];

        // Helper function to create charts
        const createChart = (elementId, type, labels, data, label, options = {}) => {
            const ctx = document.getElementById(elementId);
            if (!ctx) return null;

            const defaultOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 12,
                                family: "'Nunito', sans-serif"
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: colors.primary,
                        borderWidth: 1,
                        cornerRadius: 6,
                        displayColors: true
                    }
                },
                scales: type === 'pie' ? {} : {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 11,
                                family: "'Nunito', sans-serif"
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.1)'
                        },
                        ticks: {
                            font: {
                                size: 11,
                                family: "'Nunito', sans-serif"
                            },
                            callback: function(value) {
                                return new Intl.NumberFormat('vi-VN').format(value) + ' VNĐ';
                            }
                        }
                    }
                }
            };

            const chartOptions = {
                ...defaultOptions,
                ...options
            };

            const chartData = {
                labels: labels,
                datasets: [{
                    label: label,
                    data: data,
                    backgroundColor: type === 'pie' ? gradientColors.slice(0, labels.length) : gradientColors[0],
                    borderColor: type === 'pie' ? gradientColors.slice(0, labels.length) : colors.primary,
                    borderWidth: 2,
                    fill: type === 'line' ? true : false,
                    tension: type === 'line' ? 0.4 : 0
                }]
            };

            return new Chart(ctx, {
                type: type,
                data: chartData,
                options: chartOptions
            });
        };

        // Debug: Log data to console
        console.log('Chart Data:', {
            catLabels, catRevenue,
            revDateLabels, revDateData,
            revMonthLabels, revMonthData,
            revYearLabels, revYearData,
            payLabels, payRevenue
        });

        // Fallback data if no data available
        if (catLabels.length === 0) {
            catLabels = ['Không có dữ liệu'];
            catRevenue = [0];
        }
        if (revDateLabels.length === 0) {
            revDateLabels = ['Hôm nay'];
            revDateData = [0];
        }
        if (revMonthLabels.length === 0) {
            revMonthLabels = ['Tháng này'];
            revMonthData = [0];
        }
        if (revYearLabels.length === 0) {
            revYearLabels = [new Date().getFullYear()];
            revYearData = [0];
        }
        if (payLabels.length === 0) {
            payLabels = ['Chưa có dữ liệu'];
            payRevenue = [0];
        }

        // Create all charts
        try {
            // Category Revenue Chart
            createChart('categoryRevenueChart', 'bar', catLabels, catRevenue, 'Doanh thu (VNĐ)');

            // Revenue by Date Chart
            createChart('revenueByDateChart', 'line', revDateLabels, revDateData, 'Doanh thu (VNĐ)', {
            elements: {
                line: {
                        tension: 0.4
                    }
                },
                plugins: {
                    legend: {
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 12,
                                family: "'Nunito', sans-serif"
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: colors.primary,
                        borderWidth: 1,
                        cornerRadius: 6,
                        callbacks: {
                            label: function(context) {
                                return 'Doanh thu: ' + new Intl.NumberFormat('vi-VN').format(context.parsed.y) + ' VNĐ';
                            }
                        }
                    }
                }
            });

            // Revenue by Month Chart
            createChart('revenueByMonthChart', 'bar', revMonthLabels, revMonthData, 'Doanh thu (VNĐ)');

            // Revenue by Year Chart
            createChart('revenueByYearChart', 'bar', revYearLabels, revYearData, 'Doanh thu (VNĐ)');

            // Payment Method Chart (Pie)
            createChart('revenueByPaymentMethodChart', 'pie', payLabels, payRevenue, 'Doanh thu (VNĐ)', {
                plugins: {
                    legend: {
                        position: 'right',
                        labels: {
                            usePointStyle: true,
                            padding: 20,
                            font: {
                                size: 12,
                                family: "'Nunito', sans-serif"
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: colors.primary,
                        borderWidth: 1,
                        cornerRadius: 6,
                        callbacks: {
                            label: function(context) {
                                const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                const percentage = ((context.parsed / total) * 100).toFixed(1);
                                return context.label + ': ' + new Intl.NumberFormat('vi-VN').format(context.parsed) + ' VNĐ (' + percentage + '%)';
                            }
                        }
                    }
                }
            });

        } catch (error) {
            console.error('Error creating charts:', error);

            // Show error message if charts fail to load
            const errorDiv = document.createElement('div');
            errorDiv.className = 'alert alert-danger';
            errorDiv.innerHTML = '<i class="fas fa-exclamation-triangle"></i> Có lỗi xảy ra khi tải biểu đồ. Vui lòng tải lại trang.';
            document.querySelector('.container-fluid').insertBefore(errorDiv, document.querySelector('.row'));
        }
    });
</script>

@endsection