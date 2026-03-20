<!DOCTYPE html>
<html>
<head>
    <title>Invoice {{ $invoice->reference }}</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6;">
<h2>Hello, {{ $invoice->entity->name }}</h2>

<p>We confirm the receipt and registration of your invoice with reference <strong>{{ $invoice->reference }}</strong>, issued on {{ $invoice->issue_date->format('d/m/Y') }}.</p>

<p><strong>Document Summary:</strong></p>
<ul>
    <li>Subtotal: € {{ number_format($invoice->subtotal, 2) }}</li>
    <li>VAT: € {{ number_format($invoice->vat_total, 2) }}</li>
    <li><strong>Total: € {{ number_format($invoice->total, 2) }}</strong></li>
    <li>Due Date: {{ $invoice->due_date->format('d/m/Y') }}</li>
</ul>

@if($invoice->attachment_path)
    <p>Please find attached a copy of the original document for your reference.</p>
@endif

<p>Best regards,<br>
    <strong>The {{ config('app.name') }} Team</strong></p>
</body>
</html>
