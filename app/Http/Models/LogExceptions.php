<?php

namespace App\Http\Models;

use Log;
use Throwable;


/**
 * Class LogExceptions
 *
 * @package App\Http\Models
 */
class LogExceptions
{
    /**
     * @param Throwable $exception
     * @param string|null $type
     */
    public static function log(Throwable $exception, ?string $type): void
    {
        Log::error($exception->getMessage(), [$type, $exception->getTraceAsString()]);
    }
}
