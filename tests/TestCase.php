<?php


namespace StackTrace\Inertia\Tests;


use Illuminate\Support\Facades\View;
use Inertia\Inertia;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function setUp(): void
    {
        parent::setUp();

        View::addLocation(__DIR__.'/Stubs');
        Inertia::setRootView('welcome');
        config()->set('inertia.testing.ensure_pages_exist', false);
        config()->set('inertia.testing.page_paths', [realpath(__DIR__)]);
    }

    protected function getPackageProviders($app)
    {
        return [
            \Inertia\ServiceProvider::class,
            \StackTrace\Inertia\ServiceProvider::class,
        ];
    }
}
