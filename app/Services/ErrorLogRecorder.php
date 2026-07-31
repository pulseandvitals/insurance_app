<?php

namespace App\Services;

use App\Models\ErrorLog;
use Illuminate\Http\Request;
use Throwable;

class ErrorLogRecorder
{
    public function __construct(private readonly Request $request) {}

    /**
     * Persist a record of the given exception so it can be reviewed from the admin Dev menu.
     */
    public function record(Throwable $e): void
    {
        try {
            ErrorLog::create([
                'method' => $this->request->method(),
                'url' => $this->request->fullUrl(),
                'route_name' => $this->request->route()?->getName(),
                'status_code' => method_exists($e, 'getStatusCode') ? $e->getStatusCode() : null,
                'exception_class' => get_class($e),
                'message' => $e->getMessage() ?: get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => substr($e->getTraceAsString(), 0, 20000),
                'user_id' => $this->request->user()?->id,
                'ip' => $this->request->ip(),
            ]);
        } catch (Throwable) {
            // Never let a broken error logger mask the original exception.
        }
    }
}
