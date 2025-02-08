<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Income Statement</title>
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
        Income Statement for Year: {{ $selectedYear }}
    @elseif($selectedFilter == 'Monthly')
        Income Statement for {{ date('F', mktime(0, 0, 0, $selectedMonth, 1)) }} {{ $selectedYear }}
    @elseif($selectedFilter == 'Custom')
        Income Statement from 
        {{ \Carbon\Carbon::parse($startDate)->format('d-M-Y') }} to {{ \Carbon\Carbon::parse($endDate)->format('d-M-Y') }}
    @else
        All Transactions Income Statement
    @endif
</h2>

@if(count($transactions) > 0)
    <table>
        
        <thead>
            <th>Cash in / Income</th>
            <th>Cash out / Expense</th>
            <th>Profit/Loss</th>
        </thead>
        <tbody>
            <tr>
                
                <td>{{ number_format($totalIncome, 2) }}</td>
                <td>{{ number_format($totalExpense, 2) }}</td>
                <td>{{ number_format($totalBalance, 2) }}</td>
            </tr>
        </tbody>
    </table>
@else
    <p class="text-center">No transactions found for the selected period.</p>
@endif

<div class="footer">
    <p>Generated on: {{ \Carbon\Carbon::now()->format('d-M-Y H:i:s') }}</p>
</div>

</body>
</html>
