<?php

namespace App\Enum;

enum TaskPriorityEnum: string
{
    case NONE = 'none';
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';

    public function label(): string
    {
        return match ($this) {
            self::NONE => 'Aucune',
            self::LOW => 'Faible',
            self::MEDIUM => 'Moyenne',
            self::HIGH => 'Forte',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::NONE => 'Pas de priorité particulière',
            self::LOW => 'Peut être traitée plus tard',
            self::MEDIUM => 'À traiter prochainement',
            self::HIGH => 'Nécessite une attention immédiate',
        };
    }

    public function classes(): string
    {
        return match ($this) {
            self::NONE => 'bg-neutral-100 text-neutral-500',
            self::LOW => 'bg-blue-100 text-blue-500',
            self::MEDIUM => 'bg-orange-100 text-orange-500',
            self::HIGH => 'bg-red-100 text-red-500',
        };
    }

}
