<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; font-size: 13px; color: #1f2937; background: white; }
        .container { max-width: 800px; margin: 0 auto; padding: 40px; }
        .header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 40px; padding-bottom: 20px; border-bottom: 2px solid #1e3a8a; }
        .company-logo { display: flex; align-items: center; gap: 12px; }
        .logo-box { width: 48px; height: 48px; background: #1e3a8a; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white; font-weight: 900; font-size: 14px; }
        .company-name { font-size: 16px; font-weight: 700; color: #1e3a8a; }
        .company-sub { font-size: 11px; color: #6b7280; }
        .invoice-title { text-align: right; }
        .invoice-title h1 { font-size: 28px; font-weight: 900; color: #1e3a8a; letter-spacing: 2px; }
        .invoice-title p { font-size: 12px; color: #6b7280; margin-top: 4px; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-bottom: 30px; }
        .info-box h3 { font-size: 11px; text-transform: uppercase; color: #6b7280; letter-spacing: 1px; margin-bottom: 8px; }
        .info-box p { margin-bottom: 2px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        thead th { background: #1e3a8a; color: white; padding: 10px 12px; text-align: left; font-size: 12px; }
        tbody td { padding: 10px 12px; border-bottom: 1px solid #f3f4f6; font-size: 13px; }
        tbody tr:hover { background: #f9fafb; }
        .total-section { margin-left: auto; width: 280px; }
        .total-row { display: flex; justify-content: space-between; padding: 6px 0; font-size: 13px; }
        .total-final { display: flex; justify-content: space-between; padding: 10px 0; font-size: 15px; font-weight: 700; border-top: 2px solid #1e3a8a; color: #1e3a8a; }
        .status-paid { display: inline-block; background: #dcfce7; color: #166534; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 600; }
        .footer { margin-top: 40px; padding-top: 20px; border-top: 1px solid #e5e7eb; display: flex; justify-content: space-between; font-size: 11px; color: #9ca3af; }
    </style>
</head>
<body>
<div class="container">

    {{-- Header --}}
    <div class="header">
        <div class="company-logo">
            <div class="logo-box">CBI</div>
            <div>
                <div class="company-name">PT Central Bearindo International</div>
                <div class="company-sub">Distributor Resmi Bearing & Power Transmission</div>
                <div class="company-sub">Jakarta, Indonesia · Sejak 1994</div>
            </div>
        </div>
        <div class="invoice-title">
            <h1>INVOICE</h1>
            <p>{{ $invoice->invoice_number }}</p>
            <p style="margin-top: 8px;">
                <span class="status-paid">{{ strtoupper($invoice->status) }}</span>
            </p>
        </div>
    </div>

    {{-- Info --}}
    <div class="info-grid">
        <div class="info-box">
            <h3>Tagihan Kepada</h3>
            <p><strong>{{ $invoice->user->name }}</strong></p>
            @if($invoice->user->company_name)
                <p>{{ $invoice->user->company_name }}</p>
            @endif
            <p>{{ $invoice->user->email }}</p>
            @if($invoice->user->phone)<p>{{ $invoice->user->phone }}</p>@endif
            @if($invoice->order->shipping_address)
                <p style="margin-top:6px; color:#6b7280; font-size:12px;">
                    {{ $invoice->order->shipping_address }},<br>
                    {{ $invoice->order->shipping_city }}, {{ $invoice->order->shipping_province }}
                </p>
            @endif
        </div>
        <div class="info-box" style="text-align:right">
            <h3>Detail Invoice</h3>
            <p><strong>No. Invoice:</strong> {{ $invoice->invoice_number }}</p>
            <p><strong>No. Pesanan:</strong> {{ $invoice->order->order_number }}</p>
            <p><strong>Tanggal:</strong> {{ $invoice->issued_date->format('d M Y') }}</p>
            <p><strong>Jatuh Tempo:</strong> {{ $invoice->due_date->format('d M Y') }}</p>
        </div>
    </div>

    {{-- Items --}}
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Produk</th>
                <th>SKU</th>
                <th style="text-align:center">Qty</th>
                <th style="text-align:right">Harga Satuan</th>
                <th style="text-align:right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->order->items as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>
                        <strong>{{ $item->product_name }}</strong><br>
                        <span style="font-size:11px; color:#6b7280">{{ $item->product->brand->name }}</span>
                    </td>
                    <td style="font-family:monospace; font-size:11px">{{ $item->product_sku }}</td>
                    <td style="text-align:center">{{ $item->quantity }}</td>
                    <td style="text-align:right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td style="text-align:right"><strong>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</strong></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Total --}}
    <div class="total-section">
        <div class="total-row">
            <span>Subtotal</span>
            <span>Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
        </div>
        <div class="total-row" style="color: #6b7280;">
            <span>PPN (11%)</span>
            <span>Rp {{ number_format($invoice->tax, 0, ',', '.') }}</span>
        </div>
        <div class="total-final">
            <span>TOTAL</span>
            <span>Rp {{ number_format($invoice->total, 0, ',', '.') }}</span>
        </div>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <div>
            <p>PT Central Bearindo International</p>
            <p>Distributor Resmi: FAG · INA · LUK · NACHI · FYH · FBJ</p>
        </div>
        <div style="text-align:right">
            <p>Dokumen ini diterbitkan secara otomatis oleh sistem.</p>
            <p>Dicetak pada {{ now()->format('d M Y H:i') }}</p>
        </div>
    </div>

</div>
</body>
</html>