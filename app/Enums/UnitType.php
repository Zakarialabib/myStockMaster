<?php

declare(strict_types=1);

namespace App\Enums;

use Illuminate\Support\Str;

enum UnitType: string
{
    case KG = 'kg';

    case PIECE = 'pcs';

    case METRE = 'm';

    case Gram = 'gr';

    public function getName(): string
    {
        return __(Str::studly($this->name));
    }

    public function getValue()
    {
        return $this->value;
    }

    public static function getLabel($value)
    {
        foreach (self::cases() as $case) {
            if ($case->getValue() === $value) {
                return $case->getName();
            }
        }

        return null;
    }
}
