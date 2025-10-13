<?php

namespace App\Services;

use App\Models\User;
use App\Models\Friendship;
use Illuminate\Http\JsonResponse;

class FriendshipService
{
    /**
     * Send a friend request.
     *
     * @param User $sender
     * @param int $receiverId
     * @return JsonResponse
     */
    public function sendRequest(User $sender, int $receiverId): JsonResponse
    {
        $receiver = User::getById($receiverId);

        if (!$receiver) {
            return response()->json([
                'message' => 'The user you are trying to add does not exist.'
            ], 404);
        }

        if (is_null($receiver->email_verified_at)) {
            return response()->json([
                'message' => 'The user is not active and cannot be added as a friend.'
            ], 400);
        }

        if (Friendship::getFriendRequestBetweenUsers($sender->id, $receiver->id)) {
            return response()->json([
                'message' => 'A friendship request or connection already exists with this user.'
            ], 400);
        }

        Friendship::createRequest($sender->id, $receiver->id);

        return response()->json([
            'message' => 'Friendship request sent successfully.'
        ]);
    }

    /**
     * Accept a friend request.
     *
     * @param User $receiver
     * @param int $senderId
     * @return JsonResponse
     */
    public function acceptRequest(User $receiver, int $senderId): JsonResponse
    {
        $friendship = Friendship::getPendingFriendRequest($senderId, $receiver->id);

        if (!$friendship) {
            return response()->json([
                'message' => 'No pending friendship request found from this user.'
            ], 404);
        }

        $friendship->accept();

        return response()->json([
            'message' => 'Friendship request accepted.'
        ]);
    }
}
