<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $status
 */
class Friendship extends Model
{
    use HasFactory;

    protected $fillable = [
        'sender_id',
        'receiver_id',
        'status',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_ACCEPTED = 'accepted';
    public const STATUS_REJECTED = 'rejected';

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    /**
     * Find an existing friendship or request between two users.
     *
     * @param int $userIdA
     * @param int $userIdB
     * @return self|null
     */
    public static function getFriendRequestBetweenUsers(int $userIdA, int $userIdB): ?self
    {
        return self::where(function (Builder $query) use ($userIdA, $userIdB) {
            $query->where('sender_id', $userIdA)
                ->where('receiver_id', $userIdB);
        })
            ->orWhere(function (Builder $query) use ($userIdA, $userIdB) {
                $query->where('sender_id', $userIdB)
                    ->where('receiver_id', $userIdA);
            })
            ->first();
    }

    /**
     * @param int $senderId
     * @param int $receiverId
     * @return self
     */
    public static function createRequest(int $senderId, int $receiverId): self
    {
        return self::create([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'status' => self::STATUS_PENDING,
        ]);
    }

    /**
     * @param int $senderId
     * @param int $receiverId
     * @return self|null
     */
    public static function getPendingFriendRequest(int $senderId, int $receiverId):?self
    {
        return self::where('sender_id', $senderId)
            ->where('receiver_id', $receiverId)
            ->where('status', self::STATUS_PENDING)
            ->first();
    }

    /**
     * @return bool
     */
    public function accept(): bool
    {
        $this->status = self::STATUS_ACCEPTED;

        return $this->save();
    }

    /**
     * @return bool
     */
    public function reject(): bool
    {
        $this->status = self::STATUS_REJECTED;

        return $this->save();
    }

    /**
     * @param int $senderId
     * @param int $receiverId
     * @return bool
     */
    public static function areUsersFriends(int $senderId, int $receiverId): bool
    {
        return self::query()
            ->where(function ($query) use ($senderId, $receiverId) {
                $query->where('sender_id', $senderId)
                    ->where('receiver_id', $receiverId);
            })
            ->orWhere(function ($query) use ($senderId, $receiverId) {
                $query->where('sender_id', $receiverId)
                    ->where('receiver_id', $senderId);
            })
            ->where('status', self::STATUS_ACCEPTED)
            ->exists();
    }
}
