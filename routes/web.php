<?php

use App\Http\Controllers\AboutCareController;
use App\Http\Controllers\AboutFaqController;
use App\Http\Controllers\AboutMakeController;
use App\Http\Controllers\AboutSectionTwoController;
use App\Http\Controllers\AboutTwoController;
use App\Http\Controllers\AccessibilityController;
use App\Http\Controllers\AccommodationGalleryController;
use App\Http\Controllers\AccomodationFaqController;
use App\Http\Controllers\AccreditationController;
use App\Http\Controllers\ActAboutController;
use App\Http\Controllers\AgedAboutController;
use App\Http\Controllers\AgedBenefitController;
use App\Http\Controllers\AgedServiceController;
use App\Http\Controllers\AliiedFaqController;
use App\Http\Controllers\AlliedHealthAboutController;
use App\Http\Controllers\AlliedHealthJourneyController;
use App\Http\Controllers\AlliedHealthServiceController;
use App\Http\Controllers\AlliedHealthSupportController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\BankAccountSettingController;
use App\Http\Controllers\BlogCategoryController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CancellationController;
use App\Http\Controllers\CaptchaSettingController;
use App\Http\Controllers\CareCoordinationAboutController;
use App\Http\Controllers\CareFaqController;
use App\Http\Controllers\CaseStudyController;
use App\Http\Controllers\ClientResourceController;
use App\Http\Controllers\CommitmentFourController;
use App\Http\Controllers\CommitmentOneController;
use App\Http\Controllers\CommitmentThreeController;
use App\Http\Controllers\CommitmentTwoController;
use App\Http\Controllers\CommunityAboutController;
use App\Http\Controllers\CommunityActivityController;
use App\Http\Controllers\CommunityApproachSectionController;
use App\Http\Controllers\CommunityBenefitController;
use App\Http\Controllers\CommunityEligibilityFaqController;
use App\Http\Controllers\CommunityNursingAboutController;
use App\Http\Controllers\CommunityNursingActController;
use App\Http\Controllers\CommunityNursingFaqController;
use App\Http\Controllers\CommunityNursingServiceController;
use App\Http\Controllers\CommunityPlanningController;
use App\Http\Controllers\CommunityServiceController;
use App\Http\Controllers\CommunitySupportController;
// fetch controller class to use in routes
use App\Http\Controllers\CommunityWorkController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\CorporateBenefitController;
use App\Http\Controllers\CorporateServiceController;
use App\Http\Controllers\CounterController;
use App\Http\Controllers\CountersSectionController;
use App\Http\Controllers\DiscalimerController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\DvaSectionController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\FaqsController;
use App\Http\Controllers\GalleryController;
use App\Http\Controllers\GrivanceRedressalController;
use App\Http\Controllers\HealthRiskController;
use App\Http\Controllers\HomeCare2ServiceController;
use App\Http\Controllers\HomeCareAboutController;
use App\Http\Controllers\HomeCareCommunityController;
use App\Http\Controllers\HomeCareDifferenceController;
use App\Http\Controllers\HomeCareServiceController;
use App\Http\Controllers\HowWorkController;
use App\Http\Controllers\IndependentAboutController;
use App\Http\Controllers\IndependentAccommodationController;
use App\Http\Controllers\JobCareerApplicationController;
use App\Http\Controllers\JobCareerController;
use App\Http\Controllers\MLM\CCSettingController;
use App\Http\Controllers\MLM\MLMOrderController;
use App\Http\Controllers\MLM\MLMPayoutController;
use App\Http\Controllers\MLM\MLMTreeController;
use App\Http\Controllers\MLM\MLMUserController;
use App\Http\Controllers\MLM\ReferralDownlineController;
use App\Http\Controllers\MLM\RegistrationController;
use App\Http\Controllers\MLM\TeamGenealogyController;
use App\Http\Controllers\MLM\WalletController;
use App\Http\Controllers\NdisAboutController;
use App\Http\Controllers\NdisServiceController;
use App\Http\Controllers\NdisSupportController;
use App\Http\Controllers\NetworkController;
use App\Http\Controllers\NiisqSectionController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\ParameterController;
use App\Http\Controllers\PartnerAboutController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\PlanManagementAboutController;
use App\Http\Controllers\PlanManagementBenefitController;
use App\Http\Controllers\PlanManagementFaqController;
use App\Http\Controllers\PopularTestController;
use App\Http\Controllers\PrivacyPolicyController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\SeoPageController;
use App\Http\Controllers\SeoSettingController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ServicesSectionController;
use App\Http\Controllers\ShippingPolicyController;
use App\Http\Controllers\SiteImagesController;
use App\Http\Controllers\SliderImageController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\StaffResourceController;
use App\Http\Controllers\SubparameterController;
use App\Http\Controllers\SupportApplySectionController;
use App\Http\Controllers\SupportCoordinationAboutController;
use App\Http\Controllers\SupportCoordinationBenefitController;
use App\Http\Controllers\SupportCoordinationFaqController;
use App\Http\Controllers\SupportCoordinationPlanController;
use App\Http\Controllers\SupportCoordinationServiceController;
use App\Http\Controllers\SystemSetting;
use App\Http\Controllers\TermsConditionController;

use App\Http\Controllers\TestController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\UserRegisterController;

use App\Http\Controllers\ValueController;
use App\Http\Controllers\WebsiteSetting;
use App\Http\Controllers\WhyChooseSectionController;
use App\Http\Controllers\WhyPartnerController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return redirect('/admin-panel');
});



// ALL THE ROUTES FOR BACKEND DASHBOARD


Route::get('/dashboard', [MLMUserController::class, 'dashboard'])
    ->middleware('auth')
    ->name('dashboard');

