<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #0f172a;
            margin: 28px;
            background: #f8fafc;
        }

        .header {
            margin-bottom: 22px;
        }

        .eyebrow {
            font-size: 11px;
            letter-spacing: .18em;
            text-transform: uppercase;
            color: #475569;
            margin: 0 0 6px;
        }

        h1 {
            font-size: 24px;
            margin: 0;
        }

        .subtitle {
            margin: 8px 0 0;
            color: #475569;
            font-size: 12px;
        }

        .meta {
            margin-top: 12px;
            font-size: 11px;
            color: #64748b;
        }

        .summary {
            display: table;
            width: 100%;
            margin: 16px 0 22px;
            border-collapse: separate;
            border-spacing: 10px;
        }

        .summary-card {
            display: table-cell;
            width: 25%;
            background: #ffffff;
            border: 1px solid #dbe2ea;
            border-radius: 12px;
            padding: 14px;
            vertical-align: top;
        }

        .summary-label {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .14em;
            color: #64748b;
            margin: 0 0 6px;
        }

        .summary-value {
            font-size: 20px;
            font-weight: 700;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: #ffffff;
        }

        th, td {
            border: 1px solid #dbe2ea;
            padding: 10px 12px;
            text-align: left;
            font-size: 11px;
        }

        th {
            background: #0f172a;
            color: #ffffff;
            font-weight: 700;
        }

        tbody tr:nth-child(even) td {
            background: #f8fafc;
        }
    </style>
</head>
<body>
    <div class="header">
        <p class="eyebrow">HRIS Export</p>
        <h1>{{ $title }}</h1>
        <p class="subtitle">{{ $subtitle ?? '' }}</p>
        <p class="meta">Generated at {{ now()->format('M d, Y H:i') }}</p>
    </div>

    @if(!empty($summary))
        <div class="summary">
            @foreach($summary as $key => $item)
                @php
                    $label = is_array($item) && array_key_exists('label', $item) ? $item['label'] : (is_string($key) ? ucfirst(str_replace('_', ' ', $key)) : 'Summary');
                    $value = is_array($item) && array_key_exists('value', $item) ? $item['value'] : $item;
                @endphp
                <div class="summary-card">
                    <p class="summary-label">{{ $label }}</p>
                    <p class="summary-value">{{ $value }}</p>
                </div>
            @endforeach
        </div>
    @endif

    <table>
        <thead>
            <tr>
                @foreach($headers as $header)
                    <th>{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @forelse($rows as $row)
                <tr>
                    @foreach($row as $cell)
                        <td>{{ $cell }}</td>
                    @endforeach
                </tr>
            @empty
                <tr>
                    <td colspan="{{ count($headers) }}">No records available.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>