<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sells_product extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'product_id',
        'qte_sortie', 
        'unique_supp',  

    ];

    protected $guarded=['id','created_ad'];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }

    public function product(){
      
        return $this->belongsTo(Product::class,'product_id');
    }
}
