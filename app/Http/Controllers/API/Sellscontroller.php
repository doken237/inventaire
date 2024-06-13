<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\Sells_product;

class Sellscontroller extends Controller
{
    public function create(Request $request){
        try{
            $data=$request->validate([
                'qte_sortie'=>'required|integer',
                'product_id'=>'required|integer'
            ]);
            $data=$request->all();
           $data['user_id']=Auth::user()->id;
           $data['product_id']=$request['product_id'];
           $data['unique_supp']=uniqid();
           $sells=Sells_product::create($data);
           
           $produit=Product::find($request['product_id']);
           $produit->qte_stock=$produit->qte_stock-$request['qte_sortie'];
           $produit->save();
           return response()->json([
                'message'=>'sortie effectuer',
                'status'=>true
            ]);
        }
        catch(\Throwable $th)
        {
            return response()->json(
                ['message'=>$th->getMessage(),
                'status'=>false
                ] );  
        }
}
}
