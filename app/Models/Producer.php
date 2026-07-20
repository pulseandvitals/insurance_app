<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Producer extends Model
{
    protected $appends = ['full_name', 'wallet_handle'];

    protected $fillable = [
        'user_id',
        'code',
        'first_name',
        'middle_name',
        'last_name',
        'suffix',
        'status',
        'is_oa',
        'tier',
        'address',
        'email',
        'phone',
        'branch_code',
        'branch_name',
    ];

    protected function casts(): array
    {
        return [
            'is_oa' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    public function deposits(): HasMany
    {
        return $this->hasMany(Deposit::class);
    }

    public function motorQuotes(): HasMany
    {
        return $this->hasMany(MotorQuote::class);
    }

    public function policies(): HasMany
    {
        return $this->hasMany(Policy::class);
    }

    public function getFullNameAttribute(): string
    {
        $middleInitial = $this->middle_name ? strtoupper(substr($this->middle_name, 0, 1)).'.' : null;

        return collect([$this->first_name, $middleInitial, $this->last_name, $this->suffix])
            ->filter()
            ->implode(' ');
    }

    public function getWalletHandleAttribute(): string
    {
        return strtolower(str_replace(' ', '', $this->first_name.$this->last_name)).'_'.$this->code.'@ctpl';
    }
}
