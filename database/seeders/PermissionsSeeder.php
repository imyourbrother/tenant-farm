<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;

class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $config = Config::get('permissions.roles');

        foreach ($config as $roleSlug => $roleData) {
            // Create Role (Global roles for now, or we can seed farm-specific roles later)
            // For now, let's assume these are "template" roles or global system roles if farm_id is null
            // But the requirement says "Roles are specific to Farms".
            // So we might just seed the Permissions here, and Roles are created when a Farm is created?
            // Or we seed "System Roles" (Superadmin) and "Template Roles" (Manager/Worker) which are then copied/assigned?
            
            // Let's seed Permissions first
            foreach ($roleData['permissions'] as $permSlug) {
                Permission::firstOrCreate(
                    ['slug' => $permSlug],
                    ['name' => ucwords(str_replace('.', ' ', $permSlug))]
                );
            }
        }
        
        // Create Superadmin Role (Global)
        $superAdminRole = Role::firstOrCreate(
            ['slug' => 'superadmin', 'farm_id' => null],
            ['name' => 'Super Admin']
        );
        
        $superAdminPerms = $config['superadmin']['permissions'];
        $permissions = Permission::whereIn('slug', $superAdminPerms)->get();
        $superAdminRole->permissions()->sync($permissions);
    }
}
