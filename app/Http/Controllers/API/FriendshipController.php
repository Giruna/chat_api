<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FriendRequestActionsRequest;
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
     * @param FriendRequestActionsRequest $request
     * @return JsonResponse
     */
    public function sendRequest(FriendRequestActionsRequest $request): JsonResponse
    {
        return $this->friendshipService->sendRequest($request->user(), $request->validated('receiver_id'));
    }

    /**
     * @param FriendRequestActionsRequest $request
     * @return JsonResponse
     */
    public function acceptRequest(FriendRequestActionsRequest $request): JsonResponse
    {
        return $this->friendshipService->acceptRequest($request->user(), $request->validated('sender_id'));
    }

    /**
     * @param FriendRequestActionsRequest $request
     * @return JsonResponse
     */
    public function rejectRequest(FriendRequestActionsRequest $request): JsonResponse
    {
        return $this->friendshipService->rejectRequest($request->user(), $request->validated('sender_id'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function receivedFriendRequests(Request $request): JsonResponse
    {
        return $this->friendshipService->receivedFriendRequests($request->user());
    }
}
