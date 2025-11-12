<?php

namespace App\Services;

use App\Models\User;
use App\Models\Friendship;
use Exception;
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
                'success' => false,
                'message' => 'The user you are trying to add does not exist.'
            ], 404);
        }

        if (is_null($receiver->email_verified_at)) {
            return response()->json([
                'success' => false,
                'message' => 'The user is not active and cannot be added as a friend.'
            ], 400);
        }

        if (Friendship::getFriendRequestBetweenUsers($sender->id, $receiver->id)) {
            return response()->json([
                'success' => false,
                'message' => 'A friendship request or connection already exists with this user.'
            ], 400);
        }

        Friendship::createRequest($sender->id, $receiver->id);

        return response()->json([
            'success' => true,
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
        $pendingFriendRequest = $this->hasPendingFriendRequest($senderId, $receiver->id);
        if (!$pendingFriendRequest instanceof Friendship) {
            return $pendingFriendRequest;
        }

        $pendingFriendRequest->accept();

        return response()->json([
            'success' => true,
            'message' => 'Friendship request accepted.'
        ]);
    }

    /**
     * @param User $receiver
     * @param int $senderId
     * @return JsonResponse
     */
    public function rejectRequest(User $receiver, int $senderId): JsonResponse
    {
        $pendingFriendRequest = $this->hasPendingFriendRequest($senderId, $receiver->id);
        if (!$pendingFriendRequest instanceof Friendship) {
            return $pendingFriendRequest;
        }

        $pendingFriendRequest->reject();

        return response()->json([
            'success' => true,
            'message' => 'Friendship request rejected.'
        ]);
    }

    /**
     * @param int $senderId
     * @param int $receiverId
     * @return Friendship|JsonResponse
     */
    private function hasPendingFriendRequest (int $senderId, int $receiverId): Friendship|JsonResponse
    {
        $friendship = Friendship::getPendingFriendRequest($senderId, $receiverId);

        if (!$friendship) {
            return response()->json([
                'success' => false,
                'message' => 'No pending friendship request found from this user.'
            ], 404);
        }

        return $friendship;
    }

    /**
     * @param User $receiver
     * @return JsonResponse
     */
    public function receivedFriendRequests (User $receiver): JsonResponse
    {
        try {
            $friendRequests = $receiver->receivedFriendRequests()
                ->with('sender')
                ->get()
                ->toArray();

            $senders = array_column($friendRequests, 'sender');

            return response()->json([
                'success' => true,
                'data' => $senders
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
