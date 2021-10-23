<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use App\Models\{
    User,
    Language
};
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        User::query()->create([
            'name' => ['ar' => ' احمد', 'en' => 'ahmed'],
            'phone' => '010',
            'email' => 'test@test.test',
            'api_token' => 'test-token',
            'password' => Hash::make('password'),
            'email_verified_at' => Carbon::now()
        ] ,
        [
            'name' => ['ar' => 'محمد علي', 'en' => 'Mohamed Ali'],
            'phone' => '010',
            'email' => 'test1@test.test',
            'api_token' => 'test-token',
            'password' => Hash::make('password'),
            'email_verified_at' => Carbon::now()
        ]);
    }
}
