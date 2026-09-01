<?php
namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'admin@carepoint.test'],
            ['name' => 'CarePoint Administrator', 'password' => Hash::make('Admin@12345'), 'role' => 'admin']
        );
    }
}