<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Member Card</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #1f2937;
            margin: 0;
            padding: 40px;
            background: #fff;
        }

        .card {
            width: 320px; /* ~8.5cm */
            height: 210px; /* ~5.5cm */
            border: 2px solid #16a34a;
            border-radius: 12px;
            padding: 16px 20px;
            position: relative;
            background: linear-gradient(135deg, #e6f4ea, #ffffff); /* dollar-themed green */
            box-shadow: 0 0 10px rgba(22, 163, 74, 0.2);
        }

        .logo {
            width: 60px;
            display: block;
            margin: 0 auto 4px;
        }

        .title {
            text-align: center;
            font-size: 14px;
            color: #16a34a;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .info {
            font-size: 11px;
            margin-bottom: 4px;
            line-height: 1.4;
        }

        .stamp {
            position: absolute;
            bottom: 12px;
            right: 12px;
            width: 60px;
            opacity: 0.35;
        }

        .digital-note {
            position: absolute;
            bottom: 6px;
            left: 14px;
            font-size: 9px;
            color: #555;
            font-style: italic;
        }
    </style>
</head>
<body>

<div class="card">
    {{-- Logo --}}
    <img src="{{ public_path('images/mumbokenya.png') }}" class="logo" alt="MUMBO Logo">
    <div class="title">MUMBO Member ID Card</div>

    {{-- Info --}}
    <div class="info">
        <strong>Name:</strong> {{ optional($shareholder->customer)->full_name ?? '—' }}
    </div>
    <div class="info">
        <strong>ID Number:</strong> {{ $shareholder->id_number ?? '—' }}
    </div>
    <div class="info">
        <strong>Shareholder No:</strong> {{ $shareholder->shareholder_number ?? '—' }}
    </div>
    <div class="info">
        <strong>Email:</strong> {{ $shareholder->email ?? optional($shareholder->customer)->email ?? '—' }}
    </div>
    <div class="info">
        <strong>Phone:</strong> {{ $shareholder->phone ?? optional($shareholder->customer)->phone ?? '—' }}
    </div>
    <div class="info">
        <strong>Status:</strong>
        @if(optional($shareholder)->is_active)
            <span style="color: #15803d; font-weight: bold;">Active</span>
        @else
            <span style="color: #dc2626; font-weight: bold;">Inactive</span>
        @endif
    </div>
    <div class="info">
        <strong>Issued:</strong> {{ now()->format('d M Y') }}
    </div>

    {{-- Stamp inside the card --}}
    <img src="{{ public_path('images/digi.png') }}" class="stamp" alt="Digital Stamp">
    <div class="digital-note">Digitally Issued</div>
</div>

</body>
</html>
