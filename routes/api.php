<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\HomeApiController;
use App\Http\Controllers\Api\AboutApiController;
use App\Http\Controllers\Api\CommitmentApiController;
use App\Http\Controllers\Api\TeamApiController;
use App\Http\Controllers\Api\PrivacyApiController;
use App\Http\Controllers\Api\TermsApiController;
use App\Http\Controllers\Api\NdisApiController;
use App\Http\Controllers\Api\AgedcareApiController;
use App\Http\Controllers\Api\BlogsApiController;
use App\Http\Controllers\Api\NiisqApiController;
use App\Http\Controllers\Api\DvaApiController;
use App\Http\Controllers\Api\ClientResourcesApiController;
use App\Http\Controllers\Api\StaffResourcesApiController;
use App\Http\Controllers\Api\FaqApiController;
use App\Http\Controllers\Api\CareerApiController;
use App\Http\Controllers\Api\HomeServiceApiController;
use App\Http\Controllers\Api\CommunityParticipationApiController;
use App\Http\Controllers\Api\supportIndependentApiController;
use App\Http\Controllers\Api\careCoordinationApiController;
use App\Http\Controllers\Api\communityNursingApiController;
use App\Http\Controllers\Api\alliedHealthApiController;
use App\Http\Controllers\Api\planManagementApiController;
use App\Http\Controllers\Api\supportCoordinationApiController;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\AccessibilityApiController;
use App\Http\Controllers\Api\GrievanceApiController;
use App\Http\Controllers\Api\ShippingPolicyApiController;
use App\Http\Controllers\Api\CancellationApiController;
use App\Http\Controllers\Api\DisclaimerApiController;
use App\Http\Controllers\Api\SystemApiController;
use App\Http\Controllers\Api\MLMApiController;
use App\Http\Controllers\Api\AdminBankApiController;
use App\Http\Controllers\Api\FundSummaryApiController;
use App\Http\Controllers\Api\FundRequestApiController;

use App\Http\Controllers\Api\FundTransferApiController;


Route::get('/ping', function () {
    return response()->json(['status' => 'API working']);
});

Route::get('/home', [HomeApiController::class, 'index']);
Route::get('/about-us', [AboutApiController::class, 'index']);
Route::get('/our-commitment', [CommitmentApiController::class, 'index']);
Route::get('/our-team', [TeamApiController::class, 'index']);
Route::get('/privacy-policy', [PrivacyApiController::class, 'index']);
Route::get('/terms-conditions', [TermsApiController::class, 'index']);  
Route::get('/accessibility', [AccessibilityApiController::class, 'index']);  
Route::get('/shipping-policy', [ShippingPolicyApiController::class, 'index']);  
Route::get('/disclaimer', [DisclaimerApiController::class, 'index']);  
Route::get('/cancel-policy', [CancellationApiController::class, 'index']);  
Route::get('/grievance-redressal', [GrievanceApiController::class, 'index']);  
Route::get('/ndis', [NdisApiController::class, 'index']);  
Route::get('/aged-care', [AgedcareApiController::class, 'index']);  
Route::get('/blogs', [BlogsApiController::class, 'index']);  
Route::get('/products', [ProductApiController::class, 'index']);  
Route::get('/niisq', [NiisqApiController::class, 'index']);  
Route::get('/dva', [DvaApiController::class, 'index']);  
Route::get('/client-resource', [ClientResourcesApiController::class, 'index']);  
Route::get('/staff-resource', [StaffResourcesApiController::class, 'index']);  
Route::get('/faqs', [FaqApiController::class, 'index']);  
Route::get('/jobs', [CareerApiController::class, 'index']);  
Route::get('/jobs/{slug}', [CareerApiController::class, 'show']);
Route::get('/home-service', [HomeServiceApiController::class, 'index']);
Route::get('/community-participation-service', [CommunityParticipationApiController::class, 'index']);
Route::get('/support-independent-service', [supportIndependentApiController::class, 'index']);
Route::get('/care-coordination-service', [careCoordinationApiController::class, 'index']);
Route::get('/community-nursing-service', [communityNursingApiController::class, 'index']);
Route::get('/allied-health-service', [alliedHealthApiController::class, 'index']);
Route::get('/plan-management-service', [planManagementApiController::class, 'index']);
Route::get('/support-coordination-service', [supportCoordinationApiController::class, 'index']);
Route::get('/system-setting', [SystemApiController::class, 'index']);


    
    // 1. Referrals
    Route::get('/referrals', [MLMApiController::class, 'getReferrals']);
    Route::get('/referrals/profile/{userId}', [MLMApiController::class, 'getReferralProfile']);
    
    // 2. Holding Tank
    Route::get('/holding-tank', [MLMApiController::class, 'getHoldingTank']);
    Route::post('/holding-tank/place', [MLMApiController::class, 'placeUser']);
    
    // 3. Referral Downline (Table)
    Route::get('/downline', [MLMApiController::class, 'getReferralDownline']);
    
    // 4. Team Genealogy & Binary Tree
    Route::get('/team/genealogy', [MLMApiController::class, 'getTeamGenealogy']);
    Route::get('/team/downline', [MLMApiController::class, 'getTeamDownline']);
    Route::get('/team/user-profile/{userId}', [MLMApiController::class, 'getUserProfile']);
    Route::get('/team/user-downline/{userId}', [MLMApiController::class, 'getUserDownline']);


    Route::get('/admin-bank-details', [AdminBankApiController::class, 'index']);
Route::get('/admin-bank-details/{id}', [AdminBankApiController::class, 'show']);
Route::get('/fund-summary', [FundSummaryApiController::class, 'index']);
// Fund Request APIs
Route::get('/fund-request/bank-details', [FundRequestApiController::class, 'getBankDetails']);
Route::post('/fund-request/submit', [FundRequestApiController::class, 'submit']);
Route::get('/fund-requests', [FundRequestApiController::class, 'index']); // Admin
Route::put('/fund-requests/{id}/status', [FundRequestApiController::class, 'updateStatus']); // Admin

// Fund Transfer APIs
Route::post('/fund-transfer/transfer', [FundTransferApiController::class, 'transfer']);
Route::get('/fund-transfer/sent', [FundTransferApiController::class, 'getSentTransfers']);
Route::get('/fund-transfer/received', [FundTransferApiController::class, 'getReceivedTransfers']);
Route::get('/fund-transfer/wallet-balance', [FundTransferApiController::class, 'getWalletBalance']);