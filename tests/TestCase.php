<?php

namespace Tests;

use App\Models\User;
use Carbon\Carbon;
use DB;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp()
    {
        parent::setUp();
        $this->clearCache();
        file_put_contents(base_path('storage/logs/laravel.log'), '');
        DB::statement(DB::raw('PRAGMA foreign_keys=1'));
    }

    protected function clearCache(): void
    {
        $this->artisan('config:clear');
        $this->artisan('cache:clear');
        $this->artisan('route:clear');
    }

    /**
     * @return mixed
     */
    protected function createTheUser()
    {
        return factory(User::class)->create([
            'first_name' => 'Toni',
            'last_name' => 'Mitrevski',
            'password' => bcrypt('toni1234'),
            'email' => 'toni@stativa.com.mk',
            'last_login_at' => Carbon::now()->minute(0)->second(0)->toDateTimeString(),
        ]);
    }
}
