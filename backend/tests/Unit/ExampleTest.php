<?php

namespace Tests\Unit;

use App\Models\Role;
use App\Models\User;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_example()
    {
        $this->assertTrue(true);
    }

    public function testUserRoleRelationship() {
        $role = Role::firstOrFail();
        $user = User::factory()->create([
            'role_id' => $role->id,
        ]);
        $this->assertEquals($role->id, $user->role->id);
    }
}
