<?php

namespace App\Services;

use App\Models\Message;
use App\Models\Friendship;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

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
                'success' => false,
                'message' => 'You can only send messages to friends.'
            ], 403);
        }

        try {
            Message::storeMessage($senderId, $receiverId, $text);
        } catch (Exception $exception) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while saving your message. Please try again later.',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Message sent successfully.',
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
                'success' => false,
                'message' => 'You can only view conversations with friends.'
            ], 403);
        }

        $messages = Message::getConversationBetween($senderId, $receiverId, $perPage);

        return response()->json(array_merge(
            ['success' => true],
            $messages->toArray()
        ));
    }
}
