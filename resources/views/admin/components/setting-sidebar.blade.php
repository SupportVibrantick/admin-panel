<div class="col-lg-3 pe-0">
    <div class="container mt-0">
        <div class="card">
            <div class="card-header primary-bg">
                <h4 class="card-title text-white "> Settings</h4>
            </div>
            <div class="card-body">
                <ul class="side-nav-setting mm-collapse" id="settingMenu">
                    <li><a href="{{ route('cc-settings.index') }}"><i class="flaticon-381-controls-3"></i> CC Points
                            Setting</a></li>
                    <li><a href="{{ route('general-setting') }}"><i class="flaticon-381-settings-2"></i> General
                            Setting</a></li>
                    <li><a href="{{ route('system-setting') }}"><i class="fa-brands fa-centos"></i> System
                            Setting</a></li>
                    <li><a href="{{ route('seo-setting.index') }}"><i class="fa-brands fa-centos"></i> SEO
                            Setting</a></li>
                    {{-- <li><a href="{{ route('theme-setting') }}"><i class="flaticon-381-controls-3"></i> Theme
                            Settings</a></li> --}}
                    <li><a href="{{ route('website-setting') }}"> <i class="flaticon-381-internet"></i> Website
                            Setting</a></li>
                    <li><a href="{{ route('bank-account-settings.index') }}"> <i class="fa-solid fa-building-columns"></i> Bank Account Settings</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

