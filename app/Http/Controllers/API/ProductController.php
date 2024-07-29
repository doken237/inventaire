<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
 * @OA\Post(
 *     path="/api/product/create",
 *     summary="Création d'un produit",
 *     security={{"bearerAuth": {}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"designation", "description", "pa", "pv", "qte_alr", "qte_stock"},
 *             @OA\Property(property="designation", type="string", description="Libelle du Produit", example="Souris Usb"),
 *             @OA\Property(property="description", type="string", description="desription du produit", example="souris filaire"),
 *             @OA\Property(property="pa", type="float", description="prix d'achat du produit", example=2500),
 *             @OA\Property(property="pv", type="float", description="prix de vente du produit", example=5000),
 *             @OA\Property(property="qte_alr", type="integer", description="Quantite d'alerte du produit", example=3),
 *        @OA\Property(property="qte_stock", type="integer", description="Quantite en stock du produit", example=10)
 *         )
 *     ),
 *     @OA\Response(response="201", description="produit  cree avec succes"),
 *     @OA\Response(response="422", description="Erreurs de validation")
 * )
 */
    public function create(Request $request){
        try{
            $data=$request->validate([
                'designation'=>'required|string',
                'description'=>'required|string',
                'pa'=>'required',
                'pv'=>'required',
                'qte_alr'=>'required|integer',
                'qte_stock'=>'required|integer',
            ]);
            $data=$request->all();
            if(empty($request['qte_stock'])||empty($request['qte_alr']))
            {
                $data['qte_alr']=0;
                $data['qte_stock']=0;
            }

           if (!is_numeric($request['pa'])||!is_numeric($request['pv'])||!is_numeric($request['qte_stock'])||!is_numeric($request['qte_alr']))
           {
            return response()->json(
                ['message'=>'les champs des prix et des quantites sont des nombres',
                'status'=>false
                ]);
           }

           if($request['pv']<=$request['pa'])
           {
            return response()->json(
                ['message'=>'le prix de vente ne peut etre inferieur au prix d\'achat',
                'status'=>false
                ]);
                die();
           }
          
            $data['user_id']=Auth::user()->id;
            if (Auth::user()->id==1) {
                return response()->json([
                    'message'=>'un admin n\'ajoute pas de produit',
                    'status'=>false
                ]);
                die();
            }
            $data['qte_stocktotal']=$request['qte_stock'];
            $data['unique_product']=uniqid();
            $produit=Product::create($data);
            return response()->json([
                'message'=>'Article create',
                'produit'=>$produit->id,
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



           /**
 * @OA\Get(
 *     path="/api/product/details",
 *     summary="Affichage des produits",
 *     security={{"bearerAuth": {}}},
 *     @OA\Response(response=400, description="Listes des produits"),
 *     @OA\Response(response=404, description="Not Found")
 * )
 */
    public function details(Request $request){ 
        try{
            $produits = Product::where('qte_stock', '>', 0)->get(['id','designation','pa','description','pv','qte_stock']);
        
                    return response()->json([
                        'message'=>'Affichages  des produits',
                        'produits'=>$produits,
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

    /**
 * @OA\Get(
 *     path="/api/product/search",
 *     summary="Rechercher dun produit",
 *     security={{"bearerAuth": {}}},
 *     @OA\Parameter(
 *         name="designation",
 *         in="query",
 *         description="designation du produit",
 *         required=false,
 *         @OA\Schema(
 *             type="string"
 *         )
 *     ),
 *   
 *     @OA\Response(response=400, description="Bad Request"),
 *     @OA\Response(response=404, description="Not Found")
 * )
 */
    public function search(Request $request){ 
        try{
            $data=$request->all();
           $produit=Product::where('designation','like','%'.$request['designation'].'%')->get(['user_id','description','designation','pa','pv','qte_stock']);
            if (!$produit){
                return response()->json([
                    'message'=>'Ce peoduit n\'existe pas!',
                    'status'=>false,
                    
                ]);
                die();
            }
           return response()->json([
            'message'=>'Affichages des details',
            'id'=>$produit,
            'statut'=>true,
            // 'uu'=>$qtestock
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

    /**
 * @OA\Post(
 *     path="/api/product/update",
 *     summary="Mise a jour d'un produit",
 *     security={{"bearerAuth": {}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"designation", "description", "pa", "pv", "qte_alr", "qte_stock"},
 *             @OA\Property(property="designation", type="string", description="Libelle du Produit", example="Souris Usb"),
 *             @OA\Property(property="description", type="string", description="desription du produit", example="souris filaire"),
 *             @OA\Property(property="pa", type="float", description="prix d'achat du produit", example=2500),
 *             @OA\Property(property="pv", type="float", description="prix de vente du produit", example=5000),
 *             @OA\Property(property="qte_alr", type="integer", description="Quantite d'alerte du produit", example=3),
 *        @OA\Property(property="qte_stock", type="integer", description="Quantite en stock du produit", example=10)
 *         )
 *     ),
 *     @OA\Response(response="201", description="produit  mis a jour avec succes"),
 *     @OA\Response(response="422", description="Erreurs de validation")
 * )
 */
    public function update(Request $request){
        try{
            $data=$request->all();
            $id=$request['id'];
            $user_id=Auth::user()->id;
            $produit=Product::find($id);
            if (!$produit){
                return response()->json([
                    'message'=>'Cette article n\'existe pas!',
                    'status'=>false
                ]);
                die();
            }
            $produit->update($data);
            return response()->json([
                'message'=>'article Update',
                'status'=>true
            ]);
        }
        catch(\Throwable $th)
        {
            return response()->json(
                ['message'=>$th->getMessage(),
                'status'=>false
                ]);       
        }
    }
}