Route::middleware('auth')->group(function () {

    // ────────────── Product Details ──────────────
    Route::get('/product-category', [ProductCategoryController::class, 'index'])->name('product-categories.index');
    // Store
    Route::post('/product-categories', [ProductCategoryController::class, 'store'])
        ->name('product-categories.store');

    // Update
    Route::put('/product-categories/{category}', [ProductCategoryController::class, 'update'])
        ->name('product-categories.update');

    // Destroy
    Route::delete('/product-categories/{category}', [ProductCategoryController::class, 'destroy'])
        ->name('product-categories.destroy');

    Route::resource('products', ProductController::class);
    Route::delete('products/{product}/remove-image', [ProductController::class, 'removeImage'])
        ->name('products.remove-image');
    Route::put('/products/{product}/update-cc', [ProductController::class, 'updateCC'])
        ->name('products.update-cc');

    // ────────────── Tests Details ──────────────
    Route::get('/tests', [TestController::class, 'index'])->name('admin.pages.test');
    Route::post('/tests', [TestController::class, 'store'])->name('admin.tests.store');
    Route::put('/tests/{test}', [TestController::class, 'update'])->name('admin.tests.update');
    Route::delete('/tests/{test}', [TestController::class, 'destroy'])->name('admin.tests.destroy');
    Route::post('/tests/delete-selected', [TestController::class, 'deleteSelected'])
        ->name('tests.delete-selected');

    // ────────────── PARAMETERS ──────────────

    Route::get('/parameters', [ParameterController::class, 'index'])
        ->name('admin.pages.parameter');
    Route::post('/parameters', [ParameterController::class, 'store'])
        ->name('admin.parameters.store');
    Route::put('/parameters/{parameter}', [ParameterController::class, 'update'])
        ->name('admin.parameters.update');
    Route::delete('/parameters/{parameter}', [ParameterController::class, 'destroy'])
        ->name('admin.parameters.destroy');
    Route::post('/parameters/delete-selected', [ParameterController::class, 'deleteSelected'])
        ->name('parameters.delete-selected');

    // ────────────── Health Risks ──────────────
    Route::get('/health-risks', [HealthRiskController::class, 'index'])
        ->name('health-risks.index');

    Route::post('/health-risks', [HealthRiskController::class, 'store'])
        ->name('health-risks.store');

    Route::put('/health-risks/{healthRisk}', [HealthRiskController::class, 'update'])
        ->name('health-risks.update');

    Route::delete('/health-risks/{healthRisk}', [HealthRiskController::class, 'destroy'])
        ->name('health-risks.destroy');
    Route::post('/health-risks/delete-selected', [HealthRiskController::class, 'deleteSelected'])
        ->name('health-risks.delete-selected');


    // ────────────── Health Package/ sub parameter ──────────────
    Route::get('/admin-subparameters', [SubparameterController::class, 'index'])
        ->name('admin-subparameters.index');

    Route::post('/admin-subparameters', [SubparameterController::class, 'store'])
        ->name('admin-subparameters.store');

    Route::put('/admin-subparameters/{subparameter}', [SubparameterController::class, 'update'])
        ->name('admin-subparameters.update');

    Route::delete('/admin-subparameters/{subparameter}', [SubparameterController::class, 'destroy'])
        ->name('admin-subparameters.destroy');

    Route::post('/admin-subparameters/delete-selected', [SubparameterController::class, 'deleteSelected'])
        ->name('admin-subparameters.delete-selected');

    // ────────────── faqs for the health packages ──────────────


    Route::get('/faqspackages', [FaqsController::class, 'index'])->name('faqspackages.index');
    Route::post('/faqspackages', [FaqsController::class, 'store'])->name('faqspackages.store');
    Route::put('/faqspackages/{faqspackage}', [FaqsController::class, 'update'])->name('faqspackages.update');
    Route::delete('/faqspackages/{faqspackage}', [FaqsController::class, 'destroy'])->name('faqspackages.destroy');



    // ────────────── Blog Category ──────────────
    Route::get('/blog-categories', [BlogCategoryController::class, 'index'])
        ->name('blog-categories.index');

    Route::post('/blog-categories', [BlogCategoryController::class, 'store'])
        ->name('blog-categories.store');

    Route::put('/blog-categories/{blogCategory}', [BlogCategoryController::class, 'update'])
        ->name('blog-categories.update');

    Route::delete('/blog-categories/{blogCategory}', [BlogCategoryController::class, 'destroy'])
        ->name('blog-categories.destroy');


    // ────────────── Blog Posts ──────────────
    Route::get('/blogs', [BlogController::class, 'index'])
        ->name('blogs.index');

    Route::post('/blogs', [BlogController::class, 'store'])
        ->name('blogs.store');

    Route::put('/blogs/{blog}', [BlogController::class, 'update'])
        ->name('blogs.update');

    Route::delete('/blogs/{blog}', [BlogController::class, 'destroy'])
        ->name('blogs.destroy');






    // ────────────── FAQs ──────────────
    Route::get('/faqs', [FaqController::class, 'index'])
        ->name('faqs.index');

    Route::post('/faqs', [FaqController::class, 'store'])
        ->name('faqs.store');

    Route::put('/faqs/{faq}', [FaqController::class, 'update'])
        ->name('faqs.update');

    Route::delete('/faqs/{faq}', [FaqController::class, 'destroy'])
        ->name('faqs.destroy');


    // ────────────── Partners ──────────────
    Route::get('/partners', [PartnerController::class, 'index'])
        ->name('partners.index');

    Route::post('/partners', [PartnerController::class, 'store'])
        ->name('partners.store');

    Route::put('/partners/{partner}', [PartnerController::class, 'update'])
        ->name('partners.update');

    Route::delete('/partners/{partner}', [PartnerController::class, 'destroy'])
        ->name('partners.destroy');

    // ────────────── Counter ──────────────
    Route::get('/counters', [CounterController::class, 'index'])->name('counters.index');
    Route::put('/counters', [CounterController::class, 'update'])->name('counters.update');

    // ────────────── Ads and Popup image ──────────────

    Route::get('/site-images', [SiteImagesController::class, 'index'])
        ->name('site-images.index');

    Route::put('/site-images/popup', [SiteImagesController::class, 'updatePopup'])
        ->name('site-images.update-popup');

    Route::put('/site-images/ads', [SiteImagesController::class, 'updateAds'])
        ->name('site-images.update-ads');

    // ────────────── Gallery ──────────────
    Route::get('/gallery', [GalleryController::class, 'index'])
        ->name('gallery.index');

    Route::post('/gallery', [GalleryController::class, 'store'])
        ->name('gallery.store');

    Route::put('/gallery/{gallery}', [GalleryController::class, 'update'])
        ->name('gallery.update');

    Route::delete('/gallery/{gallery}', [GalleryController::class, 'destroy'])
        ->name('gallery.destroy');





    // ────────────── SLIDER IMAGES ──────────────
    Route::get('/sliderimage', [SliderImageController::class, 'index'])
        ->name('sliderimage.index');
    Route::post('/sliderimage', [SliderImageController::class, 'store'])
        ->name('sliderimage.store');
    Route::put('/sliderimage/{sliderImage}', [SliderImageController::class, 'update'])
        ->name('sliderimage.update');
    Route::delete('/sliderimage/{sliderImage}', [SliderImageController::class, 'destroy'])
        ->name('sliderimage.destroy');



    // ────────────── JOB CAREER (Job Openings) ──────────────
    Route::get('/jobcareer', [JobCareerController::class, 'index'])
        ->name('jobcareer.index');

    Route::get('/jobcareer/create', [JobCareerController::class, 'create'])
        ->name('jobcareer.create');

    Route::post('/jobcareer', [JobCareerController::class, 'store'])
        ->name('jobcareer.store');

    Route::get('/jobcareer/{jobCareer}/edit', [JobCareerController::class, 'edit'])
        ->name('jobcareer.edit');

    Route::put('/jobcareer/{jobCareer}', [JobCareerController::class, 'update'])
        ->name('jobcareer.update');

    Route::delete('/jobcareer/{jobCareer}', [JobCareerController::class, 'destroy'])
        ->name('jobcareer.destroy');

    // ────────────── ACCREDITATIONS SECTION ──────────────
    Route::get('/accreditations', [AccreditationController::class, 'index'])
        ->name('accreditations.index');

    Route::get('/accreditations/edit', [AccreditationController::class, 'edit'])
        ->name('accreditations.edit');

    Route::put('/accreditations', [AccreditationController::class, 'update'])
        ->name('accreditations.update');

    // ────────────── ABOUT SECTION TWO (Know Us Better) ──────────────
    Route::get('/about-section-two', [AboutSectionTwoController::class, 'index'])
        ->name('about-section-two.index');

    Route::get('/about-section-two/edit', [AboutSectionTwoController::class, 'edit'])
        ->name('about-section-two.edit');

    Route::put('/about-section-two', [AboutSectionTwoController::class, 'update'])
        ->name('about-section-two.update');


    Route::post('/services/delete/{service}', [ServiceController::class, 'destroy'])->name('services.destroy');


    // ────────────── Profile ──────────────  
    Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'edit'])
        ->name('profile');

    Route::patch('/profile', [App\Http\Controllers\ProfileController::class, 'update'])
        ->name('profile.update');

    Route::put('/profile/password', [App\Http\Controllers\Auth\PasswordController::class, 'update'])
        ->name('profile.password.update');


    // Separate pages for Roles and Permissions
    Route::get('/roles', [RolePermissionController::class, 'rolesIndex'])
        ->name('roles.index');

    Route::get('/permissions', [RolePermissionController::class, 'permissionsIndex'])
        ->name('permissions.index');

    // Permissions CRUD
    Route::post('/permissions', [RolePermissionController::class, 'storePermission'])
        ->name('permissions.store');

    Route::put('/permissions/{permission}', [RolePermissionController::class, 'updatePermission'])
        ->name('permissions.update');

    Route::delete('/permissions/{permission}', [RolePermissionController::class, 'destroyPermission'])
        ->name('permissions.destroy');

    // Roles CRUD
    Route::post('/roles', [RolePermissionController::class, 'storeRole'])
        ->name('roles.store');

    Route::put('/roles/{role}', [RolePermissionController::class, 'updateRole'])
        ->name('roles.update');

    Route::delete('/roles/{role}', [RolePermissionController::class, 'destroyRole'])
        ->name('roles.destroy');

    // Assign permissions to role
    Route::put('/roles/{role}/permissions', [RolePermissionController::class, 'updateRolePermissions'])
        ->name('roles.permissions.update');


    // User Management Routes
    Route::get('/users', [UserRegisterController::class, 'index'])
        ->name('admin-register.index');

    Route::post('/users', [UserRegisterController::class, 'store'])
        ->name('admin-register.store');

    Route::put('/users/{user}', [UserRegisterController::class, 'update'])
        ->name('admin-register.update');

    Route::delete('/users/{user}', [UserRegisterController::class, 'destroy'])
        ->name('admin-register.destroy');

    Route::put('/users/{user}/role', [UserRegisterController::class, 'updateRole'])
        ->name('admin-register.role.update');

    Route::get('/users/{user}', [UserRegisterController::class, 'show'])
        ->name('admin-register.show');




    //test services 


    Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
    Route::post('/services', [ServiceController::class, 'store'])->name('services.store');
    Route::post('/services/update', [ServiceController::class, 'update'])->name('services.update');


    // FOR DOCTOR
    Route::post('/doctors/store', [DoctorController::class, 'store'])->name('admin-doctor.store');
    Route::get('/admin-doctors', [DoctorController::class, 'index'])->name('admin-doctors.index');
    Route::get('/doctors/view/{id}', [DoctorController::class, 'view']);
    Route::post('/doctors/update', [DoctorController::class, 'update']);
    Route::delete('/doctors/delete/{id}', [DoctorController::class, 'delete']);


    // FOR APPOINTMENT
    Route::get('/appointments', [AppointmentController::class, 'index'])->name('admin-appointment.index');
    Route::get('/appointment/view/{id}', [AppointmentController::class, 'view']);
    Route::post('/appointment/update', [AppointmentController::class, 'update']);
    Route::delete('/appointment/delete/{id}', [AppointmentController::class, 'delete']);
    Route::post('/appointments/delete-selected', [AppointmentController::class, 'deleteSelected'])
        ->name('appointments.delete-selected');

    // FOR PACKAGES 
    Route::get('/admin-packages', [PackageController::class, 'index'])->name('packages.index');
    Route::post('/admin-packages/store', [PackageController::class, 'store'])->name('packages.store');
    Route::get('/admin-packages/view/{id}', [PackageController::class, 'view']);


    // FOR CONTACT 
    Route::get('/contacts', [ContactController::class, 'index'])->name('admin-contact.index');
    Route::get('/contacts/view/{id}', [ContactController::class, 'view']);
    Route::post('/contacts/update', [ContactController::class, 'update']);
    Route::delete('/contacts/delete/{id}', [ContactController::class, 'delete']);

    // FOR BOOKING FORM
    Route::get('/booking-lead', [BookingController::class, 'index'])->name('admin-booking.index');
    Route::delete('/booking-lead/delete/{id}', [BookingController::class, 'delete']);


    // FOR CORPORATE 
    Route::get('/corporate-benefit', [CorporateBenefitController::class, 'index'])
        ->name('admin-corporate.index');
    Route::put('/corporate-benefit', [CorporateBenefitController::class, 'update'])->name('admin-corporate.update');

    // FOR CORPORATE 
    Route::get('/corporate-services', [CorporateServiceController::class, 'index'])
        ->name('admin-corporate-service.index');
    Route::put('/corporate-services', [CorporateServiceController::class, 'update'])->name('admin-corporate-service.update');


    // WHAT MAKES US DIFFERENT ABOUT SECTION
    Route::get('/About-makes-us-different', [AboutMakeController::class, 'index'])
        ->name('admin-about-makes.index');
    Route::put('/About-makes-us-different', [AboutMakeController::class, 'update'])->name('admin-about-makes.update');

    // PARTNER ABOUT SECTION
    Route::get('/partner-about', [PartnerAboutController::class, 'index'])
        ->name('admin-partner-about.index');
    Route::put('/partner-about', [PartnerAboutController::class, 'update'])->name('admin-partner-about.update');

    //WHY PARTNER  SECTION
    Route::get('/why-partner', [WhyPartnerController::class, 'index'])
        ->name('admin-why-partner.index');
    Route::put('/why-partner', [WhyPartnerController::class, 'update'])->name('admin-why-partner.update');

    // PRIVACY POLICY CONTENT
    Route::get('/privacy-policy-content', [PrivacyPolicyController::class, 'index'])
        ->name('admin-privacy-policy.index');

    Route::put('/privacy-policy/update', [PrivacyPolicyController::class, 'update'])
        ->name('admin-privacy-policy.update');

    // Accessibility  CONTENT
    Route::get('/admin-accessibility', [AccessibilityController::class, 'index'])
        ->name('admin-accessibility.index');

    Route::put('/admin-accessibility/update', [AccessibilityController::class, 'update'])
        ->name('admin-accessibility.update');

    // Cancellation  CONTENT
    Route::get('/admin-cancellation-policy', [CancellationController::class, 'index'])
        ->name('admin-cancellation-policy.index');

    Route::put('/admin-cancellation-policy/update', [CancellationController::class, 'update'])
        ->name('admin-cancellation-policy.update');

    // Discalimer  CONTENT
    Route::get('/admin-disclaimer', [DiscalimerController::class, 'index'])
        ->name('admin-disclaimer.index');

    Route::put('/admin-disclaimer/update', [DiscalimerController::class, 'update'])
        ->name('admin-disclaimer.update');

    // Grievance  CONTENT
    Route::get('/admin-grievance-redressal', [GrivanceRedressalController::class, 'index'])
        ->name('admin-grievance-redressal.index');

    Route::put('/admin-grievance-redressal/update', [GrivanceRedressalController::class, 'update'])
        ->name('admin-grievance-redressal.update');

    // Discalimer  CONTENT
    Route::get('/admin-shipping-policy', [ShippingPolicyController::class, 'index'])
        ->name('admin-shipping-policy.index');

    Route::put('/admin-shipping-policy/update', [ShippingPolicyController::class, 'update'])
        ->name('admin-shipping-policy.update');

    // TERMS CONDITION POLICY CONTENT
    Route::get('/terms-condition-content', [TermsConditionController::class, 'index'])
        ->name('admin-terms-condition.index');

    Route::put('/terms-condition/update', [TermsConditionController::class, 'update'])
        ->name('admin-terms-condition.update');

    // SEO Pages
    Route::post('/seo-pages/store', [SeoPageController::class, 'store'])->name('seo-pages.store');
    Route::get('/seo-pages', [SeoPageController::class, 'index'])->name('seo-pages.index');
    Route::post('/seo-pages/update', [SeoPageController::class, 'update']);
    Route::delete('/seo-pages/delete/{id}', [SeoPageController::class, 'delete']);


    // SEO SETTING
    Route::post('/seo-setting/store', [SeoSettingController::class, 'store'])->name('seo-setting.store');
    Route::get('/seo-setting', [SeoSettingController::class, 'index'])->name('seo-setting.index');
    Route::get('/seo-setting/view/{id}', [SeoSettingController::class, 'view']);
    Route::post('/seo-setting/update', [SeoSettingController::class, 'update']);
    Route::delete('/seo-setting/delete/{id}', [SeoSettingController::class, 'delete']);


    // For CAREER
    Route::get('/applications', [JobCareerApplicationController::class, 'index'])->name('admin-applications.index');
    Route::get('/applications/view/{id}', [JobCareerApplicationController::class, 'view']);
    Route::post('/applications/update', [JobCareerApplicationController::class, 'update']);
    Route::delete('/applications/delete/{id}', [JobCareerApplicationController::class, 'delete']);
    Route::post('/applications/delete-selected', [JobCareerApplicationController::class, 'deleteSelected'])
        ->name('applications.delete-selected');


    // FOR POPULAR TEST
    Route::post('/popularTest/store', [PopularTestController::class, 'store'])->name('admin-popularTest.store');
    Route::get('/admin-popularTest', [PopularTestController::class, 'index'])->name('admin-popularTest.index');
    Route::get('/popularTest/view/{id}', [PopularTestController::class, 'view']);
    Route::post('/popularTest/update', [PopularTestController::class, 'update']);
    Route::delete('/popularTest/delete/{id}', [PopularTestController::class, 'delete']);
    Route::post('/popularTest/delete-selected', [PopularTestController::class, 'deleteSelected']);




    // ────────────── Website Setting ──────────────

    Route::get('/website-setting', [WebsiteSetting::class, 'index'])
        ->name('website-setting');

    Route::put('/website-setting', [WebsiteSetting::class, 'update'])
        ->name('website-setting.update');



    // ────────────── Website Setting ──────────────

    Route::get('/system-setting', [SystemSetting::class, 'index'])
        ->name('system-setting');

    Route::put('/system-setting', [SystemSetting::class, 'update'])
        ->name('system-setting.update');


    // ────────────── General/ Captcha Setting ──────────────

    Route::get('/general-setting', [CaptchaSettingController::class, 'index'])
        ->name('general-setting');

    Route::put('/general-setting', [CaptchaSettingController::class, 'update'])
        ->name('general-setting.update');





    // Dashboard Routes for admin panel
    // For about section 
    Route::get('/about-section', [AboutCareController::class, 'index'])->name('about-section.index');
    Route::put('/about-section', [AboutCareController::class, 'update'])->name('about-section.update');
    // FOr Act About
    Route::get('/act-about-section', [ActAboutController::class, 'index'])->name('act-about-section.index');
    Route::put('/act-about-section', [ActAboutController::class, 'update'])->name('act-about-section.update');
    // For How we work
    Route::get('/how-works', [HowWorkController::class, 'index'])->name('how-works.index');
    Route::put('/how-works/{section}', [HowWorkController::class, 'update'])->name('how-works.update');
    // for staff crud
    Route::post('/staff/store', [StaffController::class, 'store'])->name('admin-staff.store');
    Route::get('/staff', [StaffController::class, 'index'])->name('admin-staff.index');
    Route::get('/staff/view/{id}', [StaffController::class, 'view']);
    Route::post('/staff/update', [StaffController::class, 'update']);
    Route::delete('/staff/delete/{id}', [StaffController::class, 'delete']);
    Route::post('/staff/deleteSelected', [StaffController::class, 'deleteSelected']);
    // For Why choose us section 
    Route::get('/whychoose-section', [WhyChooseSectionController::class, 'index'])->name('whychoose-section.index');
    Route::post('/whychoose-section', [WhyChooseSectionController::class, 'store'])->name('whychoose-section.store');
    Route::put('/whychoose-section/{card}', [WhyChooseSectionController::class, 'update'])->name('whychoose-section.update');
    Route::delete('/whychoose-section/{card}', [WhyChooseSectionController::class, 'destroy'])->name('whychoose-section.destroy');
    // for testimonial
    Route::get('/testimonials', [TestimonialController::class, 'index'])->name('testimonials.index');
    Route::post('/testimonials', [TestimonialController::class, 'store'])->name('testimonials.store');
    Route::put('/testimonials/{testimonial}', [TestimonialController::class, 'update'])->name('testimonials.update');
    Route::delete('/testimonials/{testimonial}', [TestimonialController::class, 'destroy'])->name('testimonials.destroy');
    // client resources policy
    Route::get('/client-resources', [ClientResourceController::class, 'index'])->name('client-resources.index');
    Route::post('/client-resources', [ClientResourceController::class, 'store'])->name('client-resource.store');
    Route::put('client-resources/{ClientPolicy}', [ClientResourceController::class, 'update'])->name('client-resources.update');
    Route::delete('/client-resource/{card}', [ClientResourceController::class, 'destroy'])->name('client-resources.destroy');
    // staff resources
    Route::get('/staff-resources', [StaffResourceController::class, 'index'])->name('staff-resources.index');
    Route::post('/staff-resources', [StaffResourceController::class, 'store'])->name('staff-resource.store');
    Route::put('staff-resources/{StaffPolicy}', [StaffResourceController::class, 'update'])->name('staff-resources.update');
    Route::delete('/staff-resource/{card}', [StaffResourceController::class, 'destroy'])->name('staff-resources.destroy');
    // For about two section
    Route::get('/about-two-section', [AboutTwoController::class, 'index'])->name('about-two-section.index');
    Route::put('/about-two-section', [AboutTwoController::class, 'update'])->name('about-two-section.update');
    // case study 
    Route::get('/case-study', [CaseStudyController::class, 'index'])->name('case-study.index');
    Route::post('/case-study', [CaseStudyController::class, 'store'])->name('case-study.store');
    Route::put('case-study/{CaseStudy}', [CaseStudyController::class, 'update'])->name('case-study.update');
    Route::delete('/case-study/{card}', [CaseStudyController::class, 'destroy'])->name('case-study.destroy');
    // For How we work
    Route::get('/network', [NetworkController::class, 'index'])->name('network.index');
    Route::put('/network/{section}', [NetworkController::class, 'update'])->name('network.update');
    // For Services
    Route::get('/services', [ServicesSectionController::class, 'index'])->name('services.index');
    Route::put('/services/{section}', [ServicesSectionController::class, 'update'])->name('services.update');
    Route::get('/counters-section', [CountersSectionController::class, 'index'])->name('counters-section.index');
    Route::put('/counters-section/{section}', [CountersSectionController::class, 'update'])->name('counters-section.update');
    // For Values Sction
    Route::get('/value', [ValueController::class, 'index'])->name('value.index');
    Route::put('/value/{section}', [ValueController::class, 'update'])->name('value.update');

    // for about faq
    Route::get('/about-faqs', [AboutFaqController::class, 'index'])->name('about-faqs.index');
    Route::post('/about-faqs', [AboutFaqController::class, 'store'])->name('about-faqs.store');
    Route::put('/about-faqs/{faq}', [AboutFaqController::class, 'update'])->name('about-faqs.update');
    Route::delete('/about-faqs/{faq}', [AboutFaqController::class, 'destroy'])->name('about-faqs.destroy');
    // for commitment
    Route::get('/commitment-one', [CommitmentOneController::class, 'index'])->name('commitment-one.index');
    Route::put('/commitment-one', [CommitmentOneController::class, 'update'])->name('commitment-one.update');
    // for commitment two
    Route::get('/commitment-two', [CommitmentTwoController::class, 'index'])->name('commitment-two.index');
    Route::put('/commitment-two', [CommitmentTwoController::class, 'update'])->name('commitment-two.update');
    // for commitment three
    Route::get('/commitment-three', [CommitmentThreeController::class, 'index'])->name('commitment-three.index');
    Route::put('/commitment-three', [CommitmentThreeController::class, 'update'])->name('commitment-three.update');
    // for commitment Four
    Route::get('/commitment-four', [CommitmentFourController::class, 'index'])->name('commitment-four.index');
    Route::put('/commitment-four', [CommitmentFourController::class, 'update'])->name('commitment-four.update');
    // for ndis about section 
    Route::get('/ndis-about', [NdisAboutController::class, 'index'])->name('ndis-about.index');
    Route::put('/ndis-about', [NdisAboutController::class, 'update'])->name('ndis-about.update');
    // for ndis service section 
    Route::get('/ndis-service', [NdisServiceController::class, 'index'])->name('ndis-service.index');
    Route::put('/ndis-service', [NdisServiceController::class, 'update'])->name('ndis-service.update');
    // for support 
    Route::get('/ndis-support', [NdisSupportController::class, 'index'])->name('ndis-support.index');
    Route::put('/ndis-support', [NdisSupportController::class, 'update'])->name('ndis-support.update');
    // for aged care sections
    Route::get('/aged-about', [AgedAboutController::class, 'index'])->name('aged-about.index');
    Route::put('/aged-about', [AgedAboutController::class, 'update'])->name('aged-about.update');
    // for aged benefits
    Route::get('/aged-benefit', [AgedBenefitController::class, 'index'])->name('aged-benefit.index');
    Route::put('/aged-benefit', [AgedBenefitController::class, 'update'])->name('aged-benefit.update');
    // for aged services
    Route::get('/aged-service', [AgedServiceController::class, 'index'])->name('aged-service.index');
    Route::put('/aged-service/{section}', [AgedServiceController::class, 'update'])->name('aged-service.update');
    // for niisq page
    Route::get('/niisq-page', [NiisqSectionController::class, 'index'])->name('niisq-page.index');
    Route::put('/niisq-page', [NiisqSectionController::class, 'update'])->name('niisq-page.update');
    // for dva page
    Route::get('/dva-page', [DvaSectionController::class, 'index'])->name('dva-page.index');
    Route::put('/dva-page', [DvaSectionController::class, 'update'])->name('dva-page.update');
    // For Home care support
    Route::get('/home-care-support', [HomeCareServiceController::class, 'index'])->name('home-care-support.index');
    Route::put('/home-care-support/{section}', [HomeCareServiceController::class, 'update'])->name('home-care-support.update');
    // for ndis about section 
    Route::get('/home-care-about', [HomeCareAboutController::class, 'index'])->name('home-care-about.index');
    Route::put('/home-care-about', [HomeCareAboutController::class, 'update'])->name('home-care-about.update');
    // For Home care2 services 
    Route::get('/home-care-service', [HomeCare2ServiceController::class, 'index'])->name('home-care-service.index');
    Route::post('/home-care-service', [HomeCare2ServiceController::class, 'store'])->name('home-care-service.store');
    Route::put('/home-care-service/{card}', [HomeCare2ServiceController::class, 'update'])->name('home-care-service.update');
    Route::delete('/home-care-service/{card}', [HomeCare2ServiceController::class, 'destroy'])->name('home-care-service.destroy');
    // home care
    Route::get('/home-care-difference', [HomeCareDifferenceController::class, 'index'])->name('home-care-difference.index');
    Route::put('/home-care-difference/{section}', [HomeCareDifferenceController::class, 'update'])->name('home-care-difference.update');

    // for home care community
    Route::get('/home-care-community', [HomeCareCommunityController::class, 'index'])->name('home-care-community.index');
    Route::post('/home-care-community', [HomeCareCommunityController::class, 'store'])->name('home-care-community.store');
    Route::put('/home-care-community/{card}', [HomeCareCommunityController::class, 'update'])->name('home-care-community.update');
    Route::delete('/home-care-community/{card}', [HomeCareCommunityController::class, 'destroy'])->name('home-care-community.destroy');
    // for support coordination about section 
    Route::get('/support-care-about', [SupportCoordinationAboutController::class, 'index'])->name('support-care-about.index');
    Route::put('/support-care-about', [SupportCoordinationAboutController::class, 'update'])->name('support-care-about.update');
    // support coordination plan
    Route::get('/support-coordination-plan', [SupportCoordinationPlanController::class, 'index'])->name('support-coordination-plan.index');
    Route::post('/support-coordination-plan', [SupportCoordinationPlanController::class, 'store'])->name('support-coordination-plan.store');
    Route::put('/support-coordination-plan/{card}', [SupportCoordinationPlanController::class, 'update'])->name('support-coordination-plan.update');
    Route::delete('/support-coordination-plan/{card}', [SupportCoordinationPlanController::class, 'destroy'])->name('support-coordination-plan.destroy');
    // For support coordination service
    Route::get('/support-coordination-service', [SupportCoordinationServiceController::class, 'index'])->name('support-coordination-service.index');
    Route::post('/support-coordination-service', [SupportCoordinationServiceController::class, 'store'])->name('support-coordination-service.store');
    Route::put('/support-coordination-service/{card}', [SupportCoordinationServiceController::class, 'update'])->name('support-coordination-service.update');
    Route::delete('/support-coordination-service/{card}', [SupportCoordinationServiceController::class, 'destroy'])->name('support-coordination-service.destroy');
    // support benefit
    Route::get('/support-coordination-benefit', [SupportCoordinationBenefitController::class, 'index'])->name('support-coordination-benefit.index');
    Route::put('/support-coordination-benefit', [SupportCoordinationBenefitController::class, 'update'])->name('support-coordination-benefit.update');

    // for support faq
    Route::get('/support-coordination-faqs', [SupportCoordinationFaqController::class, 'index'])->name('support-coordination-faqs.index');
    Route::post('/support-coordination-faqs', [SupportCoordinationFaqController::class, 'store'])->name('support-coordination-faqs.store');
    Route::put('/support-coordination-faqs/{faq}', [SupportCoordinationFaqController::class, 'update'])->name('support-coordination-faqs.update');
    Route::delete('/support-coordination-faqs/{faq}', [SupportCoordinationFaqController::class, 'destroy'])->name('support-coordination-faqs.destroy');
    // for Plan Management faq
    Route::get('/plan-management-faqs', [PlanManagementFaqController::class, 'index'])->name('plan-management-faqs.index');
    Route::post('/plan-management-faqs', [PlanManagementFaqController::class, 'store'])->name('plan-management-faqs.store');
    Route::put('/plan-management-faqs/{faq}', [PlanManagementFaqController::class, 'update'])->name('plan-management-faqs.update');
    Route::delete('/plan-management-faqs/{faq}', [PlanManagementFaqController::class, 'destroy'])->name('plan-management-faqs.destroy');
    // plan management about
    Route::get('/plan-management-about', [PlanManagementAboutController::class, 'index'])->name('plan-management-about.index');
    Route::post('/plan-management-about', [PlanManagementAboutController::class, 'store'])->name('plan-management-about.store');
    Route::put('/plan-management-about/{card}', [PlanManagementAboutController::class, 'update'])->name('plan-management-about.update');
    Route::delete('/plan-management-about/{card}', [PlanManagementAboutController::class, 'destroy'])->name('plan-management-about.destroy');
    // benefit
    Route::get('/plan-management-benefit', [PlanManagementBenefitController::class, 'index'])->name('plan-management-benefit.index');
    Route::put('/plan-management-benefit/{section}', [PlanManagementBenefitController::class, 'update'])->name('plan-management-benefit.update');

    // for allied health about
    Route::get('/allied-health-about', [AlliedHealthAboutController::class, 'index'])->name('allied-health-about.index');
    Route::put('/allied-health-about', [AlliedHealthAboutController::class, 'update'])->name('allied-health-about.update');
    // for community nursing about
    Route::get('/community-nursing-about', [CommunityNursingAboutController::class, 'index'])->name('community-nursing-about.index');
    Route::put('/community-nursing-about', [CommunityNursingAboutController::class, 'update'])->name('community-nursing-about.update');
    // for care coordination about
    Route::get('/care-coordination-about', action: [CareCoordinationAboutController::class, 'index'])->name('care-coordination-about.index');
    Route::put('/care-coordination-about', [CareCoordinationAboutController::class, 'update'])->name('care-coordination-about.update');
    // for SIL about
    Route::get('/SIL-about', action: [IndependentAboutController::class, 'index'])->name('SIL-about.index');
    Route::put('/SIL-about', [IndependentAboutController::class, 'update'])->name('SIL-about.update');
    // for community service
    Route::get('/community-about', action: [CommunityAboutController::class, 'index'])->name('community-about.index');
    Route::put('/community-about', [CommunityAboutController::class, 'update'])->name('community-about.update');
    // for allied health faq section
    Route::get('/allied-health-faqs', [AliiedFaqController::class, 'index'])->name('allied-health-faqs.index');
    Route::post('/allied-health-faqs', [AliiedFaqController::class, 'store'])->name('allied-health-faqs.store');
    Route::put('/allied-health-faqs/{faq}', [AliiedFaqController::class, 'update'])->name('allied-health-faqs.update');
    Route::delete('/allied-health-faqs/{faq}', [AliiedFaqController::class, 'destroy'])->name('allied-health-faqs.destroy');
    // for Community nursing faq section
    Route::get('/community-nursing-faqs', [CommunityNursingFaqController::class, 'index'])->name('community-nursing-faqs.index');
    Route::post('/community-nursing-faqs', [CommunityNursingFaqController::class, 'store'])->name('community-nursing-faqs.store');
    Route::put('/community-nursing-faqs/{faq}', [CommunityNursingFaqController::class, 'update'])->name('community-nursing-faqs.update');
    Route::delete('/community-nursing-faqs/{faq}', [CommunityNursingFaqController::class, 'destroy'])->name('community-nursing-faqs.destroy');
    // for care coordination faq section
    Route::get('/care-coordination-faqs', [CareFaqController::class, 'index'])->name('care-coordination-faqs.index');
    Route::post('/care-coordination-faqs', [CareFaqController::class, 'store'])->name('care-coordination-faqs.store');
    Route::put('/care-coordination-faqs/{faq}', [CareFaqController::class, 'update'])->name('care-coordination-faqs.update');
    Route::delete('/care-coordination-faqs/{faq}', [CareFaqController::class, 'destroy'])->name('care-coordination-faqs.destroy');
    // for allied health support
    Route::get('/allied-health-support', [AlliedHealthSupportController::class, 'index'])->name('allied-health-support.index');
    Route::put('/allied-health-support', [AlliedHealthSupportController::class, 'update'])->name('allied-health-support.update');
    // for allied health journey
    Route::get('/allied-health-journey', [AlliedHealthJourneyController::class, 'index'])->name('allied-health-journey.index');
    Route::put('/allied-health-journey', [AlliedHealthJourneyController::class, 'update'])->name('allied-health-journey.update');
    // for allied health services
    Route::get('/allied-health-service', [AlliedHealthServiceController::class, 'index'])->name('allied-health-service.index');
    Route::post('/allied-health-service', [AlliedHealthServiceController::class, 'store'])->name('allied-health-service.store');
    Route::put('/allied-health-service/{card}', [AlliedHealthServiceController::class, 'update'])->name('allied-health-service.update');
    Route::delete('/allied-health-service/{card}', [AlliedHealthServiceController::class, 'destroy'])->name('allied-health-service.destroy');
    // for community nursing services
    Route::get('/community-nursing-service', [CommunityNursingServiceController::class, 'index'])->name('community-nursing-service.index');
    Route::post('/community-nursing-service', [CommunityNursingServiceController::class, 'store'])->name('community-nursing-service.store');
    Route::put('/community-nursing-service/{card}', [CommunityNursingServiceController::class, 'update'])->name('community-nursing-service.update');
    Route::delete('/community-nursing-service/{card}', [CommunityNursingServiceController::class, 'destroy'])->name('community-nursing-service.destroy');

    // for community nursing act
    Route::get('/community-nursing-act', [CommunityNursingActController::class, 'index'])->name('community-nursing-act.index');
    Route::put('/community-nursing-act', [CommunityNursingActController::class, 'update'])->name('community-nursing-act.update');

    // for support apply
    Route::get('/support-apply', action: [SupportApplySectionController::class, 'index'])->name('support-apply.index');
    Route::put('/support-apply', [SupportApplySectionController::class, 'update'])->name('support-apply.update');
    // for community eligibility faq
    Route::get('/community-eligibility-faqs', [CommunityEligibilityFaqController::class, 'index'])->name('community-eligibility-faqs.index');
    Route::post('/community-eligibility-faqs', [CommunityEligibilityFaqController::class, 'store'])->name('community-eligibility-faqs.store');
    Route::put('/community-eligibility-faqs/{faq}', [CommunityEligibilityFaqController::class, 'update'])->name('community-eligibility-faqs.update');
    Route::delete('/community-eligibility-faqs/{faq}', [CommunityEligibilityFaqController::class, 'destroy'])->name('community-eligibility-faqs.destroy');
    // For community How we work
    Route::get('/community-how-works', [CommunityWorkController::class, 'index'])->name('community-how-works.index');
    Route::put('/community-how-works/{section}', [CommunityWorkController::class, 'update'])->name('community-how-works.update');
    // for community plannings
    Route::get('/community-planning', [CommunityPlanningController::class, 'index'])->name('community-planning.index');
    Route::put('/community-planning', [CommunityPlanningController::class, 'update'])->name('community-planning.update');
    // for community activity
    Route::get('/community-activity', [CommunityActivityController::class, 'index'])->name('community-activity.index');
    Route::put('/community-activity', [CommunityActivityController::class, 'update'])->name('community-activity.update');
    // for community benefit
    Route::get('/community-benefit', [CommunityBenefitController::class, 'index'])->name('community-benefit.index');
    Route::put('/community-benefit/{section}', [CommunityBenefitController::class, 'update'])->name('community-benefit.update');
    // for community approach
    Route::get('/community-approach', [CommunityApproachSectionController::class, 'index'])->name('community-approach.index');
    Route::put('/community-approach', [CommunityApproachSectionController::class, 'update'])->name('community-approach.update');
    // for community service
    Route::get('/community-approach', [CommunityApproachSectionController::class, 'index'])->name('community-approach.index');
    Route::put('/community-approach', [CommunityApproachSectionController::class, 'update'])->name('community-approach.update');
    // community services
    Route::get('/community-service', [CommunityServiceController::class, 'index'])->name('community-service.index');
    Route::post('/community-service', [CommunityServiceController::class, 'store'])->name('community-service.store');
    Route::put('/community-service/{card}', [CommunityServiceController::class, 'update'])->name('community-service.update');
    Route::delete('/community-service/{card}', [CommunityServiceController::class, 'destroy'])->name('community-service.destroy');
    // community services
    Route::get('/community-support', [CommunitySupportController::class, 'index'])->name('community-support.index');
    Route::post('/community-support', [CommunitySupportController::class, 'store'])->name('community-support.store');
    Route::put('/community-support/{card}', [CommunitySupportController::class, 'update'])->name('community-support.update');
    Route::delete('/community-support/{card}', [CommunitySupportController::class, 'destroy'])->name('community-support.destroy');
    // for support service 
    Route::get('/independent-accommodation', [IndependentAccommodationController::class, 'index'])
        ->name('independent-accommodation.index');
    Route::post('/independent-accommodation', [IndependentAccommodationController::class, 'store'])
        ->name('independent-accommodation.store');
    Route::put('/independent-accommodation/{card}', [IndependentAccommodationController::class, 'update'])
        ->name('independent-accommodation.update');
    Route::delete('/independent-accommodation/{card}', [IndependentAccommodationController::class, 'destroy'])
        ->name('independent-accommodation.destroy');
    Route::post('/independent-accommodation/delete-selected', [IndependentAccommodationController::class, 'deleteSelected'])
        ->name('independent-accommodation.delete-selected');
    // for gallery
    Route::get('/accommodation-gallery', [AccommodationGalleryController::class, 'index'])
        ->name('accommodation-gallery.index');
    Route::post('/accommodation-gallery', [AccommodationGalleryController::class, 'store'])
        ->name('accommodation-gallery.store');
    Route::put('/accommodation-gallery/{card}', [AccommodationGalleryController::class, 'update'])
        ->name('accommodation-gallery.update');
    Route::delete('/accommodation-gallery/{card}', [AccommodationGalleryController::class, 'destroy'])
        ->name('accommodation-gallery.destroy');
    Route::post('/accommodation-gallery/delete-selected', [AccommodationGalleryController::class, 'deleteSelected'])
        ->name('accommodation-gallery.delete-selected');
    // for gallery
    Route::get('/accommodation-faq', [AccomodationFaqController::class, 'index'])
        ->name('accommodation-faq.index');
    Route::post('/accommodation-faq', [AccomodationFaqController::class, 'store'])
        ->name('accommodation-faq.store');
    Route::put('/accommodation-faq/{card}', [AccomodationFaqController::class, 'update'])
        ->name('accommodation-faq.update');
    Route::delete('/accommodation-faq/{card}', [AccomodationFaqController::class, 'destroy'])
        ->name('accommodation-faq.destroy');
    Route::post('/accommodation-faq/delete-selected', [AccomodationFaqController::class, 'deleteSelected'])
        ->name('accommodation-faq.delete-selected');










