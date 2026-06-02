<div class="nav-header">
    <a href="{{ route('dashboard') }}" class="brand-logo d-flex align-items-center">
        <!-- Logo Image -->
        {{-- <img src="{{ asset('admin/images/favicon.png') }}" alt="Logo" class="logo-abbr"> --}}

        @php
            $settings = App\Models\Setting::first();
        @endphp
        <!-- Optional: Brand Title next to logo -->
        <span class="brand-title ms-2">
            <img src="{{ $settings->backend_logo ? asset('storage/' . $settings->backend_logo) : asset('images/logo/hozlogo.png') }}"
                alt="img">
        </span>
    </a>

    <div class="nav-control">
        <div class="hamburger">
            <span class="line"></span>
            <span class="line"></span>
            <span class="line"></span>
        </div>
    </div>
</div>


<div class="header">
    <div class="header-content">
        <nav class="navbar navbar-expand">
            <div class="collapse navbar-collapse justify-content-between">
                <div class="header-left">
                    <div class="dashboard_bar">Dashboard</div>
                </div>

                <ul class="navbar-nav header-right">

                    <li class="nav-item dropdown notification_dropdown">
                        <a class="nav-link bell dz-theme-mode" href="javascript:void(0);">
                            <i id="icon-light" class="fas fa-sun"></i>
                            <i id="icon-dark" class="fas fa-moon"></i>
                        </a>
                    </li>
                    <li class="nav-item dropdown header-profile">
                        <a class="nav-link" href="javascript:;" role="button" data-bs-toggle="dropdown">
                            @php
                                $user = auth()->user();
                            @endphp

                            <img src="{{ $user->profile_photo_path ? Storage::url($user->profile_photo_path) : url('images/avatar/1.png') }}"
                                onerror="this.onerror=null; this.src='{{ asset('assets/images/avatar/1.png') }}';"
                                class="rounded-circle me-2" width="40" height="40" style="object-fit: cover;"
                                alt="{{ $user->name }}">
                            <div class="header-info">
                                <span>Hello, <strong class="">{{ auth()->user()->name }}</strong></span>
                            </div>

                        </a>
                        <div class="dropdown-menu dropdown-menu-end">
                            <a href="{{ route('profile.update') }}" class="dropdown-item ai-icon">
                                <svg id="icon-user1" xmlns="http://www.w3.org/2000/svg" class="text-primary"
                                    width="18" height="18" viewbox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                    <circle cx="12" cy="7" r="4"></circle>
                                </svg>
                                <span class="ms-2">Profile </span>
                            </a>
                            {{-- <a href="email-inbox.html" class="dropdown-item ai-icon">
                                <svg id="icon-inbox" xmlns="http://www.w3.org/2000/svg" class="text-success" width="18"
                                    height="18" viewbox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                    </path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                                <span class="ms-2">Inbox </span>
                            </a> --}}
                            <a href="#" class="dropdown-item ai-icon"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-danger"
                                    width="18" height="18" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                    <polyline points="16 17 21 12 16 7"></polyline>
                                    <line x1="21" y1="12" x2="9" y2="12"></line>
                                </svg>
                                <span class="ms-2">Logout </span>
                            </a>

                            <!-- Hidden logout form -->
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>

                        </div>
                    </li>
                </ul>
            </div>
        </nav>
    </div>
</div>
