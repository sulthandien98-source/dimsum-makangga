<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RekapitulasiExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Order::select(
            'customer_name',
            'total_price',
            'status',
            'created_at'
        )->latest()->get();
    }

    public function headings(): array
    {
        return [
            'Customer',
            'Total Harga',
            'Status',
            'Tanggal'
        ];
    }
}