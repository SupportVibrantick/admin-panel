@extends("admin.layout.admin-master")
@section("title", "Welcome to Admin Panel")
@section("content")
    <div class="content-body">
        <div class="container-fluid">
            <!-- Welcome Header -->
            <div class="card border-0 bg-primary gradient-card mb-4" style="background: linear-gradient(135deg, #284a8a 0%, #aece5b 100%);">
                <div class="card-body text-white p-4">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-2 fw-bold text-white">Welcome back, {{ auth()->user()->name }}!</h2>
                            <p class="mb-0 opacity-75 ">Here's what's happening in your network today. You have <strong>{{ $pendingApprovals }}</strong> pending approvals in the holding tank.</p>
                        </div>
                        <div class="col-md-4 text-md-end mt-3 mt-md-0">
                          
                            <button class="btn btn-light text-primary">
                                <i class="las la-file-alt me-2"></i>View Reports
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row">
                <div class="col-xl-3 col-lg-6 col-sm-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-box bg-success bg-opacity-10 rounded-3 p-3 me-3">
                                    <i class="las la-rupee-sign text-success fs-2"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-muted mb-1 fs-14">Total Revenue</p>
                                    <h3 class="mb-0 fw-bold">₹{{ number_format($totalRevenue) }}</h3>
                                    <small class="text-success"><i class="las la-arrow-up"></i> +12.5%</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6 col-sm-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-box bg-primary bg-opacity-10 rounded-3 p-3 me-3">
                                    <i class="las la-shopping-cart text-primary fs-2"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-muted mb-1 fs-14">Total Orders</p>
                                    <h3 class="mb-0 fw-bold">{{ $totalOrders }}</h3>
                                    <small class="text-success"><i class="las la-arrow-up"></i> +8.2%</small>
                                    <p class="mb-0 fs-12 text-muted">{{ $completedOrders }} completed</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6 col-sm-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-box bg-purple bg-opacity-10 rounded-3 p-3 me-3">
                                    <i class="las la-users text-purple fs-2"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-muted mb-1 fs-14">Active Users</p>
                                    <h3 class="mb-0 fw-bold">{{ $activeUsers }}</h3>
                                    <small class="text-success"><i class="las la-arrow-up"></i> +5.3%</small>
                                    <p class="mb-0 fs-12 text-muted">+{{ $newUsersToday }} new today</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-lg-6 col-sm-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="icon-box bg-pink bg-opacity-10 rounded-3 p-3 me-3">
                                    <i class="las la-wallet text-pink fs-2"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <p class="text-muted mb-1 fs-14">Total Payout</p>
                                    <h3 class="mb-0 fw-bold">₹{{ number_format($totalPayout) }}</h3>
                                    <small class="text-danger"><i class="las la-arrow-down"></i> -2.1%</small>
                                    <p class="mb-0 fs-12 text-muted">Rewards processed</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="row mt-4">
                <!-- Bonus Value Card -->
                <div class="col-xl-4 col-lg-6">
                    <div class="card border-0 shadow-sm gradient-card" style="background: linear-gradient(135deg, #284a8a 0%, #aece5b 100%);">
                        <div class="card-body text-white p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="mb-0 fw-bold text-white"><i class="las la-gift me-2"></i>Bonus Value</h5>
                                <i class="las la-ellipsis-v cursor-pointer"></i>
                            </div>
                            
                            <div class="mb-3">
                                <p class="mb-2 opacity-75 fs-14">TEAM VOLUME</p>
                                <div class="d-flex justify-content-between">
                                    <span class="fs-14 opacity-75">LEFT</span>
                                    <span class="fw-bold">{{ $leftTeamVolume }}</span>
                                    <span class="fs-14 opacity-75">RIGHT</span>
                                    <span class="fw-bold">{{ $rightTeamVolume }}</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <p class="mb-2 opacity-75 fs-14">CARRY VOLUME</p>
                                <div class="d-flex justify-content-between">
                                    <span class="fs-14 opacity-75">LEFT</span>
                                    <span class="fw-bold">{{ $leftCarryVolume }}</span>
                                    <span class="fs-14 opacity-75">RIGHT</span>
                                    <span class="fw-bold">{{ $rightCarryVolume }}</span>
                                </div>
                            </div>

                            <div class="border-top border-white border-opacity-25 pt-3 mt-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fs-14">Total BV</span>
                                    <span class="fw-bold fs-18">{{ $totalBV }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Holding Tank -->
                <div class="col-xl-4 col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h5 class="mb-0 fw-bold text-dark"><i class="las la-box me-2 text-warning"></i>Holding Tank</h5>
                                <span class="badge bg-warning bg-opacity-10 text-warning">{{ $pendingApprovals }} Pending</span>
                            </div>

                            <div class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Direct Sellers</span>
                                    <span class="fw-bold">{{ $directSellers }}</span>
                                </div>
                            </div>

                            <div class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Eligible</span>
                                    <span class="fw-bold text-success">{{ $eligibleCount }}</span>
                                </div>
                            </div>

                            <div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted fs-14">PLACEMENT RATE</span>
                                    <span class="text-muted fs-14">0%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Network Tree -->
                <div class="col-xl-4 col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-box bg-primary bg-opacity-10 rounded-3 p-2 me-3">
                                    <i class="las la-sitemap text-primary fs-4"></i>
                                </div>
                                <h5 class="mb-0 fw-bold text-dark">Network Tree</h5>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <div class="border rounded-3 p-3 text-center">
                                        <p class="text-muted mb-1 fs-12">LEFT NODES</p>
                                        <h4 class="mb-0 fw-bold text-primary">{{ $leftNodes }}</h4>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="border rounded-3 p-3 text-center">
                                        <p class="text-muted mb-1 fs-12">RIGHT NODES</p>
                                        <h4 class="mb-0 fw-bold">{{ $rightNodes }}</h4>
                                    </div>
                                </div>
                            </div>

                            <div class="bg-dark text-white rounded-3 p-3 text-center">
                                <p class="mb-0 fs-14">TOTAL TREE NODES</p>
                                <h3 class="mb-0 fw-bold">{{ $totalNodes }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Second Row -->
            <div class="row mt-4">
                <!-- User Distribution -->
                <div class="col-xl-4 col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-box bg-primary bg-opacity-10 rounded-3 p-2 me-3">
                                    <i class="las la-chart-pie text-primary fs-4"></i>
                                </div>
                                <h5 class="mb-0 fw-bold text-dark">User Distribution</h5>
                            </div>

                            <div class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <span class="bg-primary rounded-circle me-2" style="width: 8px; height: 8px;"></span>
                                        <span class="text-muted">Customers</span>
                                    </div>
                                    <span class="fw-bold">{{ $customerCount }}</span>
                                </div>
                            </div>

                            <div class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <span class="bg-success rounded-circle me-2" style="width: 8px; height: 8px;"></span>
                                        <span class="text-muted">Direct Sellers</span>
                                    </div>
                                    <span class="fw-bold">{{ $directSellers }}</span>
                                </div>
                            </div>

                            <div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex align-items-center">
                                        <span class="bg-danger rounded-circle me-2" style="width: 8px; height: 8px;"></span>
                                        <span class="text-muted">Inactive</span>
                                    </div>
                                    <span class="fw-bold">{{ $inactiveCount }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Inventory Status -->
                <div class="col-xl-4 col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex align-items-center mb-4">
                                <div class="icon-box bg-success bg-opacity-10 rounded-3 p-2 me-3">
                                    <i class="las la-boxes text-success fs-4"></i>
                                </div>
                                <h5 class="mb-0 fw-bold text-dark">Inventory Status</h5>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h3 class="mb-0 fw-bold">{{ $totalProducts }}</h3>
                                    <p class="mb-0 text-muted fs-14">Total Products</p>
                                </div>
                                <div class="text-end">
                                    <p class="mb-1 text-warning fs-12"><i class="las la-exclamation-triangle me-1"></i>{{ $lowStock }} Low Stock</p>
                                    <p class="mb-0 text-danger fs-12"><i class="las la-times-circle me-1"></i>{{ $outOfStock }} Out of Stock</p>
                                </div>
                            </div>

                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-success" role="progressbar" style="width: {{ $inventoryPercentage }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .gradient-card {
        background: linear-gradient(135deg, #aece5b 0%, #284a8a 100%);
    }
    
    .icon-box {
        min-width: 50px;
        text-align: center;
    }
    
    .bg-purple {
        background-color: #284a8a !important;
    }
    
    .bg-pink {
        background-color: #aece5b !important;
    }
    
    .text-purple {
        color: #284a8a !important;
    }
    
    .text-pink {
        color: #aece5b !important;
    }
    
    .fs-12 { font-size: 12px; }
    .fs-14 { font-size: 14px; }
    .fs-18 { font-size: 18px; }
    
    .cursor-pointer { cursor: pointer; }
    
    .shadow-sm {
        box-shadow: 0 2px 8px rgba(0,0,0,0.08) !important;
    }
    
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.12) !important;
    }
</style>
@endpush