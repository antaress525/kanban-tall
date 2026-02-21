<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Arr;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'color',
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

    public function boards(): HasMany {
        return $this->hasMany(Board::class);
    }

    public function emailChanges(): HasMany  {
        return $this->hasMany(EmailChange::class);
    }

    public function collaborativeBoards(): BelongsToMany {
        return $this->belongsToMany(Board::class);

    }

    public function getAvatarUrl(): string {
        if ($this->avatar) {
            return asset('storage/avatars/'.$this->avatar);
        }

        $bg = $this->color ? ltrim($this->color, '#') : 'random';

        return 'https://ui-avatars.com/api/?size=30&name='.urlencode($this->name).'&background='.$bg.'&format=svg';
    }

    protected static function booted(): void
    {
        static::creating(function (User $user) {
            if (! $user->color) {
                $palette = [
                    'F3F4F6', // neutral
                    '60A5FA', // blue
                    'FDBA74', // orange
                    'FCA5A5', // red-ish
                    'C7F9CC', // greenish
                    'FDE68A', // yellow
                ];

                $user->color = Arr::random($palette);
            }
        });
    }
}
