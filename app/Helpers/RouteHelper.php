<?php

namespace App\Helpers;

class RouteHelper
{
    public static function includeRouteFiles(string $folder): void
    {
        $dirIterator = new \RecursiveDirectoryIterator($folder);

        /** @var \RecursiveDirectoryIterator|\RecursiveIteratorIterator */
        $iterator = new \RecursiveIteratorIterator($dirIterator);

        while ($iterator->valid()) {
            
            if(! $iterator->isDot() && $iterator->isFile() && $iterator->isReadable() && ($iterator->current()->getExtension() === 'php')){

                require $iterator->key();

            }

            $iterator->next();
        }
        
    }
}


