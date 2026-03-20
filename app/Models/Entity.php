<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Entity extends Model
{
    use HasFactory, LogsActivity, SoftDeletes;

    protected $fillable = [
        'is_customer',
        'is_supplier',
        'vat_number',
        'name',
        'address',
        'zip_code',
        'city',
        'phone',
        'mobile',
        'email',
        'website',
        'notes',
        'gdpr_consent',
        'number',
    ];

    protected function casts(): array
    {
        return [
            'is_customer' => 'boolean',
            'is_supplier' => 'boolean',
            'is_active' => 'boolean',
            'gdpr_consent' => 'boolean',
            'address' => 'encrypted',
            'zip_code' => 'encrypted',
            'city' => 'encrypted',
            'phone' => 'encrypted',
            'mobile' => 'encrypted',
            'email' => 'encrypted',
            'website' => 'encrypted',
            'notes' => 'encrypted',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable();
    }
}
