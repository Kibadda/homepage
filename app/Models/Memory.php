<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Memory extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image',
        'date',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function timeline(): BelongsTo
    {
        return $this->belongsTo(Timeline::class);
    }
}
