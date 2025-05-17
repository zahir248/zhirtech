<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_name',
        'email',
        'phone',
        'service_id',
        'amount',
        'status',
        'reference_no',
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
}
