<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Database\Seeders\Traits\TruncateTable;
use Database\Seeders\Traits\ToggleForeignKeyChecks;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PostSeeder extends Seeder
{
    use TruncateTable, ToggleForeignKeyChecks;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->disableForeignKeyChecks();

        $this->truncate("posts");

        $posts = Post::factory(10)->randomUser()->create();

        $posts->each(
            fn(Post $post) => 
            $post->users()->syncWithoutDetaching(User::inRandomOrder()->first())
        );
        
        $this->enableForeignKeyChecks();
    }
}
