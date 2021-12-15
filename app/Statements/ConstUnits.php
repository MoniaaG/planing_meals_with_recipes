<?php
namespace App\Statements;

class ConstUnits {
    const g = 'g';
    const ml = 'ml';
    const szt = 'szt';

    public static function constUnits()
    {
        return [
            self::g,
            self::ml,
            self::szt,
        ];
    }
}