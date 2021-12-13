<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

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
            'role_id' => Role::where('name', 'admin')->firstOrFail()->id,
        ]);

        $standardUser = User::factory()->create([
            'role_id' => Role::where('name', 'standard')->firstOrFail()->id,
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
