<?php

use App\Http\Controllers\Admin\BranchController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\MotorQuoteController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\PolicyholderController;
use App\Http\Controllers\ProducerController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
    ]);
});

Route::get('/dashboard', function () {
    $user = request()->user();

    if ($user->isSuperAdmin()) {
        return redirect()->route('admin.dashboard');
    }

    return redirect()->route('producers.dashboard', $user->producer);
})->middleware(['auth', 'verified', 'producer'])->name('dashboard');

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('branches', BranchController::class)->except(['show']);
    Route::resource('users', AdminUserController::class)->only(['index', 'create', 'store', 'edit', 'update']);
});

Route::middleware(['auth', 'verified', 'producer'])->group(function () {
    // Producer dashboard & account
    Route::get('/producers/wallet', [WalletController::class, 'show'])->name('producers.wallet');
    Route::post('/producers/wallet/deposit', [WalletController::class, 'deposit'])->name('producers.wallet.deposit');
    Route::get('/producers/{producer}/edit', [ProducerController::class, 'edit'])->name('producers.edit');
    Route::patch('/producers/{producer}', [ProducerController::class, 'update'])->name('producers.update');
    Route::get('/producers/{producer}', [ProducerController::class, 'dashboard'])->name('producers.dashboard');

    // Deposits (topups)
    Route::get('/topups', [DepositController::class, 'index'])->name('topups.index');
    Route::post('/topups/{deposit}/pay', [DepositController::class, 'pay'])->name('topups.pay');
    Route::post('/topups/{deposit}/upload', [DepositController::class, 'upload'])->name('topups.upload');

    // Motor CTPL — OCR & Renewal (fixed paths, must be registered before the {motor_quote} wildcard group)
    Route::get('/motor_risks/ctplocr', [MotorQuoteController::class, 'ocr'])->name('motor-risks.ocr');
    Route::post('/motor_risks/ctplocr', [MotorQuoteController::class, 'ocrStore'])->name('motor-risks.ocr.store');
    Route::get('/motor_risks/new', [MotorQuoteController::class, 'create'])->name('motor-risks.create');
    Route::post('/motor_risks', [MotorQuoteController::class, 'store'])->name('motor-risks.store');
    Route::get('/motor_risks', [MotorQuoteController::class, 'index'])->name('motor-risks.index');

    // Motor CTPL — Retail wizard
    Route::get('/motor_risks/{motorQuote}', [MotorQuoteController::class, 'show'])->name('motor-risks.show');
    Route::post('/motor_risks/{motorQuote}/proceed', [MotorQuoteController::class, 'proceed'])->name('motor-risks.proceed');
    Route::get('/motor_risks/{motorQuote}/pre_flight', [MotorQuoteController::class, 'preFlight'])->name('motor-risks.pre-flight');
    Route::post('/motor_risks/{motorQuote}/pre_flight', [MotorQuoteController::class, 'preFlightStore'])->name('motor-risks.pre-flight.store');
    Route::post('/motor_risks/{motorQuote}/authenticate_lto', [MotorQuoteController::class, 'authenticateLto'])->name('motor-risks.authenticate-lto');
    Route::get('/motor_risks/{motorQuote}/checkout', [MotorQuoteController::class, 'checkout'])->name('motor-risks.checkout');
    Route::post('/motor_risks/{motorQuote}/checkout', [MotorQuoteController::class, 'checkoutStore'])->name('motor-risks.checkout.store');

    // Policyholders
    Route::post('/motor_risks/{motorQuote}/policyholders', [PolicyholderController::class, 'store'])->name('motor-risks.policyholders.store');
    Route::delete('/motor_risks/{motorQuote}/policyholders/{policyholder}', [PolicyholderController::class, 'destroy'])->name('motor-risks.policyholders.destroy');
    Route::post('/motor_risks/{motorQuote}/policyholders/{policyholder}/use_address', [PolicyholderController::class, 'useAddress'])->name('motor-risks.policyholders.use-address');

    // Online Sales — fixed paths before the {policy} wildcard
    Route::get('/policies/onlinepolicyrenewal', [PolicyController::class, 'renewal'])->name('policies.renewal');
    Route::post('/policies/onlinepolicyrenewal', [PolicyController::class, 'renewalSearch'])->name('policies.renewal.search');
    Route::get('/policies/extractions', [PolicyController::class, 'extractions'])->name('policies.extractions');
    Route::post('/policies/extractions', [PolicyController::class, 'extractionsExport'])->name('policies.extractions.export');
    Route::get('/policies/batch_coc', [PolicyController::class, 'batchCoc'])->name('policies.batch-coc');
    Route::post('/policies/batch_coc', [PolicyController::class, 'batchCocExport'])->name('policies.batch-coc.export');
    Route::get('/policies', [PolicyController::class, 'index'])->name('policies.index');

    // Policy contract detail
    Route::get('/policies/{policy}', [PolicyController::class, 'show'])->name('policies.show');
    Route::get('/policies/{policy}/download', [PolicyController::class, 'download'])->name('policies.download');
    Route::get('/policies/{policy}/print/{mode}', [PolicyController::class, 'print'])
        ->where('mode', 'schedule|coc|cov|premium-statement|jacket')
        ->name('policies.print');
});

require __DIR__.'/auth.php';
