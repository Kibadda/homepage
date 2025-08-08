<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Timeline extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
    ];

    public function memories(): HasMany
    {
        return $this->hasMany(Memory::class);
    }
}
