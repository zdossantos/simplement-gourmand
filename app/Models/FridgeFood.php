<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FridgeFood extends Model
{
    use HasFactory;
    protected $table = 'fridge_food';

    protected $fillable = [
        'user_id',
        'openfoodfacts_id',
        'name',
        'quantity',
        'unit',
        'image',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'user_id' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
