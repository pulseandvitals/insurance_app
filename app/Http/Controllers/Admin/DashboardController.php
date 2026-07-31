<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'producers_count' => User::whereHas('roles', fn ($q) => $q->where('name', User::ROLE_PRODUCER))->count(),
                'branches_count' => Branch::count(),
            ],
        ]);
    }
}
