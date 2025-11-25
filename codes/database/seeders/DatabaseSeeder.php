<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PermissionsSeeder::class);

        // Create Superadmin
        \App\Models\User::factory()->create([
            'name' => 'Super Admin',
            'email' => 'admin@example.com',
            'is_superadmin' => true,
        ]);

        // Create Farms
        $farmA = \App\Models\Farm::create(['name' => 'Farm A', 'slug' => 'farm-a']);
        $farmB = \App\Models\Farm::create(['name' => 'Farm B', 'slug' => 'farm-b']);

        // Create Manager Role for Farm A
        $managerRole = \App\Models\Role::create(['farm_id' => $farmA->id, 'name' => 'Manager', 'slug' => 'manager']);
        $managerPerms = config('permissions.roles.manager.permissions');
        $managerRole->permissions()->sync(\App\Models\Permission::whereIn('slug', $managerPerms)->get());

        // Create Manager User
        $manager = \App\Models\User::factory()->create([
            'name' => 'Manager A',
            'email' => 'manager@farma.com',
        ]);
        
        $manager->farms()->attach($farmA->id, ['role_id' => $managerRole->id]);
        
        // Create Harvests for Farm A
        \App\Models\Harvest::create([
            'farm_id' => $farmA->id,
            'crop_name' => 'Wheat',
            'quantity' => 1000,
            'harvested_at' => now(),
        ]);
        
        // Create Harvests for Farm B (should not be visible to Manager A)
        \App\Models\Harvest::create([
            'farm_id' => $farmB->id,
            'crop_name' => 'Corn',
            'quantity' => 500,
            'harvested_at' => now(),
        ]);
    }
}
