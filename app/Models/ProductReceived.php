<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReceived extends Model
{
    protected $table = 'products_received';
    protected $guarded = ['id'];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}