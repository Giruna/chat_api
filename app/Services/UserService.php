<?php

namespace App\Services;

use App\Models\User;
use App\Models\Friendship;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class UserService
{
    /**
     * @param int $authenticatedUserId
     * @param string|null $searchTerm
     * @param int $itemsPerPage
     * @return LengthAwarePaginator
     */
    public function getActiveUsersWithFriendStatus(int $authenticatedUserId, ?string $searchTerm = null, int $itemsPerPage = 10): LengthAwarePaginator
    {
        $paginatedUsers = User::activeExcludingUser($authenticatedUserId, $searchTerm)
            ->paginate($itemsPerPage);

        $paginatedUsers->getCollection()->transform(function (User $user) use ($authenticatedUserId) {
            $friendRequest = Friendship::getFriendRequestBetweenUsers($authenticatedUserId, $user->id);
            $user->friend_status = $friendRequest?->status;
            return $user;
        });

        return $paginatedUsers;
    }

    /**
     * @param $userId
     * @return Collection
     */
    public function getFriends($userId): Collection
    {
        /** @var User $user */
        $user = User::find($userId);

        $sentFriends = $user->acceptedSentFriendRequests()->get();
        $receivedFriends = $user->acceptedReceivedFriendRequests()->get();

        return $sentFriends->merge($receivedFriends);
    }
}
