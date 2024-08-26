<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        h1,
        p {
            text-align: center;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            text-align: center;
            padding: 8px;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .footer {
            position: fixed;
            bottom: 20px;
            right: 20px;
            font-size: 12px;
        }
    </style>
</head>

<body>
    <h1>Product Received Report</h1>
    @if ($startDate && $endDate)
        <p>Date Range : {{ $startDate }} - {{ $endDate }}
        <p>
    @endif


    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Transaction Code</th>
                <th>Date</th>
                <th>Product Name</th>
                <th>Stock In</th>
                <th>Supplier</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->product_transaction }}</td>
                    <td>{{ $item->date }}</td>
                    <td>{{ $item->product_name }} </td>
                    <td>{{ $item->stock_in }} </td>
                    <td>{{ $item->supplier->supplier }} </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Printed by : {{ auth()->user()->name }}<br>
        Date : {{ date('d-m-Y') }}
    </div>
</body>

</html>
