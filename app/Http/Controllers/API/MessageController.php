<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MessageRequest;
use App\Services\MessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    protected MessageService $messageService;

    public function __construct(MessageService $messageService) {
        $this->messageService = $messageService;
    }

    /**
     * @param MessageRequest $request
     * @param int $receiverId
     * @return JsonResponse
     */
    public function send(MessageRequest $request, int $receiverId): JsonResponse
    {
        $validated = $request->validated();

        return $this->messageService->sendMessage($receiverId, $validated['message']);
    }

    /**
     * @param Request $request
     * @param int $friendId
     * @return JsonResponse
     */
    public function conversation(Request $request, int $friendId): JsonResponse
    {
        $perPage = $request->query('per_page', 20);

        return $this->messageService->getConversation($friendId, $perPage);
    }
}
