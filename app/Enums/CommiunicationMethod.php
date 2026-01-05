<?php

namespace App\Enums;

enum CommiunicationMethod : string
{
    case EMAIL = 'email';
    case WHATSAPP = 'whatsapp';
    case None = 'none';
}
