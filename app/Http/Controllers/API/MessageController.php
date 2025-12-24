<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MessageRequest;
use App\Services\MessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    private int $messagesPerPage = 20;

    protected MessageService $messageService;

    public function __construct(MessageService $messageService) {
        $this->messageService = $messageService;
    }

    /**
     * @param MessageRequest $request
     * @return JsonResponse
     */
    public function send(MessageRequest $request): JsonResponse
    {
        $validated = $request->validated();

        return $this->messageService->sendMessage($request->validated('receiver_id'), $validated['message']);
    }

    /**
     * @param Request $request
     * @param int $friendId
     * @return JsonResponse
     */
    public function conversation(Request $request, int $friendId): JsonResponse
    {
        $perPage = $request->query('per_page', $this->messagesPerPage);

        return $this->messageService->getConversation($friendId, $perPage);
    }
}
