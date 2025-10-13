<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\HasApiTokens;

/**
 * @method static Builder activeExcludingUser(int $excludeUserId)
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $friend_status
 */
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * @param int $id
     * @return self|null
     */
    public static function getById(int $id): ?self
    {
        return static::find($id);
    }

    /**
     * @param string $email
     * @return self|null
     */
    public static function findByEmail(string $email): ?self
    {
        return self::where('email', $email)->first();
    }

    /**
     * Scope: only active users excluding given user, optional search by name/email
     *
     * @param Builder $query
     * @param int $excludedUserId
     * @param string|null $searchTerm
     * @return Builder
     */
    public function scopeActiveExcludingUser(Builder $query, int $excludedUserId, ?string $searchTerm = null): Builder
    {
        return $query->where('id', '!=', $excludedUserId)
            ->whereNotNull('email_verified_at')
            ->when($searchTerm, function (Builder $query) use ($searchTerm) {
                $query->where(function (Builder $subQuery) use ($searchTerm) {
                    $subQuery->where('name', 'like', "%{$searchTerm}%");
                });
            });
    }

    public function sentFriendRequests(): HasMany
    {
        return $this->hasMany(Friendship::class, 'sender_id');
    }

    public function receivedFriendRequests(): HasMany
    {
        return $this->hasMany(Friendship::class, 'receiver_id');
    }

    public function friends(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'friendships',
            'sender_id',
            'receiver_id'
        )->wherePivot('status', 'accepted');
    }
}
