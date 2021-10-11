<?php

namespace Tests;

use App\Models\Users\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function authenticate()
    {
        $this->actingAs(User::factory()->create());
    }
}
