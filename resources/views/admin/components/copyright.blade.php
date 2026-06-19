<div class="footer">
    <div class="copyright">
     @php
            $settings = App\Models\Setting::first();
        @endphp
        <p>&copy; <?= date('Y') ?> <a href=""> {{ $settings->company_name ? $settings->	company_name : 'Dashboard' }}</a> | Developed by <a href="https://vibrantick.in/" target="_blank">Vibrantick Infotech Solutions</a> 
        </p>
    </div>
</div>