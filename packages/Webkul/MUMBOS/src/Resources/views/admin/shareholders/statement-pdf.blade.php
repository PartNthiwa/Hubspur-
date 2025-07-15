<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Contribution Statement</title>
    <style>
      body {
        font-family: DejaVu Sans, sans-serif;
        font-size: 12px;
        margin: 0px;
        color: #1f2937;
    }
    .content {
        padding: 40px;
        padding-bottom: 100px; /* Leave space for fixed footer */
    }

    .header {
        text-align: center;
        margin-bottom: 20px;
    }

    .logo {
        width: 150px;
        margin-bottom: 10px;
    }

    .section {
        margin-bottom: 2px;
    }

    .section h3 {
        color: #006400;
        border-bottom: 1px solid #ccc;
        padding-bottom: 4px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
    }

    table, th, td {
        border: 1px solid #ccc;
    }

    th {
        background-color: #006400;
        color: white;
        text-align: left;
    }

    td {
        padding: 6px;
    }

    .stamp-background {
        position: fixed;
        bottom: 20px;
        right: 60px;
        width: 120px;
        opacity: 5;
        z-index: 0;
        pointer-events: none;
    }

    footer {
        position: fixed;
        bottom: 2;
        left: 0;
        right: 0;
        height: 80px; /* Adjust based on your footer content */
       
        color:rgb(2, 58, 7);
        padding: 20px;
        font-size: 12px;
        text-align: center;
        box-sizing: border-box;
        z-index: 1000;
        page-break-inside: avoid;
    }

    .approved {
        color: #006400;
        font-weight: bold;
    }

    .rejected {
        color: #b91c1c;
        font-weight: bold;
    }

    .info-meta {
        text-align: right;
        font-size: 11px;
        margin-top: -20px;
    }

    .qr {
        text-align: right;
        margin-top: 20px;
    }
    </style>
</head>
<body>

<div class="content">
    <div class="header">
        <img src="{{ public_path('images/mumbokenya.png') }}" class="logo" alt="MUMBO Logo">
        <h2>MUMBO Kenya Diaspora Investments Ltd</h2>
        <p><strong>Shareholder Contribution Statement</strong></p>
    </div>

    <div class="info-meta">
        <p><strong>Receipt No:</strong> MDI-{{ str_pad($shareholder->id, 6, '0', STR_PAD_LEFT) }}</p>
    <p><strong>Statement Date:</strong> {{ now()->format('d M Y, h:i A') }}</p>

    </div>

    <table style="width: 100%; margin-bottom: 10px;">
        <tr>
            <!-- Member Info -->
            <td style="vertical-align: top; width: 50%;">
                <div class="section">
                    <h3>Member Info</h3>
                    <p><strong>Name:</strong> {{ $shareholder->customer->first_name }} {{ $shareholder->customer->last_name }}</p>
                    <p><strong>Member No:</strong> {{ $shareholder->shareholder_number }}</p>
                    <p><strong>Email:</strong> {{ $shareholder->customer->email }}</p>
                    <p><strong>Phone:</strong> {{ $shareholder->phone ?? 'N/A' }}</p>
                </div>
                <img src="{{ public_path('images/digi.png') }}" alt="Digital Stamp" class="stamp-background">
                <p style="position: absolute; bottom: 80px; right: 60px; font-size: 10px; color: #555;">Digitally Issued</p>
            </td>

            <!-- Incentives -->
            <td style="vertical-align: top; width: 50%;">
                <div class="section">
                    <h3>Incentives</h3>
                    <ul>
                        @forelse ($shareholder->incentives as $incentive)
                            <li><strong>{{ ucfirst($incentive->type) }}:</strong> {{ $incentive->pivot->units ?? 0 }} units</li>
                        @empty
                            <li>No incentives found.</li>
                        @endforelse
                    </ul>
                </div>
            </td>
        </tr>
    </table>

    <!-- Summary of Capital and Other Contributions -->
    <div class="section">
        <h3>Contribution Summary</h3>
        <table>
            <thead>
                <tr>
                    <th>Capital Contribution (by Phase)</th>
                    <th>Other Contributions (Excl. Membership & Capital)</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $capitalByPhase = $shareholder->contributions
                        ->where('type', 'capital')
                        ->groupBy('phase.name');

                    $otherContributions = $shareholder->contributions
                        ->filter(fn($c) => !in_array($c->type, ['capital', 'membership']));
                @endphp
                <tr>
                    <td>
                        <ul style="margin: 0; padding-left: 16px;">
                            @forelse($capitalByPhase as $phaseName => $group)
                                <li>{{ $phaseName ?? 'N/A' }}: KES {{ number_format($group->sum('amount'), 2) }}</li>
                            @empty
                                <li>No capital contributions</li>
                            @endforelse
                        </ul>
                    </td>
                    <td>
                        <ul style="margin: 0; padding-left: 16px;">
                        <li>
                            KES {{ number_format($otherContributions->sum('amount'), 2) }}
                        </li>
                       </ul> 
                    </td>
                     
                </tr>
                <tr>
                      <td colspan='2'>
                        <ul style="margin: 0; padding-left: 16px;">
                        <li>
                        Total Contributions : KES {{ number_format($shareholder->contributions->sum('amount'), 2) }}
                        </li>
                       </ul> 
                    </td>
                   
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <h3>All Contributions</h3>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Phase</th>
                    <th>Amount (KES)</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($shareholder->contributions as $contribution)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($contribution->created_at)->format('d M Y') }}</td>
                        <td>{{ ucfirst($contribution->type) }}</td>
                        <td>{{ $contribution->phase->name ?? 'N/A' }}</td>
                        <td>{{ number_format($contribution->amount, 2) }}</td>
                        <td class="{{ $contribution->status === 'approved' ? 'approved' : 'rejected' }}">
                            {{ ucfirst($contribution->status) }}
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5">No contributions found.</td></tr>
                @endforelse

                <tr style="font-weight: bold;">
                    <td colspan="3">Total Contributions</td>
                    <td colspan="2">{{ number_format($shareholder->contributions->sum('amount'), 2) }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Optional QR Code for Verification -->
    {{-- <div class="qr">
        <img src="data:image/png;base64,{{ base64_encode(QrCode::format('png')->size(100)->generate(route('verify.receipt', $shareholder->id))) }}" alt="QR Code">
        <p style="font-size: 10px;">Scan to verify receipt online</p>
    </div> --}}
</div>

<img src="{{ public_path('images/digi.png') }}" alt="Digital Stamp" class="stamp-background">
<p style="position: absolute; bottom: 80px; right: 60px; font-size: 10px; color: #555;">Digitally Issued</p>

<footer>
    <div style="max-width: 800px; margin: 0 auto;">
        <em>This is a system-generated statement. No signature is required.</em><br>
        <strong>MUMBO Kenya Diaspora Investments Ltd</strong><br>
        P.O. Box XXXX-00100 Nairobi, Kenya<br>
        Website: www.mumbodiaspora.org | Tel: +254 712 345 678<br>
        Email: <a href="mailto:invest@mumbodiaspora.org" style="color:rgb(248, 2, 2); text-decoration: underline;">invest@mumbodiaspora.org</a><br><br>
        <small>This receipt is issued in accordance with applicable financial reporting guidelines. Contributions are non-refundable unless otherwise stated in writing.</small><br><br>
        &copy; {{ date('Y') }} MUMBO Kenya Diaspora Investments. All rights reserved.
    </div>
</footer>

</body>
</html>
