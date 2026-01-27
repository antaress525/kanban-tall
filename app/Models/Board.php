<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Board extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'user_id',
        'color',
    ];

    protected static function booted()
    {
        static::creating(function (Board $board) {
            $colors = [
                '#FF5733',
                '#33FF57',
                '#3357FF',
                '#F3FF33',
                '#FF33F3',
                '#33FFF3',
                '#FF8C33',
                '#8C33FF',
                '#33FF8C',
                '#FF3333',
                '#33A1FF',
                '#A1FF33',
                '#FF33A1',
                '#A133FF',
                '#33FFA1',
                '#FFD433',
                '#FF6F61',
                '#6BFF33',
                '#336BFF',
                '#FF336B',
                '#33FFCC',
                '#CC33FF',
                '#FFCC33',
                '#33CCFF',
                '#CCFF33',
            ];

            $board->color = $board->color ?? $colors[array_rand($colors)];
        });
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function tasks(): HasMany {
        return $this->hasMany(Task::class);
    }
}
