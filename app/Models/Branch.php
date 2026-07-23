<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    protected $fillable = ['code', 'name', 'address', 'status'];

    public function producers(): HasMany
    {
        return $this->hasMany(Producer::class);
    }
}