// 👥 MLM Users - Simple CRUD
Route::get('/mlm-users', [MLMUserController::class, 'index'])->name('mlm-users.index');
Route::post('/mlm-users', [MLMUserController::class, 'store'])->name('mlm-users.store');
Route::put('/mlm-users/{id}', [MLMUserController::class, 'update'])->name('mlm-users.update');
Route::delete('/mlm-users/{id}', [MLMUserController::class, 'destroy'])->name('mlm-users.destroy');

// ✉️ Activation
Route::get('/activate/{token}', [MLMUserController::class, 'activate'])->name('mlm.activate');
Route::post('/mlm-users/{id}/resend-activation', [MLMUserController::class, 'resendActivation'])->name('mlm-users.resend-activation');

// 🌳 Holding Tank & Tree Placement
Route::get('/holding-tank', [MLMUserController::class, 'holdingTank'])->name('holding-tank');
Route::post('/holding-tank/place', [MLMUserController::class, 'placeUser'])->name('holding-tank.place');

// 📦 Orders (MLMOrderController)
Route::get('/mlm-users/{user}/create-order', [MLMOrderController::class, 'create'])->name('mlm-users.create-order');
Route::post('/mlm-users/create-order', [MLMOrderController::class, 'store'])->name('mlm-users.store-order');


