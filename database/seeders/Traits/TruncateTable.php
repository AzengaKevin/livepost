<?php

namespace Database\Seeders\Traits;

use Illuminate\Support\Facades\DB;

/**
 * Truncate tables
 */
trait TruncateTable
{

    protected function truncate(string $tableName) : void
    {
        DB::table($tableName)->truncate();
        
    }
    
}


