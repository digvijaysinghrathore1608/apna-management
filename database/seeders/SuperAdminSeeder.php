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
        // Create Super Admin Role
        $role = Role::firstOrCreate(['name' => 'super_admin']);

        // Create all services (add your actual service names here)
        $services = ['default'];
        $serviceIds = [];
        foreach ($services as $serviceName) {
            $service = Service::firstOrCreate(['name' => $serviceName]);
            $serviceIds[] = $service->id;
        }

        // Create Super Admin User
        $user = User::firstOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('superadmin123'),
                'status' => 'active',
                'is_banned' => false,
            ]
        );

        // Assign Super Admin role to all services
        foreach ($serviceIds as $serviceId) {
            UserServiceRole::firstOrCreate([
                'user_id' => $user->id,
                'service_id' => $serviceId,
                'role_id' => $role->id,
            ]);
        }
    }
}