// 💰 Payouts - ✅ FIXED: Point to MLMPayoutController
Route::get('/mlm-users/payout', [MLMPayoutController::class, 'dashboard'])->name('mlm-users.payout');
Route::get('/mlm-users/payout-request', [MLMPayoutController::class, 'payoutRequest'])->name('mlm-users.payout-request');
Route::put('/mlm-users/payout-request/{id}', [MLMPayoutController::class, 'updatePayoutRequest'])->name('mlm-users.update-payout-request');
Route::get('/mlm-users/payout-summary', [MLMPayoutController::class, 'payoutSummary'])->name('mlm-users.payout-summary');
Route::get('/mlm-users/payout-transfer-history', [MLMPayoutController::class, 'payoutTransferHistory'])->name('mlm-users.payout-transfer-history');
Route::get('/mlm-users/payout/{userId}', [MLMPayoutController::class, 'details'])->name('mlm-users.payout.details');
Route::post('/mlm-users/withdraw', [MLMPayoutController::class, 'withdraw'])->name('mlm-users.withdraw');
Route::post('/mlm-users/withdrawal/{transactionId}/{action}', [MLMPayoutController::class, 'approveWithdrawal'])->name('mlm-users.withdrawal.approve');

// 💸 Withdrawals
Route::post('/mlm-users/withdraw', [MLMUserController::class, 'processWithdrawal'])->name('mlm-users.withdraw');
Route::post('/mlm-users/withdrawal/{transactionId}/{action}', [MLMUserController::class, 'approveWithdrawal'])->name('mlm-users.withdrawal.approve');

