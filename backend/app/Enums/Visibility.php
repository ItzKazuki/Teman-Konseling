<?php

namespace App\Enums;

/**
 * Enum for file visibility options.
 * Local: File is stored privately.
 * Public: File is accessible publicly.
 */
enum Visibility: string
{
    case LOCAL = 'local';
    case PUBLIC = 'public';
}
