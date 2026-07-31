<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ErrorLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ErrorLogController extends Controller
{
    public function index(Request $request): Response
    {
        $errors = ErrorLog::query()
            ->when($request->string('search')->toString(), function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('message', 'like', "%{$search}%")
                        ->orWhere('url', 'like', "%{$search}%")
                        ->orWhere('exception_class', 'like', "%{$search}%");
                });
            })
            ->when($request->string('method')->toString(), fn ($query, $method) => $query->where('method', $method))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Dev/Errors/Index', [
            'errors' => $errors,
            'filters' => $request->only(['search', 'method']),
        ]);
    }

    public function show(ErrorLog $errorLog): Response
    {
        return Inertia::render('Admin/Dev/Errors/Show', [
            'error' => $errorLog->load('user'),
        ]);
    }

    public function destroy(ErrorLog $errorLog): RedirectResponse
    {
        $errorLog->delete();

        return redirect()->route('admin.dev.errors.index')->with('success', 'Error log deleted.');
    }
}
