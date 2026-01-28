<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            ['id' => 1,  'name' => 'Baxter'],
            ['id' => 2,  'name' => 'Elodie'],
            ['id' => 3,  'name' => 'Finn'],
            ['id' => 4,  'name' => 'Marlowe'],
            ['id' => 5,  'name' => 'Jasper'],
            ['id' => 6,  'name' => 'Tamsin'],
            ['id' => 7,  'name' => 'Cole'],
            ['id' => 8,  'name' => 'Rhea'],
            ['id' => 9,  'name' => 'Nolan'],
            ['id' => 10, 'name' => 'Briar'],
            ['id' => 11, 'name' => 'Theo'],
            ['id' => 12, 'name' => 'Quinn'],
            ['id' => 13, 'name' => 'Rowan'],
            ['id' => 14, 'name' => 'Skye'],
            ['id' => 15, 'name' => 'Miles'],
            ['id' => 16, 'name' => 'Poppy'],
            ['id' => 17, 'name' => 'Sawyer'],
            ['id' => 18, 'name' => 'Wren'],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['id' => $user['id']],
                [
                    'name' => $user['name'],
                    'email' => strtolower($user['name']) . '@gmail.com',
                    'email_verified_at' => Carbon::now(),
                    'password' => Hash::make('1234'),
                ]
            );
        }
    }
}
