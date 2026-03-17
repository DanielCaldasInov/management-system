<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'vat_number',
        'address',
        'zip_code',
        'city',
        'logo_path',
    ];

    protected function casts(): array
    {
        return [
            'name' => 'encrypted',
            'vat_number' => 'encrypted',
            'address' => 'encrypted',
            'zip_code' => 'encrypted',
            'city' => 'encrypted',
        ];
    }
}
