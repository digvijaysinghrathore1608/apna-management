<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Service;
use App\Models\UserServiceRole;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $roles = [
            'super_admin',
            'default',
        ];
        $roleIds = [];
        foreach ($roles as $roleName) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $roleIds[$roleName] = $role->id;
        }

        // Create services
        $services = [
            'welcome',
            'microfinance',
        ];
        $serviceIds = [];
        foreach ($services as $serviceName) {
            $service = Service::firstOrCreate(['name' => $serviceName]);
            $serviceIds[$serviceName] = $service->id;
        }

        // Create super admin user
        $user = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('superadmin123'),
                'status' => 'active',
                'is_banned' => false,
            ]
        );

        // Assign super_admin role to ALL services
        foreach ($serviceIds as $serviceId) {
            UserServiceRole::firstOrCreate([
                'user_id'    => $user->id,
                'service_id' => $serviceId,
                'role_id'    => $roleIds['super_admin'],
            ]);
        }
    }
}
