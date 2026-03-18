<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type',
        'entity_id',
        'quote_id',
        'reference',
        'issue_date',
        'status',
        'subtotal',
        'vat_total',
        'total',
        'notes',
        'supplier_id',
        'cost_price',
    ];

    protected function casts(): array
    {
        return [
            'issue_date' => 'date',
            'notes' => 'encrypted',
        ];
    }

    public function entity(): BelongsTo
    {
        return $this->belongsTo(Entity::class);
    }

    public function quote(): BelongsTo
    {
        return $this->belongsTo(Quote::class);
    }

    public function lines(): HasMany
    {
        return $this->hasMany(OrderLine::class);
    }
}
