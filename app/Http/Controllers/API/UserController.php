<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function usersList(Request $request): JsonResponse
    {
        $authenticatedUser = $request->user();

        $searchTerm = $request->query('search');
        $itemsPerPage = $request->query('per_page', 10);

        $users = $this->userService->getActiveUsersWithFriendStatus(
            $authenticatedUser->id,
            $searchTerm,
            $itemsPerPage
        );

        return response()->json(array_merge(['success' => true], $users->toArray()));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function friendsList(Request $request): JsonResponse
    {
        $authenticatedUser = $request->user();

        $friends = $this->userService->getFriends($authenticatedUser->id);

        return response()->json(
            array_merge(
                ['success' => true],
                ['data' => $friends->map(function($friend) {
                    return $friend->toArray();
                })->toArray()]
            )
        );
    }
}
