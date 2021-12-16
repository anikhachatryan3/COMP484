<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // roles
        $adminRole = Role::factory()->create([
            'name' => 'admin',
        ]);
        $standardRole = Role::factory()->create([
            'name' => 'standard',
        ]);

        // users
        $adminUser = User::factory()->create([
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role_id' => $adminRole->id,
        ]);

        $standardUser = User::factory()->create([
            'username' => 'user1',
            'email' => 'user1@gmail.com',
            'password' => Hash::make('password'),
            'role_id' => $standardRole->id,
        ]);

        // posts
        $post1 = Post::factory()->create([
            'user_id' => $adminUser,
        ]);

        $post2 = Post::factory()->create([
            'user_id' => $standardUser,
        ]);

        // comments
        $comment1 = Comment::factory()->create([
            'user_id' => $adminUser,
            'post_id' => $post2->id,
        ]);

        $comment2 = Comment::factory()->create([
            'user_id' => $standardUser,
            'post_id' => $post1->id,
        ]);

    }
}
