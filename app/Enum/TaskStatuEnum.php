<?php

namespace App\Enum;

enum TaskStatuEnum: string
{
    case TODO = 'to_do';
    case INPROGRESS = 'in_progress';
    case REVIEW = 'review';
    case DONE = 'done';

    public function label(): string {
        return match($this) {
            TaskStatuEnum::TODO => 'A faire',
            TaskStatuEnum::INPROGRESS => 'En cours',
            TaskStatuEnum::REVIEW => 'Revue',
            TaskStatuEnum::DONE => 'Fait',
        };
    }

    public function color(): string {
        return match($this) {
            TaskStatuEnum::TODO => 'bg-neutral-100',
            TaskStatuEnum::INPROGRESS => 'bg-orange-500/15 text-orange-500',
            TaskStatuEnum::REVIEW => 'bg-blue-500/15 text-blue-500',
            TaskStatuEnum::DONE => 'bg-green-500/15 text-green-500',
        };
    }


}
