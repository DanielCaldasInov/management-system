<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Quote {{ $quote->reference }}</title>
    <style>
        body { font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif; font-size: 14px; color: #333; }
        table { width: 100%; border-collapse: collapse; }
        .header { margin-bottom: 40px; }
        .company-info { float: left; width: 50%; }
        .quote-info { float: right; width: 50%; text-align: right; }
        .clear { clear: both; }
        .logo { max-width: 150px; max-height: 80px; margin-bottom: 10px; }
        .details-box { margin-bottom: 30px; }
        .customer-info { float: left; width: 50%; }
        .meta-info { float: right; width: 50%; text-align: right; }
        .lines-table th { background: #f8f9fa; padding: 10px; text-align: left; border-bottom: 2px solid #ddd; }
        .lines-table td { padding: 10px; border-bottom: 1px solid #eee; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .totals-box { width: 300px; float: right; margin-top: 20px; }
        .totals-table td { padding: 8px 0; }
        .total-row { font-size: 18px; font-weight: bold; border-top: 2px solid #333; }
        .notes { margin-top: 50px; padding: 15px; background: #f9f9f9; border-left: 4px solid #666; font-size: 12px; }
        .status-badge { display: inline-block; padding: 5px 10px; border-radius: 4px; font-size: 12px; font-weight: bold; text-transform: uppercase; margin-bottom: 10px; }
    </style>
</head>
<body>

<div class="header">
    <div class="company-info">
        @if($company && $company->logo_path)
            <img src="{{ storage_path('app/public/' . $company->logo_path) }}" class="logo" alt="Photo">
        @endif
        <h2>{{ $company ? $company->name : 'Our Company' }}</h2>
        <p>
            {{ $company ? $company->address : '' }}<br>
            {{ $company ? $company->zip_code . ' ' . $company->city : '' }}<br>
            {{ $company && $company->vat_number ? 'NIF: ' . $company->vat_number : '' }}
        </p>
    </div>
    <div class="quote-info">
        <h1 style="color: #4a5568; margin-bottom: 5px;">{{ $document_title ?? 'QUOTE' }}</h1>
        <h2 style="margin-top: 0;">{{ $quote->reference }}</h2>

        @php
            $statusColor = '#6c757d';
            if($quote->status == 'sent') $statusColor = '#0d6efd';
            if($quote->status == 'accepted') $statusColor = '#198754';
            if($quote->status == 'rejected') $statusColor = '#dc3545';
        @endphp
        <div class="status-badge" style="background-color: {{ $statusColor }}; color: white;">
            {{ $quote->status }}
        </div>
    </div>
    <div class="clear"></div>
</div>

<div class="details-box">
    <div class="customer-info">
        <strong>Billed To:</strong><br>
        {{ $quote->entity->name }}<br>
        @if($quote->entity->vat_number) NIF: {{ $quote->entity->vat_number }}<br> @endif
        @if($quote->entity->email) {{ $quote->entity->email }}<br> @endif
        {{ $quote->entity->address }}<br>
        {{ $quote->entity->zip_code }} {{ $quote->entity->city }}
    </div>
    <div class="meta-info">
        <strong>Issue Date:</strong> {{ $quote->issue_date->format('d/m/Y') }}<br><br>
        @if($quote->valid_until)
            <strong>Valid Until:</strong> {{ $quote->valid_until->format('d/m/Y') }}
        @endif
    </div>
    <div class="clear"></div>
</div>

<table class="lines-table">
    <thead>
    <tr>
        <th>Description</th>
        <th class="text-center">Qty</th>
        <th class="text-right">Unit Price</th>
        <th class="text-right">VAT</th>
        <th class="text-right">Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach($quote->lines as $line)
        <tr>
            <td>{{ $line->description }}</td>
            <td class="text-center">{{ number_format($line->quantity, 2) }}</td>
            <td class="text-right">&euro; {{ number_format($line->unit_price, 2) }}</td>
            <td class="text-right">{{ number_format($line->vat_percentage, 2) }}%</td>
            <td class="text-right">&euro; {{ number_format($line->total, 2) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<div>
    <div class="totals-box">
        <table class="totals-table">
            <tr>
                <td>Subtotal:</td>
                <td class="text-right">&euro; {{ number_format($quote->subtotal, 2) }}</td>
            </tr>
            <tr>
                <td>VAT Total:</td>
                <td class="text-right">&euro; {{ number_format($quote->vat_total, 2) }}</td>
            </tr>
            <tr class="total-row">
                <td style="padding-top: 10px;">Total:</td>
                <td class="text-right" style="padding-top: 10px;">&euro; {{ number_format($quote->total, 2) }}</td>
            </tr>
        </table>
    </div>
    <div class="clear"></div>
</div>

@if($quote->notes)
    <div class="notes">
        <strong>Notes / Terms:</strong><br>
        {!! nl2br(e($quote->notes)) !!}
    </div>
@endif

</body>
</html>