// 🗑️ Recycle Bin
Route::get('/recycle-bin', [MLMUserController::class, 'recycleBin'])->name('recycle-bin');
Route::post('/recycle-bin/{id}/restore', [MLMUserController::class, 'restore'])->name('recycle-bin.restore');
Route::post('/recycle-bin/{id}/permanent', [MLMUserController::class, 'permanentDelete'])->name('recycle-bin.permanent');
Route::post('/recycle-bin/bulk-restore', [MLMUserController::class, 'bulkRestore'])->name('recycle-bin.bulk-restore');
Route::post('/recycle-bin/bulk-permanent-delete', [MLMUserController::class, 'bulkPermanentDelete'])->name('recycle-bin.bulk-permanent-delete');

// 🧬 Team Genealogy Routes - Individual definitions
Route::get('/team-genealogy', [TeamGenealogyController::class, 'genealogyView'])->name('team-genealogy.index');

// ✅ ADD /team-genealogy prefix to ALL routes
Route::get('/team-genealogy/user/{userId}/modal', [TeamGenealogyController::class, 'showProfileModal'])
    ->name('team-genealogy.user.modal');

Route::get('/team-genealogy/downline/{userId}', [TeamGenealogyController::class, 'showUserDownline'])
    ->name('team-genealogy.downline');


