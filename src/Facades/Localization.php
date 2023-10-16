<?php


namespace StackTrace\Inertia\Facades;


use Illuminate\Support\Facades\Facade;

class Localization extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'inertia.localization';
    }
}
