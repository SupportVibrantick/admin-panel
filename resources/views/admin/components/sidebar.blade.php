<div class="deznav">
    <div class="deznav-scroll">
        <ul class="metismenu" id="menu">
            <li>
                @can('view-dashboard')
                    <a class="ai-icon" href="{{ route('dashboard') }}" aria-expanded="false">
                        <i class="flaticon-381-networking"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                @endcan
            </li>





            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-381-user-7"></i>
                    <span class="nav-text">Manage Network</span>
                </a>
                <ul aria-expanded="false">


                    <li><a class="" href="{{ route('mlm-users.index') }}" aria-expanded="false">Manage
                            Customers</a></li>
                    <li><a class="" href="{{ route('holding-tank') }}" aria-expanded="false">Holding Tank</a></li>
                    <li><a class="" href="{{ route('team-genealogy.index') }}" aria-expanded="false">Team
                            Genealogy</a></li>
                    <li><a class="" href="{{ route('referral-genealogy.index') }}" aria-expanded="false">Referral
                            Genealogy</a></li>
                    <li><a class="" href="{{ route('referral-downline.index') }}" aria-expanded="false">Referral
                            Downline</a></li>
                    <li><a class="" href="{{ route('team-downline.index') }}" aria-expanded="false">Team
                            Downline</a></li>
                    {{-- <li><a class="" href="{{ route('team-genealogy.index') }}" aria-expanded="false">Placement
                            Settings</a></li> --}}

                    <li><a class="" href="{{ route('recycle-bin') }}" aria-expanded="false">Recycle Bin</a></li>

                </ul>
            </li>

            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-381-home"></i>
                    <span class="nav-text">Financials</span>
                </a>
                <ul aria-expanded="false">

                    <li>
                        <a href="{{ route('wallets.financial-overview') }}">
                           Financial Overview
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('wallets.commission-wallet') }}">
                          
                            <span>Commission Wallet</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('wallets.purchase-wallet') }}">
                          
                            <span>Purchase Wallet</span>
                        </a>
                    </li>
                   
                    <li>
                        <a href="{{ route('wallets.pending-earnings') }}">
                          
                            <span>Pending Earnings</span>
                        </a>
                    </li>
                   
                    <li>
                        <a href="{{ route('wallets.bonus-history') }}">
                          
                            <span>Bonus History</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('wallets.cc-logs') }}">
                          
                            <span>CC Logs</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('wallets.pair-matching-logs') }}">
                          
                            <span>Pair Logs</span>
                        </a>
                    </li>
                </ul>
            </li>


            <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                    <i class="flaticon-381-home"></i>
                    <span class="nav-text">Wallets & Payouts</span>
                </a>
                <ul aria-expanded="false">

                    <li>
                        <a href="{{ route('mlm-users.payout') }}">
                          
                            <span>Payout Dashboard</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('wallets.index') }}">
                          
                            <span>Master Wallet</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('mlm-users.payout-request') }}">                          
                            <span>Payout Requests</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('mlm-users.payout-summary') }}">                          
                            <span>Payout Summary</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('mlm-users.payout-transfer-history') }}">                          
                            <span>Payout Transfer History</span>
                        </a>
                    </li> 
                </ul>
            </li>



            {{-- CMS --}}
            @canany(['manage-ads', 'manage-blog-categories', 'manage-blogs', 'manage-slider', 'manage-about',
                'manage-why-choose-us', 'manage-accreditations', 'manage-gallery', 'manage-testimonials', 'manage-know-us',
                'manage-counter', 'manage-what-makes-different', 'manage-partners', 'manage-partner-images',
                'manage-why-partners', 'manage-corporate-benefits', 'manage-corporate-services', 'manage-job-career',
                'manage-privacy-policy', 'manage-terms-conditions'])
                <li><a class="has-arrow ai-icon" href="javascript:void()" aria-expanded="false">
                        <i class="flaticon-381-layer-1"></i>
                        <span class="nav-text">CMS</span>
                    </a>
                    <ul aria-expanded="false">


                        @canany(['manage-blog-categories', 'manage-blogs'])
                            <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">Blogs</a>
                                <ul class="" aria-expanded="false">
                                    @can('manage-blog-categories')
                                        <li><a href="{{ route('blog-categories.index') }}">Blog category</a></li>
                                    @endcan
                                    @can('manage-blogs')
                                        <li><a href="{{ route('blogs.index') }}">Blog Details</a></li>
                                    @endcan
                                </ul>
                            </li>
                        @endcanany
                        @canany(['manage-product-categories', 'manage-products'])
                            <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">Products</a>
                                <ul class="" aria-expanded="false">
                                    @can('manage-product-categories')
                                        <li><a href="{{ route('product-categories.index') }}">Product category</a></li>
                                    @endcan
                                    @can('manage-products')
                                        <li><a href="{{ route('products.index') }}">Product Details</a></li>
                                    @endcan
                                </ul>
                            </li>
                        @endcanany

                        @canany(['manage-slider', 'manage-about', 'manage-why-choose-us', 'manage-accreditations',
                            'manage-gallery', 'manage-testimonials'])
                            <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">Home Page</a>
                                <ul aria-expanded="false">
                                    <li><a href="{{ route('sliderimage.index') }}">Slider</a></li>
                                    <li><a href="{{ route('about-section.index') }}">About Us</a></li>
                                    {{-- <li><a href="{{ route('act-about-section.index') }}"> About Act Section</a></li> --}}
                                    {{-- <li><a href="{{ route('how-works.index') }}">How We Works</a></li> --}}
                                    {{-- <li><a href="{{ route('whychoose-section.index') }}">Why Choose Us</a></li> --}}
                                    {{-- <li><a href="{{ route('about-two-section.index') }}">About Two </a></li> --}}
                                    {{-- <li><a href="{{ route('case-study.index') }}">Case Study</a></li> --}}
                                    <li><a href="{{ route('services.index') }}">Service Section</a></li>
                                    <li><a href="{{ route('counters-section.index') }}">Counter Section</a></li>


                                </ul>
                            </li>
                        @endcanany


                        @can('manage-partner-images')
                            <li><a href="{{ route('partners.index') }}" aria-expanded="false">Partners</a></li>
                        @endcan
                        @can('manage-testimonials')
                            <li><a href="{{ route('testimonials.index') }}" aria-expanded="false">Testimonials</a></li>
                        @endcan
                        @can('manage-gallery')
                            <li><a href="{{ route('faqs.index') }}" aria-expanded="false">Faq</a></li>
                        @endcan

                        {{-- <li><a class="" href="{{ route('client-resources.index') }}" aria-expanded="false">Client Resources</a></li>
                            <li><a class="" href="{{ route('staff-resources.index') }}" aria-expanded="false">Staff Resources</a></li>
                        --}}

                        @canany(['manage-privacy-policy', 'manage-terms-conditions', 'manage-accessibility',
                            'manage-cancellation-refund-process', 'manage-disclaimer', 'manage-shipping-policy',
                            'manage-grievance-redressal'])
                            <li><a class="has-arrow" href="javascript:void()" aria-expanded="false">Legals</a>
                                <ul class="" aria-expanded="false">
                                    @can('manage-privacy-policy')
                                        <li><a class="" href="{{ route('admin-privacy-policy.index') }}"
                                                aria-expanded="false">Privacy
                                                Policy</a></li>
                                    @endcan

                                    @can('manage-terms-conditions')
                                        <li><a class="" href="{{ route('admin-terms-condition.index') }}"
                                                aria-expanded="false">Terms
                                                Conditions</a></li>
                                    @endcan
                                    @can('manage-accessibility')
                                        <li><a class="" href="{{ route('admin-accessibility.index') }}"
                                                aria-expanded="false">Accessibility</a></li>
                                    @endcan
                                    @can('manage-cancellation-refund-process')
                                        <li><a class="" href="{{ route('admin-cancellation-policy.index') }}"
                                                aria-expanded="false">Cancellation & Refund Process</a></li>
                                    @endcan
                                    @can('manage-disclaimer')
                                        <li><a class="" href="{{ route('admin-disclaimer.index') }}"
                                                aria-expanded="false">Disclaimer</a></li>
                                    @endcan
                                    @can('manage-shipping-policy')
                                        <li><a class="" href="{{ route('admin-shipping-policy.index') }}"
                                                aria-expanded="false">Shipping Policy</a></li>
                                    @endcan
                                    @can('manage-grievance-redressal')
                                        <li><a class="" href="{{ route('admin-grievance-redressal.index') }}"
                                                aria-expanded="false">Grievance Redressal</a></li>
                                    @endcan
                                </ul>
                            </li>
                        @endcanany
                    </ul>
                </li>
            @endcanany
            {{-- for who we serve --}}




            @can('manage-profile')
                <li><a href="{{ route('profile') }}" class="ai-icon" aria-expanded="false">
                        <i class="fa fa-user-circle"></i>
                        <span class="nav-text">My Profile</span>
                    </a>
                </li>
            @endcan

            @canany(['manage-general-settings', 'manage-system-settings', 'manage-seo-settings',
                'manage-website-settings'])
                <li><a href="javascript:void(0)" class="has-arrow ai-icon" aria-expanded="false">
                        <i class="flaticon-381-settings-2"></i>
                        <span class="nav-text">Settings</span>
                    </a>
                    <ul aria-expanded="false">
                        @can('manage-general-settings')
                            <li><a href="{{ route('cc-settings.index') }}">CC Points Settings</a></li>
                        @endcan
                        @can('manage-general-settings')
                            <li><a href="{{ route('general-setting') }}">General Settings</a></li>
                        @endcan
                        @can('manage-system-settings')
                            <li><a href="{{ route('system-setting') }}">System Setting</a></li>
                        @endcan
                        @can('manage-seo-settings')
                            <li><a href="{{ route('seo-setting.index') }}">SEO Setting</a></li>
                        @endcan
                        @can('manage-website-settings')
                            <li><a href="{{ route('website-setting') }}">Website Settings</a></li>
                        @endcan
                        @can('manage-website-settings')
                            <li><a href="{{ route('bank-account-settings.index') }}">Bank Account Settings</a></li>
                        @endcan
                    </ul>
                </li>
            @endcanany

            @canany(['manage-doctors'])
                <li><a href="javascript:void(0);" class="ai-icon has-arrow" aria-expanded="false">
                        <i class="flaticon-381-id-card-4"></i>
                        <span class="nav-text">Our Team</span>
                    </a>
                    <ul aria-expanded="false">
                        @can('manage-doctors')
                            <li><a href="{{ route('admin-staff.index') }}">Team</a></li>
                        @endcan
                    </ul>
                </li>
            @endcanany

            @canany(['manage-users', 'manage-roles', 'manage-permissions'])
                <li><a href="javascript:void(0);" class="ai-icon has-arrow" aria-expanded="false">
                        <i class="flaticon-381-id-card"></i>

                        <span class="nav-text">User Directory</span>
                    </a>
                    <ul aria-expanded="false">
                        @can('manage-users')
                            <li><a href="{{ route('admin-register.index') }}">Add User</a></li>
                        @endcan
                        @can('manage-roles')
                            <li><a href="{{ route('roles.index') }}">Manage Roles</a></li>
                        @endcan
                        @can('manage-permissions')
                            <li><a href="{{ route('permissions.index') }}">Manage Permissions</a></li>
                        @endcan
                    </ul>
                </li>
            @endcanany

            <li>
                <div class="custom-btn-logout">
                    <a href="#" class="dropdown-item ai-icon"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <svg id="icon-logout" xmlns="http://www.w3.org/2000/svg" class="text-white" width="18"
                            height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        <span class="ms-2">Logout </span>
                    </a>
                </div>
            </li>
            <div class=" mt-2">
                <p class="p-2 text-center">
                    Admin Panel Version <span class="text-theme">1.0.0</span> <br>
                    Last Updated: <span class="text-theme">Dec, 2026 </span>
                </p>
            </div>
        </ul>
    </div>
</div>
