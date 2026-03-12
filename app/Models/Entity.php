<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

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
}
