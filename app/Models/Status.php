<?php

namespace App\Models;

enum Status: string
{
    case OPEN = 'open';
    case IN_PROGRESS = 'in_progress';

    case CLOSED = 'closed';
}
