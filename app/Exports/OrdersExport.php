<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersExport implements FromCollection, WithHeadings
{
    protected $orders;

    public function __construct($orders)
    {
        $this->orders = $orders;
    }

    public function collection()
    {
        return $this->orders->values()->map(function ($order, $index) {
            return [
                $index + 1, // No
                $order->reference_no,
                $order->service->name ?? '-',
                $order->customer_name,
                $order->phone,
                $order->email,
                number_format($order->amount, 2),
                $order->created_at->format('Y-m-d H:i')
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No',
            'Reference Number',
            'Service',
            'Customer Name',
            'Phone',
            'Email',
            'Amount (RM)',
            'Created At'
        ];
    }
}
