<?php

namespace Database\Seeders;

use App\Models\Message;
use Illuminate\Database\Seeder;

class DemoMessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $messages = [
            ['sender_id' => 11, 'receiver_id' => 1,  'message' => 'Hi!'],
            ['sender_id' => 1,  'receiver_id' => 11, 'message' => 'Hello there.'],
            ['sender_id' => 1,  'receiver_id' => 11, 'message' => 'How are you?'],
            ['sender_id' => 11, 'receiver_id' => 1,  'message' => 'Thanks!'],
            ['sender_id' => 11, 'receiver_id' => 1,  'message' => 'See you soon.'],
            ['sender_id' => 11, 'receiver_id' => 12, 'message' => 'Can we talk later?'],
            ['sender_id' => 11, 'receiver_id' => 12, 'message' => 'I will get back to you shortly.'],
            ['sender_id' => 11, 'receiver_id' => 1,  'message' => 'This sounds good to me.'],
            ['sender_id' => 11, 'receiver_id' => 10, 'message' => 'Let me know what you think.'],
            ['sender_id' => 1,  'receiver_id' => 3,  'message' => 'I am currently working on it.'],
            ['sender_id' => 11, 'receiver_id' => 10, 'message' => 'Sorry for the late reply.'],
            ['sender_id' => 1,  'receiver_id' => 10, 'message' => 'That makes a lot of sense.'],
            ['sender_id' => 1,  'receiver_id' => 10, 'message' => 'We should definitely discuss this.'],
            ['sender_id' => 11, 'receiver_id' => 13, 'message' => 'I will check and update you.'],
            ['sender_id' => 1,  'receiver_id' => 3,  'message' => 'Everything looks fine on my end.'],
            ['sender_id' => 1,  'receiver_id' => 3,  'message' => 'Let’s schedule a meeting for tomorrow.'],
            ['sender_id' => 1,  'receiver_id' => 6,  'message' => 'I appreciate your quick response.'],
            ['sender_id' => 11, 'receiver_id' => 13, 'message' => 'This issue might take a bit longer to resolve.'],
            ['sender_id' => 1,  'receiver_id' => 15, 'message' => 'Please feel free to reach out if you have any further questions.'],
        ];

        foreach ($messages as $message) {
            Message::create([
                'sender_id' => $message['sender_id'],
                'receiver_id' => $message['receiver_id'],
                'message' => $message['message'],
            ]);
        }
    }
}
