<?php

use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Route;
use Inertia\Controller;
use Inertia\Testing\AssertableInertia as Assert;
use StackTrace\Inertia\Notification;
use StackTrace\Inertia\NotificationManager;
use StackTrace\Inertia\Tests\Stubs\ExampleMiddleware;
use function Pest\Laravel\followingRedirects;
use function Pest\Laravel\get;

it('should push notification to the stack', function () {
    $manager = new NotificationManager;

    expect($manager->all())->toBeEmpty();

    $manager->push(Notification::positive('Test'));

    expect($manager->all())->toHaveCount(1);
});

it('should push notification array to the stack', function () {
    $manager = new NotificationManager();

    expect($manager->all())->toBeEmpty();

    $manager->push([
        'title' => 'This is title'
    ]);

    expect($manager->all())->toHaveCount(1);
});

it('should format notification to array', function () {
    $notification = new Notification('warning', 'This is title', 'This is content', ['custom' => 'value']);

    expect($notification->toArray())->toMatchArray([
        'type' => 'warning',
        'title' => 'This is title',
        'content' =>'This is content',
        'custom' => 'value',
    ]);
});

it('should add notifications to the response', function () {
    Route::middleware([StartSession::class, ExampleMiddleware::class])
        ->get('/', function () {
            Notification::positive('Serus')->push();

            return \Inertia\Inertia::render('Home');
        });

    get('/')->assertInertia(function (Assert $page) {
        $page
            ->has('notifications.default', 1)
            ->has('notifications.default.0', fn (Assert $notification) => $notification
                ->has('id')
                ->where('value.type', 'positive')
                ->where('value.title', 'Serus')
                ->has('timestamp')
                ->where('autoDismiss', true)
                ->etc()
        );
    });
});

it('should retain notifications when redirected', function () {
    Route::middleware([StartSession::class, ExampleMiddleware::class])
        ->get('/', function () {
            Notification::positive('Serus')->push();

            return redirect()->to('/redirect');
        });

    Route::middleware([StartSession::class, ExampleMiddleware::class])
        ->get('/redirect', Controller::class)
        ->defaults('component', 'Home')
        ->defaults('props', []);

    followingRedirects()->get('/')->assertInertia(function (Assert $page) {
        $page
            ->has('notifications.default', 1)
            ->has('notifications.default.0', fn (Assert $notification) => $notification
                ->has('id')
                ->where('value.type', 'positive')
                ->where('value.title', 'Serus')
                ->has('timestamp')
                ->where('autoDismiss', true)
                ->etc()
            );
    });

    get('/redirect')->assertInertia(fn (Assert $page) => $page->where('notifications', []));
});
