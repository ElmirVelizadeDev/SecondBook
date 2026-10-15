<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;

trait CreatesApplication
{
    /**
     * Creates the application.
     */
    public function createApplication(): Application
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        $this->serverVariables = array_merge($this->serverVariables, [
            'SCRIPT_NAME' => '/index.php',
            'PHP_SELF' => '/index.php',
        ]);

        \Illuminate\Support\Facades\URL::forceRootUrl('http://localhost');

        return $app;
    }
}
