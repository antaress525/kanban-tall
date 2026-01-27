<?php

namespace App\Enum;

enum TaskStatuEnum: string
{
    case TODO = 'to_do';
    case DOING = 'in_progress';
    case review = 'review';
    case DONE = 'done';

    public function label(): string {
        return 'test'; 
    }
}
