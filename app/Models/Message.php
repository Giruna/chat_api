<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Pagination\LengthAwarePaginator;

class Message extends Model
{
    use SoftDeletes;

    protected $fillable = ['sender_id', 'receiver_id', 'message'];

    /**
     * @param int $senderId
     * @param int $receiverId
     * @param string $text
     * @return self
     */
    public static function storeMessage(int $senderId, int $receiverId, string $text): self
    {
        return self::create([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'message' => $text,
        ]);
    }

    /**
     * @param int $userId
     * @param int $friendId
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public static function getConversationBetween(int $userId, int $friendId, int $perPage = 20): LengthAwarePaginator
    {
        return self::query()
            ->where(function ($query) use ($userId, $friendId) {
                $query->where('sender_id', $userId)
                    ->where('receiver_id', $friendId);
            })
            ->orWhere(function ($query) use ($userId, $friendId) {
                $query->where('sender_id', $friendId)
                    ->where('receiver_id', $userId);
            })
            ->orderBy('created_at', 'asc')
            ->paginate($perPage);
    }

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
