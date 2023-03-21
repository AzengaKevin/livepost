<?php

namespace Database\Seeders;

use App\Models\Comment;
use Illuminate\Database\Seeder;
use Database\Seeders\Traits\TruncateTable;
use Database\Seeders\Traits\ToggleForeignKeyChecks;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CommentSeeder extends Seeder
{
    use TruncateTable, ToggleForeignKeyChecks;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->disableForeignKeyChecks();

        $this->truncate("comments");

        Comment::factory(10)->randomUser()->randomPost()->create();
        
        $this->enableForeignKeyChecks();
        
    }
}
