<?php
namespace Database\Seeders\Traits;

use Illuminate\Support\Facades\DB;

/**
 * Desable and Enable Foreign Key Checks
 */
trait ToggleForeignKeyChecks
{

    protected function disableForeignKeyChecks() : void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
    }

    protected function enableForeignKeyChecks() : void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }    
}
