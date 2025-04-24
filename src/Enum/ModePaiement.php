<?php
namespace App\Enum;

enum ModePaiement: string
{
    case VIREMENT_BANCAIRE = 'VIREMENT_BANCAIRE';
    case CHEQUE = 'CHEQUE';
}
