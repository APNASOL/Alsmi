<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        h1, h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #0A8ACC;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
        }
        .footer .date-time {
            font-size: 12px;
            font-style: italic;
        }
        .footer .totals {
            margin-top: 10px;
            text-align: left;
        }
        .logo {
            text-align: center;
            margin-bottom: 20px;
        }
        .logo img {
            max-width: 150px;
        }
    </style>
</head>
<body>

{{-- Logo --}}
<div class="logo">
    <img src="{{ public_path('images/logo.png') }}" alt="Al-Shahama Hospital Logo">
</div>

{{-- Institution Title --}}
<h1>Al-Shahama Hospital Peshawar</h1>

{{-- Dynamic Title Based on Filter --}}
<h2>
    @if($selectedFilter == 'Yearly')
        Transaction Report for Year: {{ $selectedYear }}
    @elseif($selectedFilter == 'Monthly')
        Transaction Report for {{ date('F', mktime(0, 0, 0, $selectedMonth, 1)) }} {{ $selectedYear }}
    @elseif($selectedFilter == 'Custom')
        Transaction Report from 
        {{ \Carbon\Carbon::parse($startDate)->format('d-M-Y') }} to {{ \Carbon\Carbon::parse($endDate)->format('d-M-Y') }}
    @else
        All Transactions Report
    @endif
</h2>

{{-- Transactions Table --}}
@if(count($transactions) > 0)
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>{{ $selectedFilter == 'Yearly' ? 'Month' : 'Date' }}</th>
                <th>Income</th>
                <th>Expense</th>
                <th>Balance</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $index => $entry)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $entry['date'] }}</td>
                    <td>{{ number_format($entry['income'], 2) }}</td>
                    <td>{{ number_format($entry['expense'], 2) }}</td>
                    <td>{{ number_format($entry['balance'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2">Total</th>
                <th>{{ number_format($totalIncome, 2) }}</th>
                <th>{{ number_format($totalExpense, 2) }}</th>
                <th>{{ number_format($totalBalance, 2) }}</th>
            </tr>
        </tfoot>
    </table>
@else
    <p class="text-center">No transactions found for the selected period.</p>
@endif

{{-- Footer with Totals --}}
<div class="footer">
    <div class="date-time">
        <p>Generated on: {{ \Carbon\Carbon::now()->format('d-M-Y H:i:s') }}</p>
    </div>
</div>

</body>
</html>
