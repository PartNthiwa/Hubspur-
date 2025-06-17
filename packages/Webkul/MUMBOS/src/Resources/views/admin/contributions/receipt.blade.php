<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Contribution Receipt</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 13px;
            color: #333;
            margin: 40px;
        }

        .header,
        .footer {
            text-align: center;
        }

        .logo {
            width: 120px;
            margin-bottom: 10px;
        }

        h2 {
            margin: 0;
            font-size: 20px;
            color: #2c3e50;
        }

        .title {
            font-size: 16px;
            margin-top: 4px;
            font-weight: bold;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        .table th, .table td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }

        .note-section {
            margin-top: 25px;
            font-style: italic;
        }

        .footer {
            margin-top: 60px;
            font-size: 12px;
            color: #777;
        }

        .signature {
            margin-top: 50px;
            display: flex;
            justify-content: space-between;
        }

        .signature div {
            width: 45%;
            border-top: 1px solid #999;
            text-align: center;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <div class="header">
        @php
            $logoPath = core()->getConfigData('general.design.logo.logo.png');
            $logoFullPath = $logoPath ? public_path('storage/' . $logoPath) : null;
        @endphp

        @if($logoFullPath && file_exists($logoFullPath))
            <img src="{{ $logoFullPath }}" class="logo" alt="Company Logo">
        @endif

        <h2>MUMBO Kenya Diaspora Investment Ltd</h2>
        <p class="title">Shareholder Contribution Receipt</p>
    </div>

    <!-- Contribution Details Table -->
    <table class="table">
        <thead>
            <tr>
                <th>Receipt #</th>
                <th>Date</th>
                <th>Shareholder</th>
                <th>Amount</th>
                <th>Method</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>#{{ $contribution->id }}</td>
                <td>{{ \Carbon\Carbon::parse($contribution->contributed_at)->format('F j, Y') }}</td>
                <td>{{ $contribution->shareholder->customer->first_name }} {{ $contribution->shareholder->customer->last_name }}</td>
                <td>{{ number_format($contribution->amount, 2) }} {{ strtoupper($contribution->currency) }}</td>
                <td>{{ ucfirst(str_replace('_', ' ', $contribution->payment_method)) }}</td>
                <td>{{ ucfirst($contribution->payment_status) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- Extra Details -->
    <table class="table" style="margin-top: 10px;">
        @if($contribution->payment_reference)
            <tr>
                <th style="width: 200px;">Payment Reference</th>
                <td>{{ $contribution->payment_reference }}</td>
            </tr>
        @endif
        @if($contribution->payment_channel)
            <tr>
                <th>Payment Channel</th>
                <td>{{ $contribution->payment_channel }}</td>
            </tr>
        @endif
        @if($contribution->note)
            <tr>
                <th>Note</th>
                <td>{{ $contribution->note }}</td>
            </tr>
        @endif
    </table>

    <!-- Signatures -->
    <div class="signature">
        <div>Shareholder Signature</div>
        <div>Authorized by MUMBO</div>
    </div>

    <!-- Footer -->
    <div class="footer">
        @php
            $website = config('app.url');
        @endphp
        <p>This is a system-generated receipt. For official use only.</p>
        <p>Website: {{ $website }} | Email: support@mumbo.co.ke</p>
    </div>

</body>
</html>
