<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Contribution Statement</title>
    <style>
        body { font-family: sans-serif; font-size: 13px; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; }
        th { background-color: #f8f8f8; }
    </style>
</head>
<body>
    <h2>Contribution Statement</h2>
    <p><strong>Name:</strong> {{ $shareholder->customer->first_name }} {{ $shareholder->customer->last_name }}</p>
    <p><strong>Member No.:</strong> {{ $shareholder->shareholder_number }}</p>
    <p><strong>Generated on:</strong> {{ now()->format('Y-m-d H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Type</th>
                <th>Phase</th>
                <th>Amount</th>
                <th>Currency</th>
                <th>Method</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($contributions as $c)
                <tr>
                    <td>{{ $c->contributed_at->format('Y-m-d') }}</td>
                    <td>{{ ucfirst($c->type) }}</td>
                    <td>{{ $c->phase->name ?? '–' }}</td>
                    <td>{{ number_format($c->amount, 2) }}</td>
                    <td>{{ $c->currency }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $c->payment_method)) }}</td>
                    <td>{{ ucfirst($c->status) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
