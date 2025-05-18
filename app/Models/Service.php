<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'price', 'note'];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
