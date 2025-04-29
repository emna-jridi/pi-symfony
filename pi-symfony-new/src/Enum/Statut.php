<?php
namespace App\Enum;

enum Statut: string
{
    case EN_COURS = 'En_cours';
    case ACCEPTEE = 'acceptée';
    case DISQUALIFIEE = 'disqualifiée';

    public static function default(): self
    {
        return self::EN_COURS;
    }
}