// 👥 Team Downline - Table View
Route::get('/team-downline', [TeamGenealogyController::class, 'downlineView'])->name('team-downline.index');
Route::get('/team-downline/{userId}/genealogy', [TeamGenealogyController::class, 'viewGenealogy'])->name('team-downline.genealogy');
Route::get('/team-downline/{userId}/referral-tree', [TeamGenealogyController::class, 'viewReferralTree'])->name('team-downline.referral-tree');
// 👥 Referral Genealogy (Card View)
Route::get('/referral-genealogy', [MLMTreeController::class, 'referralIndex'])->name('referral-genealogy.index');
Route::get('/referral-genealogy/user/{userId}', [MLMTreeController::class, 'referralProfile'])->name('referral-genealogy.profile');

Route::get('/referral-downline', [ReferralDownlineController::class, 'index'])->name('referral-downline.index');
Route::get('/referral-downline/{userId}/genealogy', [ReferralDownlineController::class, 'viewGenealogy'])->name('referral-downline.genealogy');
Route::get('/referral-downline/{userId}/referral-tree', [ReferralDownlineController::class, 'viewReferralTree'])->name('referral-downline.referral-tree');





// 📦 Products API
Route::get('/api/products/list', [ProductController::class, 'getProductsList'])->name('api.products.list');

