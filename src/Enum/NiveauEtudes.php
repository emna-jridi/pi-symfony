<?php
namespace App\Enum;

enum NiveauEtudes: string
{
    case AUCUN = 'AUCUN';
    case PRIMAIRE = 'PRIMAIRE';
    case SECONDAIRE = 'SECONDAIRE';
    case BAC = 'BAC';
    case BAC_PLUS_2 = 'BAC_PLUS_2';
    case BAC_PLUS_3 = 'BAC_PLUS_3';
    case BAC_PLUS_5 = 'BAC_PLUS_5';
    case DOCTORAT = 'DOCTORAT';
}
