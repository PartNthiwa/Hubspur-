<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Contribution Receipt</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        .header { text-align: center; margin-bottom: 20px; }
        .details { margin-top: 20px; }
        .details th, .details td { text-align: left; padding: 4px 8px; }
    </style>
</head>
<body>
    <div class="header">
        <h2>MUMBO Kenya Diaspora Investment Ltd</h2>
        <p><strong>Contribution Receipt</strong></p>
    </div>

    <table class="details">
        <tr><th>Receipt #: </th><td>#{{ $contribution->id }}</td></tr>
        <tr><th>Date:</th><td>{{ $contribution->contributed_at->format('Y-m-d') }}</td></tr>
        <tr><th>Shareholder:</th><td>{{ $contribution->shareholder->customer->first_name }} {{ $contribution->shareholder->customer->last_name }}</td></tr>
        <tr><th>Amount:</th><td>{{ number_format($contribution->amount, 2) }} {{ $contribution->currency }}</td></tr>
        <tr><th>Method:</th><td>{{ ucfirst(str_replace('_', ' ', $contribution->payment_method)) }}</td></tr>
        @if($contribution->payment_reference)
            <tr><th>Reference:</th><td>{{ $contribution->payment_reference }}</td></tr>
        @endif
        <tr><th>Status:</th><td>{{ ucfirst($contribution->payment_status) }}</td></tr>
    </table>

    @if($contribution->note)
        <p style="margin-top: 20px;"><strong>Note:</strong><br>{{ $contribution->note }}</p>
    @endif
</body>
</html>
