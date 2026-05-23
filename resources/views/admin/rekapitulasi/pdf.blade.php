<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <title>Rekapitulasi Penjualan</title>

    <style>

        body{
            font-family: sans-serif;
            padding:20px;
        }

        h1{
            text-align:center;
            margin-bottom:5px;
        }

        p{
            text-align:center;
            margin-bottom:30px;
            color:gray;
        }

        table{
            width:100%;
            border-collapse: collapse;
        }

        th{
            background:#111827;
            color:white;
        }

        th, td{
            border:1px solid #d1d5db;
            padding:10px;
            font-size:12px;
            text-align:left;
        }

        tr:nth-child(even){
            background:#f9fafb;
        }

    </style>
</head>
<body>

    <h1>Rekapitulasi Penjualan</h1>

    <p>
        Dimsum App - {{ now()->format('d M Y') }}
    </p>

    <table>

        <thead>

            <tr>
                <th>No</th>
                <th>Customer</th>
                <th>Total</th>
                <th>Status</th>
                <th>Tanggal</th>
            </tr>

        </thead>

        <tbody>

            @foreach($orders as $order)

            <tr>

                <td>{{ $loop->iteration }}</td>

                <td>
                    {{ $order->customer_name }}
                </td>

                <td>
                    Rp {{ number_format($order->total_price) }}
                </td>

                <td>
                    {{ ucfirst($order->status) }}
                </td>

                <td>
                    {{ $order->created_at->format('d M Y H:i') }}
                </td>

            </tr>

            @endforeach

        </tbody>

    </table>

</body>
</html>