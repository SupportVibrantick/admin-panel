<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    public function run()
    {
        // Only view permissions for sidebar access
        $permissions = [
            // Dashboard Permissions
            ['name' => 'view-dashboard', 'guard_name' => 'web'],
            
            // Appointment Permissions
            ['name' => 'view-appointments', 'guard_name' => 'web'],
            // for application
            ['name' => 'view-applications', 'guard_name' => 'web'],

            
            // Booking Leads Permissions
            ['name' => 'view-booking-leads', 'guard_name' => 'web'],
            
            // Contact Leads Permissions
            ['name' => 'view-contact-leads', 'guard_name' => 'web'],
            
            // Health Package Permissions
            ['name' => 'manage-tests', 'guard_name' => 'web'],
            ['name' => 'manage-parameters', 'guard_name' => 'web'],
            ['name' => 'manage-health-risks', 'guard_name' => 'web'],
            ['name' => 'manage-health-packages', 'guard_name' => 'web'],
            ['name' => 'manage-faqs', 'guard_name' => 'web'],
            
            // CMS Permissions
            ['name' => 'manage-ads', 'guard_name' => 'web'],
            ['name' => 'manage-blog-categories', 'guard_name' => 'web'],
            ['name' => 'manage-blogs', 'guard_name' => 'web'],
            ['name' => 'manage-slider', 'guard_name' => 'web'],
            ['name' => 'manage-about', 'guard_name' => 'web'],
            ['name' => 'manage-why-choose-us', 'guard_name' => 'web'],
            ['name' => 'manage-accreditations', 'guard_name' => 'web'],
            ['name' => 'manage-gallery', 'guard_name' => 'web'],
            ['name' => 'manage-testimonials', 'guard_name' => 'web'],
            ['name' => 'manage-know-us', 'guard_name' => 'web'],
            ['name' => 'manage-counter', 'guard_name' => 'web'],
            ['name' => 'manage-what-makes-different', 'guard_name' => 'web'],
            ['name' => 'manage-partners', 'guard_name' => 'web'],
            ['name' => 'manage-partner-images', 'guard_name' => 'web'],
            ['name' => 'manage-why-partners', 'guard_name' => 'web'],
            ['name' => 'manage-corporate-benefits', 'guard_name' => 'web'],
            ['name' => 'manage-corporate-services', 'guard_name' => 'web'],
            ['name' => 'manage-job-career', 'guard_name' => 'web'],
            ['name' => 'manage-privacy-policy', 'guard_name' => 'web'],
            ['name' => 'manage-terms-conditions', 'guard_name' => 'web'],
            
            // Profile Permission
            ['name' => 'manage-profile', 'guard_name' => 'web'],
            
            // Settings Permissions
            ['name' => 'manage-general-settings', 'guard_name' => 'web'],
            ['name' => 'manage-system-settings', 'guard_name' => 'web'],
            ['name' => 'manage-seo-settings', 'guard_name' => 'web'],
            ['name' => 'manage-website-settings', 'guard_name' => 'web'],
            
            // Staff Permissions
            ['name' => 'manage-doctors', 'guard_name' => 'web'],
            
            // Services Permissions
            ['name' => 'manage-services', 'guard_name' => 'web'],
            
            // Packages Permissions
            ['name' => 'manage-packages', 'guard_name' => 'web'],
            
            // SEO Permissions
            ['name' => 'manage-seo-pages', 'guard_name' => 'web'],
            
            // User Management Permissions
            ['name' => 'manage-users', 'guard_name' => 'web'],
            ['name' => 'manage-roles', 'guard_name' => 'web'],
            ['name' => 'manage-permissions', 'guard_name' => 'web'],
        ];

        foreach ($permissions as $permissionData) {
            // This won't throw error if permission exists
            Permission::firstOrCreate(
                ['name' => $permissionData['name'], 'guard_name' => $permissionData['guard_name']],
                $permissionData
            );
        }

        // Create Admin Role if it doesn't exist
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);
        $mlmUserRole = Role::firstOrCreate(['name' => 'mlm_user', 'guard_name' => 'web']);
        $seoManagerRole = Role::firstOrCreate(['name' => 'seo_manager', 'guard_name' => 'web']);
        
        // Assign all permissions to admin role
        $adminRole->syncPermissions(Permission::all());
        
        $this->command->info('View permissions for sidebar seeded successfully.');
    }
}