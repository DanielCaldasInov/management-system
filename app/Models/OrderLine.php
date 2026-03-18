<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderLine extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'article_id',
        'supplier_id',
        'description',
        'quantity',
        'unit_price',
        'cost_price',
        'vat_percentage',
        'subtotal',
        'vat_amount',
        'total',
    ];

    protected function casts(): array
    {
        return [
            'description' => 'encrypted',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Entity::class, 'supplier_id');
    }
}
