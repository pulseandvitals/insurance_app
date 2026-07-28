<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\Branch;
use App\Models\User;
use App\Services\ProducerProvisioner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;
use Inertia\Response;

class UserController extends Controller
{
    public function index(Request $request): Response
    {
        $users = User::query()
            ->where('role', User::ROLE_PRODUCER)
            ->with('producer.branch')
            ->when($request->string('search')->toString(), function ($query, $search) {
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->integer('branch_id'), function ($query, $branchId) {
                $query->whereHas('producer', fn ($q) => $q->where('branch_id', $branchId));
            })
            ->latest()
            ->get();

        return Inertia::render('Admin/Users/Index', [
            'users' => $users,
            'branches' => Branch::orderBy('name')->get(['id', 'code', 'name']),
            'filters' => $request->only(['search', 'branch_id']),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Users/Create', [
            'branches' => Branch::orderBy('name')->get(['id', 'code', 'name']),
        ]);
    }

    public function store(StoreUserRequest $request, ProducerProvisioner $provisioner): RedirectResponse
    {
        $user = User::create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => Hash::make($request->validated('password')),
            'role' => User::ROLE_PRODUCER,
            'email_verified_at' => now(),
        ]);

        $branch = Branch::findOrFail($request->validated('branch_id'));
        $provisioner->provision($user, $branch);

        $user->producer->update($request->safe()->only([
            'first_name', 'middle_name', 'last_name', 'suffix', 'address', 'phone',
        ]));

        $user->producer->pricing->update($request->safe()->only([
            'others_fee', 'coc_verification_fee', 'motorcycle_price', 'pc_suv_price', 'cv_truck_price',
        ]));

        return redirect()->route('admin.users.index')->with('success', 'Producer account created.');
    }

    public function edit(User $user): Response
    {
        return Inertia::render('Admin/Users/Edit', [
            'user' => $user->load('producer.branch', 'producer.pricing'),
            'branches' => Branch::orderBy('name')->get(['id', 'code', 'name']),
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $user->producer->update($request->safe()->only([
            'branch_id', 'first_name', 'middle_name', 'last_name', 'suffix', 'address', 'phone', 'status',
        ]));

        $user->producer->pricing->update($request->safe()->only([
            'others_fee', 'coc_verification_fee', 'motorcycle_price', 'pc_suv_price', 'cv_truck_price',
        ]));

        return redirect()->route('admin.users.index')->with('success', 'Producer updated.');
    }
}
