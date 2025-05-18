<!DOCTYPE html>
<html>
<head>
    <title>Orders Export</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        table {
            width: 80%;
            margin: 0 auto; /* Center the table */
            border-collapse: collapse;
            text-align: center; /* Center text inside table */
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f2f2f2;
        }
        .header {
            margin-bottom: 20px;
        }
        .filter-info {
            margin-bottom: 15px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Orders Report</h1>
        <p>Generated on: {{ now()->setTimezone('Asia/Kuala_Lumpur')->format('Y-m-d H:i:s') }}</p>
    </div>
    
    @if($serviceFilter !== 'all')
    <div class="filter-info">
        Filtered by Service: <strong>{{ $serviceFilter }}</strong>
    </div>
    @endif
    
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Reference Number</th>
                <th>Service</th>
                <th>Customer Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Amount (RM)</th>
                <th>Created At</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $order->reference_no }}</td>
                <td>{{ $order->service->name ?? '-' }}</td>
                <td>{{ $order->customer_name }}</td>
                <td>{{ $order->phone }}</td>
                <td>{{ $order->email }}</td>
                <td>{{ number_format($order->amount, 2) }}</td>
                <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>