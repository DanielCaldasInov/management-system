<?php

use App\Services\ViesApiService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

uses(TestCase::class);

it('returns formatted data for a valid VAT number', function () {
    Http::fake([
        'ec.europa.eu/*' => Http::response([
            'valid' => true,
            'name' => 'INOVCORP TEST LDA',
            'address' => "RUA DA INOVAÇÃO 42\n4000-000 PORTO",
        ], 200),
    ]);

    $service = new ViesApiService;

    $result = $service->checkVat('PT', '500000000');

    expect($result['success'])->toBeTrue()
        ->and($result['data']['name'])->toBe('INOVCORP TEST LDA')
        ->and($result['data']['address'])->toBe('RUA DA INOVAÇÃO 42')
        ->and($result['data']['zip_code'])->toBe('4000-000')
        ->and($result['data']['city'])->toBe('PORTO');
});

it('returns an error for an invalid VAT number', function () {
    Http::fake([
        'ec.europa.eu/*' => Http::response([
            'valid' => false,
        ], 200),
    ]);

    $service = new ViesApiService;
    $result = $service->checkVat('PT', '999999999');

    expect($result['success'])->toBeFalse()
        ->and($result['message'])->toContain('invalid or not registered');
});

it('handles API communication errors gracefully', function () {
    Http::fake([
        'ec.europa.eu/*' => Http::response(null, 500),
    ]);

    $service = new ViesApiService;
    $result = $service->checkVat('PT', '500000000');

    expect($result['success'])->toBeFalse()
        ->and($result['message'])->toContain('Error communicating');
});
