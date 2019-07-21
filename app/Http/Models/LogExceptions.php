<?php

namespace App\Http\Models;

use Log;
use Exception;

/**
 * Class LogExceptions
 *
 * @package App\Http\Models
 */
class LogExceptions
{
    /**
     * @param Exception $exception
     * @param string|null $type
     * @return void
     */
    public static function log(Exception $exception, ?string $type): void
    {
        Log::error($exception->getMessage(), [$type, $exception->getTraceAsString()]);
    }
}
