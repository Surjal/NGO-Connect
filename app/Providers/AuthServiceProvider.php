<?php

namespace App\Providers;

use App\Models\Event;
use App\Models\Ngo;
use App\Models\Post;
use App\Policies\EventPolicy;
use App\Policies\NgoPolicy;
use App\Policies\PostPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Post::class => PostPolicy::class,
        Event::class => EventPolicy::class,
        Ngo::class => NgoPolicy::class,
    ];

    public function boot(): void
    {
        //
    }
}
