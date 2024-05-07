<?php

namespace App\Providers;

use App\Marker;
use App\Markers\TerminalMarker;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(Marker::class, TerminalMarker::class);
    }
}
