<?php

namespace App\Services;

use App\Models\Message;
use App\Models\Friendship;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;

class MessageService
{
    /**
     * @param int $receiverId
     * @param string $text
     * @return JsonResponse
     */
    public function sendMessage(int $receiverId, string $text): JsonResponse
    {
        $senderId = Auth::id();

        if (!Friendship::areUsersFriends($senderId, $receiverId)) {
            return response()->json([
                'message' => 'You can only send messages to friends.'
            ], 403);
        }

        $message = Message::storeMessage($senderId, $receiverId, $text);

        return response()->json([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'message' => 'Message sent successfully.',
            'data' => $message,
        ]);
    }

    /**
     * @param int $receiverId
     * @param int $perPage
     * @return JsonResponse
     */
    public function getConversation(int $receiverId, int $perPage = 20): JsonResponse
    {
        $senderId = Auth::id();

        if (!Friendship::areUsersFriends($senderId, $receiverId)) {
            return response()->json([
                'message' => 'You can only view conversations with friends.'
            ], 403);
        }

        $messages = Message::getConversationBetween($senderId, $receiverId, $perPage);

        return response()->json($messages);
    }
}
