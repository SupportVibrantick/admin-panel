<?php

use App\Http\Controllers\Api\AboutApiController;
use App\Http\Controllers\Api\AccessibilityApiController;
use App\Http\Controllers\Api\AdminBankApiController;
use App\Http\Controllers\Api\AgedcareApiController;
use App\Http\Controllers\Api\alliedHealthApiController;
use App\Http\Controllers\Api\BlogsApiController;
use App\Http\Controllers\Api\CancellationApiController;
use App\Http\Controllers\Api\careCoordinationApiController;
use App\Http\Controllers\Api\CareerApiController;
use App\Http\Controllers\Api\ClientResourcesApiController;
use App\Http\Controllers\Api\CommitmentApiController;
use App\Http\Controllers\Api\communityNursingApiController;
use App\Http\Controllers\Api\CommunityParticipationApiController;
use App\Http\Controllers\Api\DisclaimerApiController;
use App\Http\Controllers\Api\DvaApiController;
use App\Http\Controllers\Api\FaqApiController;
use App\Http\Controllers\Api\FundRequestApiController;
use App\Http\Controllers\Api\FundSummaryApiController;
use App\Http\Controllers\Api\FundTransferApiController;
use App\Http\Controllers\Api\GrievanceApiController;
use App\Http\Controllers\Api\GrievanceController;
use App\Http\Controllers\Api\HomeApiController;
use App\Http\Controllers\Api\HomeServiceApiController;
use App\Http\Controllers\Api\KycController;
use App\Http\Controllers\Api\MLMApiController;
use App\Http\Controllers\Api\NdisApiController;
use App\Http\Controllers\Api\NiisqApiController;
use App\Http\Controllers\Api\planManagementApiController;
use App\Http\Controllers\Api\PrivacyApiController;
use App\Http\Controllers\Api\ProductApiController;
use App\Http\Controllers\Api\ShippingPolicyApiController;
use App\Http\Controllers\Api\StaffResourcesApiController;
use App\Http\Controllers\Api\supportCoordinationApiController;
use App\Http\Controllers\Api\supportIndependentApiController;
use App\Http\Controllers\Api\SystemApiController;
use Illuminate\Support\Facades\Route;


// Route::get('/wallets', [WalletApiController::class, 'getWallets']);
// Route::get('/wallet/transactions', [WalletApiController::class, 'getTransactions']);
// Route::post('/wallet/transactions', [WalletApiController::class, 'createTransaction']);
// Route::get('/direct-income', [DirectIncomeApiController::class, 'index']);
// Route::get('/account-summary', [AccountSummaryApiController::class, 'index']);
// Route::get('/matching-income', [MatchingIncomeApiController::class, 'index']);
// ============================================
// Unified Wallet & Income APIs
// ============================================

// Wallets
Route::get('/wallets', [WalletIncomeApiController::class, 'getWallets']);
Route::get('/wallet/transactions', [WalletIncomeApiController::class, 'getWalletTransactions']);

// Income Types
Route::get('/direct-income', [WalletIncomeApiController::class, 'getDirectIncome']);
Route::get('/matching-income', [WalletIncomeApiController::class, 'getMatchingIncome']);
Route::get('/generation-income', [WalletIncomeApiController::class, 'getGenerationIncome']);

// Account Summary
Route::get('/account-summary', [WalletIncomeApiController::class, 'getAccountSummary']);

// Cash Bonus & Awards
Route::get('/cash-bonus-request', [WalletIncomeApiController::class, 'getCashBonusRequests']);
Route::get('/claim-cash-request', [WalletIncomeApiController::class, 'getClaimCashRequests']);
Route::get('/cash-bonus-history', [WalletIncomeApiController::class, 'getCashBonusHistory']);
Route::get('/awards-rewards', [WalletIncomeApiController::class, 'getAwardsRewards']);

// Downline Rank, Weekly Payout, Retreat Tours
Route::get('/downline-rank', [WalletIncomeApiController::class, 'getDownlineRank']);
Route::get('/weekly-payout', [WalletIncomeApiController::class, 'getWeeklyPayout']);
Route::get('/retreat-tours', [WalletIncomeApiController::class, 'getRetreatTours']);

// Order & Delivery APIs
Route::get('/order-history', [WalletIncomeApiController::class, 'getOrderHistory']);
Route::get('/by-hand-delivery', [WalletIncomeApiController::class, 'getByHandDelivery']);
Route::get('/courier-delivery', [WalletIncomeApiController::class, 'getCourierDelivery']);
Route::get('/by-hand-award', [WalletIncomeApiController::class, 'getByHandAward']);
Route::get('/by-courier-award', [WalletIncomeApiController::class, 'getByCourierAward']);
Route::get('/other-products', [WalletIncomeApiController::class, 'getOtherProducts']);
