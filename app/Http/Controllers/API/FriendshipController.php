<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\FriendshipService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class FriendshipController extends Controller
{
    protected FriendshipService $friendshipService;

    public function __construct(FriendshipService $friendshipService)
    {
        $this->friendshipService = $friendshipService;
    }

    /**
     * @param Request $request
     * @param int $receiverId
     * @return JsonResponse
     */
    public function sendRequest(Request $request, int $receiverId): JsonResponse
    {
        return $this->friendshipService->sendRequest($request->user(), $receiverId);
    }

    /**
     * @param Request $request
     * @param int $senderId
     * @return JsonResponse
     */
    public function acceptRequest(Request $request, int $senderId): JsonResponse
    {
        return $this->friendshipService->acceptRequest($request->user(), $senderId);
    }
}