// ⚙️ CC Settings
Route::get('/cc-settings', [CCSettingController::class, 'index'])->name('cc-settings.index');
Route::put('/cc-settings/{ccSetting}', [CCSettingController::class, 'update'])->name('cc-settings.update');
Route::delete('/cc-settings/{ccSetting}', [CCSettingController::class, 'destroy'])->name('cc-settings.destroy');

// Bank account Settings
Route::get('/bank-account-settings', [BankAccountSettingController::class, 'index'])->name('bank-account-settings.index');
Route::put('/bank-account-settings/{bankAccountSetting}', [BankAccountSettingController::class, 'update'])->name('bank-account-settings.update');
Route::delete('/bank-account-settings/{bankAccountSetting}', [BankAccountSettingController::class, 'destroy'])->name('bank-account-settings.destroy');



// Wallet Management
Route::resource('wallets', WalletController::class);
Route::get('/wallets/{wallet}/payout-config', [WalletController::class, 'payoutConfig'])
    ->name('wallets.payout-config');
Route::post('/wallets/{wallet}/payout-config', [WalletController::class, 'updatePayoutConfig'])
    ->name('wallets.update-payout-config');
Route::get('/wallets/{wallet}/charges', [WalletController::class, 'charges'])
    ->name('wallets.charges');
