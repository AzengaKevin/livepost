<?php

namespace Database\Seeders;

use App\Models\User;
use Database\Seeders\Traits\ToggleForeignKeyChecks;
use Database\Seeders\Traits\TruncateTable;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    use TruncateTable, ToggleForeignKeyChecks;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->disableForeignKeyChecks();

        $this->truncate("users");

        User::factory(10)->create();
        
        $this->enableForeignKeyChecks();
    }
}
