<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use RuntimeException;

abstract class TestCase extends BaseTestCase
{
    /**
     * A cached config (artisan optimize / config:cache) makes the app ignore
     * phpunit.xml's environment — including the sicv_test database override —
     * so RefreshDatabase would migrate:fresh the DEVELOPMENT database.
     * Refuse to run instead of silently wiping it.
     */
    protected function setUp(): void
    {
        parent::setUp();

        if (! $this->app->environment('testing')) {
            throw new RuntimeException(
                'Tests are not running in the [testing] environment — a cached config is '
                .'probably overriding phpunit.xml. Run [php artisan optimize:clear] and retry.'
            );
        }
    }
}