Route::post('/wallets/{wallet}/charges', [WalletController::class, 'updateCharges'])
    ->name('wallets.update-charges');
Route::post('/wallets/{wallet}/sync', [WalletController::class, 'syncWallet'])
    ->name('wallets.sync');
Route::post('/wallets/{wallet}/assign-user', [WalletController::class, 'assignUser'])
    ->name('wallets.assign-user');
Route::delete('/wallets/{wallet}/remove-user', [WalletController::class, 'removeUser'])
    ->name('wallets.remove-user');

    Route::get('/financial-overview', [WalletController::class, 'financialOverview'])
    ->name('wallets.financial-overview');
Route::get('/commission-wallet', [WalletController::class, 'commissionWallet'])
    ->name('wallets.commission-wallet');
    Route::get('/purchase-wallet', [WalletController::class, 'purchaseWallet'])
    ->name('wallets.purchase-wallet');

Route::get('/pending-earnings', [WalletController::class, 'pendingEarnings'])
    ->name('wallets.pending-earnings');
    Route::get('/bonus-history', [WalletController::class, 'bonusHistory'])
    ->name('wallets.bonus-history');
    Route::get('/cc-logs', [WalletController::class, 'ccLogs'])
    ->name('wallets.cc-logs');
    Route::get('/pair-matching-logs', [WalletController::class, 'pairMatchingLogs'])
    ->name('wallets.pair-matching-logs');
});




require __DIR__ . '/auth.php';
