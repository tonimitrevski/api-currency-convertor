<?php

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->create([
            'first_name' => 'Toni',
            'last_name' => 'Mitrevski',
            'password' => bcrypt('toni1234'),
            'email' => 'toni@stativa.com.mk',
            'last_login_at' => Carbon::now()->minute(0)->second(0)->toDateTimeString(),
        ]);
    }
}
