<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    <title>VSR MLM | Welcome Letter</title>

    <style>
    @media print {
        body * {
            visibility: hidden;
        }
        
        #welcomeLetter, #welcomeLetter * {
            visibility: visible;
        }
        
        #welcomeLetter {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        
        .no-print {
            display: none !important;
        }
        
        .letter-paper {
            box-shadow: none !important;
            margin: 0 !important;
            padding: 20px !important;
        }
        
        @page {
            margin: 1cm;
        }
    }

    /* Letter Styling */
    .welcome-letter-container {
        background: #f5f5f5;
        padding: 20px;
    }

    .letter-paper {
        background: white;
        max-width: 900px;
        margin: 0 auto;
        padding: 40px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
        border-radius: 8px;
    }

    .letter-header {
        margin-bottom: 20px;
    }

    .company-logo {
        max-width: 220px;
        height: auto;
    }

    .company-name {
        color: #1e3a8a;
        font-weight: bold;
        margin: 0;
        font-size: 28px;
    }

    .company-tagline {
        color: #666;
        font-size: 14px;
        margin: 5px 0 0 0;
    }

    .letter-date {
        font-size: 14px;
        color: #333;
    }

    .letter-divider {
        height: 3px;
        background: linear-gradient(135deg, #284a8a 0%, #aece5b 100%);
        margin: 20px 0;
    }

    .letter-title {
        margin: 30px 0;
        text-align: center;
    }

    .letter-title h3 {
        color: #1e3a8a;
        font-size: 24px;
        font-weight: bold;
        margin: 0;
        text-decoration: underline;
    }

    .letter-subtitle {
        color: #666;
        font-size: 14px;
        margin: 10px 0 0 0;
    }

    .letter-greeting {
        margin: 20px 0;
    }

    .letter-content {
        line-height: 1.8;
        text-align: justify;
        margin-bottom: 15px;
        color: #333;
    }

    .section-title {
        background: linear-gradient(135deg, #284a8a 0%, #aece5b 100%);
        color: white;
        padding: 10px 15px;
        margin: 25px 0 15px 0;
        font-size: 16px;
        border-radius: 4px;
    }

    .details-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }

    .details-table tr {
        border-bottom: 1px solid #e0e0e0;
    }

    .details-table td {
        padding: 10px;
        vertical-align: top;
    }

    .label-cell {
        width: 20%;
        color: #555;
    }

    .value-cell {
        width: 30%;
        color: #333;
        font-weight: 500;
    }

    .letter-closing {
        margin: 30px 0;
    }

    .signature-section {
        margin-top: 50px;
    }
    .signature-section .row{
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .signature-box {
        margin-top: 40px;
    }

    .signature-line {
        width: 200px;
        height: 1px;
        background: #333;
        margin-bottom: 10px;
    }

    .signature-role {
        color: #666;
        font-size: 12px;
        margin: 5px 0 0 0;
    }

    .letter-footer {
        margin-top: 40px;
        padding-top: 20px;
        border-top: 2px solid #1e3a8a;
    }

    .footer-content {
        text-align: center;
        color: #666;
        font-size: 12px;
    }

    .footer-content p {
        margin: 5px 0;
    }

    .footer-contact {
        color: #333;
    }

    .footer-note {
        font-style: italic;
        color: #999;
        font-size: 11px;
        margin-top: 10px;
    }
    .mail-header-section{
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
</style>
</head>
<body>
    <div class="welcome-letter-container" id="welcomeLetter">
        <div class="letter-paper">
            
            <!-- Header with Logo -->
            <div class="letter-header">
                <div class="row align-items-center mail-header-section" >
                    <div class="col-3">
                        <img src="{{ asset('images/logo/hozlogo.png') }}" alt="Company Logo" class="company-logo">
                    </div>
                    <div class="col-6 text-center">
                        <h2 class="company-name">VSR MLM NETWORK</h2>
                        <p class="company-tagline">Empowering Your Financial Future</p>
                    </div>
                    <div class="col-3 text-end">
                        <div class="letter-date">
                            <strong>Date:</strong><br>
                            {{ \Carbon\Carbon::parse($user->created_at)->format('d-m-Y') }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <div class="letter-divider"></div>

            <!-- Letter Title -->
            <div class="letter-title text-center">
                <h3>WELCOME LETTER</h3>
                <p class="letter-subtitle">Congratulations on joining our network!</p>
            </div>

            <!-- Greeting -->
            <div class="letter-greeting">
                <p>Dear <strong>{{ $user->first_name }} {{ $user->last_name }}</strong>,</p>
                <p class="letter-content">
                    We are delighted to welcome you to the <strong>VSR MLM NETWORK</strong> family! 
                    Your decision to join our network marks the beginning of an exciting journey towards 
                    financial freedom and personal growth.
                </p>
                <p class="letter-content">
                    We are committed to providing you with the best support, training, and opportunities 
                    to help you achieve your goals and build a successful business.
                </p>
            </div>

            <!-- User Details Table -->
            <div class="user-details-section">
                <h4 class="section-title">YOUR ACCOUNT DETAILS</h4>
                <table class="details-table">
                    <tr>
                        <td class="label-cell"><strong>User Name:</strong></td>
                        <td class="value-cell">{{ $user->user_name }}</td>
                        <td class="label-cell"><strong>Track ID:</strong></td>
                        <td class="value-cell">{{ $user->track_id }}</td>
                    </tr>
                    <tr>
                        <td class="label-cell"><strong>Full Name:</strong></td>
                        <td class="value-cell">{{ $user->first_name }} {{ $user->last_name }}</td>
                        <td class="label-cell"><strong>Father's Name:</strong></td>
                        <td class="value-cell">{{ $user->father_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="label-cell"><strong>Email:</strong></td>
                        <td class="value-cell">{{ $user->email }}</td>
                        <td class="label-cell"><strong>Phone:</strong></td>
                        <td class="value-cell">{{ $user->phone }}</td>
                    </tr>
                    <tr>
                        <td class="label-cell"><strong>Address:</strong></td>
                        <td class="value-cell" colspan="3">{{ $user->address ?? 'N/A' }}, {{ $user->district ?? '' }} - {{ $user->pincode ?? '' }}, {{ $user->state ?? '' }}</td>
                    </tr>
                    <tr>
                        <td class="label-cell"><strong>Date of Joining:</strong></td>
                        <td class="value-cell">{{ \Carbon\Carbon::parse($user->created_at)->format('d-m-Y') }}</td>
                        <td class="label-cell"><strong>Membership Type:</strong></td>
                        <td class="value-cell">{{ ucfirst(str_replace('_', ' ', $user->membership_type ?? 'Customer')) }}</td>
                    </tr>
                </table>
            </div>

            <!-- Sponsor Details -->
            <div class="sponsor-details-section">
                <h4 class="section-title">SPONSOR / UPLINE DETAILS</h4>
                <table class="details-table">
                    <tr>
                        <td class="label-cell"><strong>Sponsor Name:</strong></td>
                        <td class="value-cell">{{ $user->sponsor->first_name ?? 'N/A' }} {{ $user->sponsor->last_name ?? '' }}</td>
                        <td class="label-cell"><strong>Sponsor ID:</strong></td>
                        <td class="value-cell">{{ $user->sponsor->user_name ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="label-cell"><strong>Sponsor Phone:</strong></td>
                        <td class="value-cell">{{ $user->sponsor->phone ?? 'N/A' }}</td>
                        <td class="label-cell"><strong>Sponsor Email:</strong></td>
                        <td class="value-cell">{{ $user->sponsor->email ?? 'N/A' }}</td>
                    </tr>
                </table>
            </div>

            <!-- Closing Message -->
            <div class="letter-closing">
                <p class="letter-content">
                    Once again, welcome aboard! We look forward to a long and prosperous association with you. 
                    Should you have any questions or need assistance, please don't hesitate to reach out to 
                    your sponsor or our support team.
                </p>
                <p class="letter-content">
                    <strong>Wishing you great success!</strong>
                </p>
            </div>

            <!-- Signature Section -->
            <div class="signature-section">
                <div class="row">
                    <div class="col-6">
                        <div class="signature-box">
                            <div class="signature-line"></div>
                            <p><strong>For {{ $user->first_name }} {{ $user->last_name }}</strong></p>
                            <p class="signature-role">Member</p>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="signature-box text-end">
                            <div class="signature-line ms-auto"></div>
                            <p><strong>For VSR MLM NETWORK</strong></p>
                            <p class="signature-role">Authorized Signatory</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="letter-footer">
                <div class="footer-content">
                    <p><strong>VSR MLM NETWORK</strong></p>
                    <p class="footer-contact">
                        <i class="las la-phone"></i> +91-XXXXXXXXXX | 
                        <i class="las la-envelope"></i> support@vsrmlm.com | 
                        <i class="las la-globe"></i> www.vsrmlm.com
                    </p>
                    <p class="footer-note">
                        This is a computer-generated document and does not require a signature.
                    </p>
                </div>
            </div>

        </div>
    </div>    
</body>
</html>