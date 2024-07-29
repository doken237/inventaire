<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use App\Models\Add_product;

class Addcontroller extends Controller
{
    /**
 * @OA\Post(
 *     path="/api/ajout/create",
 *     summary="Ajouter un produit",
 *     security={{"bearerAuth": {}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"qte_ajouter", "product_id"},
 *             @OA\Property(property="qte_ajouter", type="integer", description="quantite ajouter", example="30"),
 *             @OA\Property(property="product_id", type="integer", description="Identifiant du produit", example=1),
 *         )
 *     ),
 *     @OA\Response(response="201", description="produit ajouter  avec succès"),
 *     @OA\Response(response="422", description="Erreurs de validation")
 * )
 */
    public function create(Request $request){
        try{
            $data=$request->validate([
                'qte_ajouter'=>'required|integer',
                'product_id'=>'required|integer'
            ]);
            $data=$request->all();
           $data['user_id']=Auth::user()->id;
           $data['product_id']=$request['product_id'];
           $data['unique_ajout']=uniqid();
           $ajout=Add_product::create($data);
           
           $produit=Product::find($request['product_id']);
           $produit->qte_stock=$produit->qte_stock+$request['qte_ajouter'];
           $produit->qte_stocktotal=$produit->qte_stocktotal+$request['qte_ajouter'];
           $produit->save();
           return response()->json([
                'message'=>'Ajout effectuer',
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
