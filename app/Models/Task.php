<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Task extends Model
{
    protected $fillable = [
        'title',
        'status',
        'order',
        'description',
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

    public function reorder(string $status, string|int $position)
    {
        DB::transaction(function () use ($status, $position) {

            $task = Task::lockForUpdate()->findOrFail($this->id);

            $newPosition = (int) $position + 1;
            $oldPosition = $task->order;
            $oldStatus = $task->status;
            $newStatus = $status;
            $boardId = $task->board_id;

            if ($newStatus === $oldStatus && $newPosition === $oldPosition) {
                return;
            }

            /*
            |-----------------------------------------
            | Move another column
            |-----------------------------------------
            */
            if ($newStatus !== $oldStatus) {

                // Close old column
                Task::where('board_id', $boardId)
                    ->where('status', $oldStatus)
                    ->where('order', '>', $oldPosition)
                    ->decrement('order');

                // Open new space in column
                Task::where('board_id', $boardId)
                    ->where('status', $newStatus)
                    ->where('order', '>=', $newPosition)
                    ->increment('order');

                // Update task
                $task->update([
                    'status' => $newStatus,
                    'order'  => $newPosition,
                ]);

                return;
            }

            /*
            |-----------------------------------------
            | Move same column
            |-----------------------------------------
            */
            if ($newPosition > $oldPosition) {

                // Down
                Task::where('board_id', $boardId)
                    ->where('status', $oldStatus)
                    ->whereBetween('order', [$oldPosition + 1, $newPosition])
                    ->decrement('order');
            } elseif ($newPosition < $oldPosition) {

                // Up
                Task::where('board_id', $boardId)
                    ->where('status', $oldStatus)
                    ->whereBetween('order', [$newPosition, $oldPosition - 1])
                    ->increment('order');
            }

            $task->update(['order' => $newPosition]);
        });
    }

    public function assignees(): BelongsToMany {
        return $this->belongsToMany(User::class);
            // ->withTimestamps();
    }
}
