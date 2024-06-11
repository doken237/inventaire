<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'designation',
        'description',
        'pa',
        'pv',
        'qte_alr',
        'qte_stock',
        'qte_stocktotal',
        'unique_product',
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
