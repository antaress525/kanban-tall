<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    protected $fillable = [
        'title',
        'status'
    ];
    protected function casts(): array
    {
        return [
            'due_date' => 'datetime',
        ];
    }

    public function board(): BelongsTo {
        return $this->belongsTo(Board::class);
    }

    protected static function booted()
    {
        static::creating(function ($task) {
            if (!$task->order) {
                $maxOrder = Task::where('board_id', $task->board_id)
                    ->where('status', $task->status)
                    ->max('order');

                $task->order = $maxOrder ? $maxOrder + 1 : 1;
            }
        });
    }
}
