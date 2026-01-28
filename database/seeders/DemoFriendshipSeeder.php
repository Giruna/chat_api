<?php

namespace Database\Seeders;

use App\Models\Friendship;
use Illuminate\Database\Seeder;

class DemoFriendshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $friendships = [
            ['id' => 1,  'sender_id' => 11, 'receiver_id' => 10, 'status' => 'accepted'],
            ['id' => 2,  'sender_id' => 11, 'receiver_id' => 12, 'status' => 'accepted'],
            ['id' => 3,  'sender_id' => 11, 'receiver_id' => 13, 'status' => 'accepted'],
            ['id' => 4,  'sender_id' => 3,  'receiver_id' => 1,  'status' => 'accepted'],
            ['id' => 5,  'sender_id' => 1,  'receiver_id' => 11, 'status' => 'accepted'],
            ['id' => 6,  'sender_id' => 15, 'receiver_id' => 1,  'status' => 'accepted'],
            ['id' => 7, 'sender_id' => 2,  'receiver_id' => 1,  'status' => 'rejected'],
            ['id' => 8, 'sender_id' => 10, 'receiver_id' => 1,  'status' => 'accepted'],
            ['id' => 9, 'sender_id' => 3,  'receiver_id' => 11, 'status' => 'pending'],
            ['id' => 10, 'sender_id' => 1,  'receiver_id' => 12, 'status' => 'pending'],
            ['id' => 11, 'sender_id' => 4,  'receiver_id' => 1,  'status' => 'pending'],
            ['id' => 12, 'sender_id' => 8,  'receiver_id' => 1,  'status' => 'pending'],
            ['id' => 13, 'sender_id' => 1,  'receiver_id' => 5,  'status' => 'pending'],
            ['id' => 14, 'sender_id' => 6,  'receiver_id' => 1,  'status' => 'accepted'],
            ['id' => 15, 'sender_id' => 14, 'receiver_id' => 1,  'status' => 'accepted'],
            ['id' => 16, 'sender_id' => 9,  'receiver_id' => 1,  'status' => 'accepted'],
            ['id' => 17, 'sender_id' => 7,  'receiver_id' => 1,  'status' => 'rejected'],
        ];

        foreach ($friendships as $friendship) {
            Friendship::updateOrCreate(
                ['id' => $friendship['id']],
                [
                    'sender_id' => $friendship['sender_id'],
                    'receiver_id' => $friendship['receiver_id'],
                    'status' => $friendship['status'],
                ]
            );
        }
    }
}
