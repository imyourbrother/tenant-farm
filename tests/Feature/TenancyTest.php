<?php

namespace Tests\Feature;

use App\Models\Farm;
use App\Models\Harvest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenancyTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_can_only_see_own_farm_harvests()
    {
        // Setup
        $farmA = Farm::create(['name' => 'Farm A', 'slug' => 'farm-a']);
        $farmB = Farm::create(['name' => 'Farm B', 'slug' => 'farm-b']);

        $role = \App\Models\Role::create(['farm_id' => $farmA->id, 'name' => 'Manager', 'slug' => 'manager']);

        $manager = User::factory()->create();
        $manager->farms()->attach($farmA->id, ['role_id' => $role->id]);

        $harvestA = Harvest::create(['farm_id' => $farmA->id, 'crop_name' => 'Wheat', 'quantity' => 100, 'harvested_at' => now()]);
        $harvestB = Harvest::create(['farm_id' => $farmB->id, 'crop_name' => 'Corn', 'quantity' => 100, 'harvested_at' => now()]);

        // Act
        $this->actingAs($manager)
             ->withSession(['farm_id' => $farmA->id]);
             
        $harvests = Harvest::all();

        // Assert
        $this->assertTrue($harvests->contains($harvestA));
        $this->assertFalse($harvests->contains($harvestB));
        $this->assertCount(1, $harvests);
    }

    public function test_superadmin_can_see_all_without_session()
    {
        // Setup
        $farmA = Farm::create(['name' => 'Farm A', 'slug' => 'farm-a']);
        $harvestA = Harvest::create(['farm_id' => $farmA->id, 'crop_name' => 'Wheat', 'quantity' => 100, 'harvested_at' => now()]);
        
        $superadmin = User::factory()->create(['is_superadmin' => true]);

        // Act
        $this->actingAs($superadmin); // No session farm_id
        
        $harvests = Harvest::all();

        // Assert
        $this->assertTrue($harvests->contains($harvestA));
    }
}
