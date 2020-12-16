<?php


use Illuminate\Database\Seeder;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeders.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
        \DB::table('users')->insert([
            'name' => "test",
            'email' => 'test@test.com',
            'password' => Hash::make('123456'),
        ]);
    }
}
