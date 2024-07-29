<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller


/**
 * @OA\Post(
 *     path="/api/user/create",
 *     summary="Création d'un utilisateur",
 *     security={{"bearerAuth": {}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email", "role", "telephone", "password", "name"},
 *             @OA\Property(property="email", type="string", format="email", description="Email de l'utilisateur", example="dok@gmail.com"),
 *             @OA\Property(property="role", type="integer", description="Rôle de l'utilisateur", example=1),
 *             @OA\Property(property="telephone", type="string", description="Numéro de téléphone de l'utilisateur", example="675656545"),
 *             @OA\Property(property="password", type="string", format="password", description="Le mot de passe de l'utilisateur", example="doknjhyt562@"),
 *             @OA\Property(property="name", type="string", description="Nom de l'utilisateur", example="danie")
 *         )
 *     ),
 *     @OA\Response(response="201", description="Utilisateur enregistré avec succès"),
 *     @OA\Response(response="422", description="Erreurs de validation")
 * )
 */
    {
        public function create(Request $request){
            try{
                $data=$request->validate([
                    'email'=>'required|email|unique:users',
                    'role'=>'required|integer',
                    'telephone'=>'required|unique:users',
                    'password'=>'required',
                    'name'=>'required'
                ]);
                
                $data=$request->all();
                $data['email']=$request['email'];
                $data['unique_user']= uniqid();
                $data['password']=Hash::make($request->password);
             

                $role=Auth::user()->role;
               
                if ($role!=1) {
                    return response()->json([
                        'message'=>'seul l\'admi a le role 1',
                        'status'=>false, 
                        'ud'=>Auth::user()->id
                    ]);
                    die();
                }    
             

                
                if (isset($request['email'])) {
                    if (!filter_var($request['email'], FILTER_VALIDATE_EMAIL)) {
                        return response()->json([
                            'message'=>'l\'email entree n\'est pas valide',
                            'status'=>false, 
                        ]);
                        die();
                }
               
                }

                $user=User::create($data);
                return response()->json([
                    'message'=>'User create',
                    'user_id'=>$user->id,
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
 *     path="/api/user/details",
 *     summary="Afficher tous les utilisateurs",
 *     security={{"bearerAuth": {}}},
 *     @OA\Response(response=400, description="Bad Request"),
 *     @OA\Response(response=404, description="Not Found")
 * )
 */
        public function details(Request $request){ 
            try{
                $users = User::all(['email','name','telephone','role']);//Récupération de toutes la catégories.
                    $data['id']=Auth::user()->id;
                    return response()->json([
                        'message'=>'Affichages des utilisateurs',
                        'users'=>$users,
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
 *     path="/api/user/search",
 *     summary="Rechercherche dun utilsateur",
 *     security={{"bearerAuth": {}}},
 *     @OA\Parameter(
 *         name="email",
 *         in="query",
 *         description="The email of the user to search for",
 *         required=false,
 *         @OA\Schema(
 *             type="email"
 *         )
 *     ),
 *   
 *     @OA\Response(response=400, description="Bad Request"),
 *     @OA\Response(response=404, description="Not Found")
 * )
 */
        public function search(Request $request){ 
            try{
                $request->validate([
                    'email' => 'required|string'
                ]);
                $user = User::where('email', 'like', '%' . $request['email'] . '%')
                ->first(['name', 'email', 'telephone']);
    
                if (!$user){
                    return response()->json([
                        'message'=>'Cette utilisateur n\'existe pas!',
                        'status'=>false,
                        
                    ]);
                    die();
                }
               return response()->json([
                'message'=>'Affichages des details',
                'name'=>$user,
                'statut'=>true
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
 *     path="/api/user/update",
 *     summary="mise a  d'un utilisateur",
 *     security={{"bearerAuth": {}}},
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             required={"email"},
 *             @OA\Property(property="email", type="string", format="email", description="Email de l'utilisateur", example="dok@gmail.com"),
 *             @OA\Property(property="role", type="integer", description="Rôle de l'utilisateur", example=1),
 *             @OA\Property(property="telephone", type="string", description="Numéro de téléphone de l'utilisateur", example="675656545"),
 *             @OA\Property(property="password", type="string", format="password", description="Le mot de passe de l'utilisateur", example="doknjhyt562@"),
 *             @OA\Property(property="name", type="string", description="Nom de l'utilisateur", example="danie")
 *         )
 *     ),
 *     @OA\Response(response="201", description="Utilisateur update"),
 *     @OA\Response(response="422", description="Erreurs de validation")
 * )
 */
        public function update(Request $request){
            try{
                $data=$request->all();
                $email=$request['email'];
                $user=User::find($email);
                $id=User::where('email',$request['email'])->first(['id']);
                if (!$email){
                    return response()->json([
                        'message'=>'Cette utilisateur n\'existe pas!',
                        'status'=>false,
                    ]);
                    die();
                }
                
                // verification de l'adresse Email

                if (isset($request['email'])) {
                    if (!filter_var($request['email'], FILTER_VALIDATE_EMAIL)) {
                        return response()->json([
                            'message'=>'l\'email entree n\'est pas valide',
                            'status'=>false,  
                        ]);
                        die();
                }
                }
                if (($request['role']==1)){
                    return response()->json([
                        'message'=>'seul l\'admin a le role 1',
                        'status'=>false,     
                    ]);
                    die();
                }
                $id->update($data);
                return response()->json([
                    'message'=>'User Update',
                    'status'=>true,  
                ]);
            }
            catch(\Throwable $th)
            {
                return response()->json(
                    ['message'=>$th->getMessage(),
                    'status'=>false,
                    'se'=>$data['email']
                    ] );   
            }
        }


    /** 
* @OA\Post(
    * path="/api/user/login",
    * summary="Creation d'un login",
    *  @OA\RequestBody(
    *       required=true,
    *      @OA\JsonContent(
    *           required={"email","password"},
    *           @OA\Property(property="email",type="email",description="Email de l'utilisateur",example="dok@gmail.com"),
    *            @OA\Property(property="password",type="paswword",description="le mot de passe de l'utilisateur",example="doknjhyt562@"),
    *      )
    *  ),
    * 
    * @OA\Response(response="201", description="Utilisateur enregistré avec succès"),
    * @OA\Response(response="422", description="Erreurs de validation")
    * )
    */
        public function login(Request $request)
        {
            try {
                $data=$request->validate([
                    'email'=>'required|email',
                    'password'=>'required'  
                ]);
                $credentials=$request->only('email','password');
                if(Auth::attempt($credentials))
                {
                    $user=User::where('email',$request['email'])->first(['id','name','email','telephone','role']);
                    $token=$user->createToken('Auth_token')->plainTextToken;
                    return response()->json(
                        [   'user'=>$user,
                            'token'=>$token,
                            'message'=>'Authentifacation reussi',
                            'status'=>true
                        ]);
                }
                else
                {
                    return response()->json(
                        ['message'=>'vos identifiants ne correspondent pas!',
                        'status'=>false
                        ] );
                }
               
            } catch (\Throwable $th) {
                return response()->json(
                    ['message'=>$th->getMessage(),
                    'status'=>false
                    ]);
            }
        }

        public function logout(Request $request)
        {
            try {
                // Récupérer l'utilisateur authentifié
                $data=$request->validate([
                    'email'=>'required|email',
                    'password'=>'required'  
                ]);
                $credentials=$request->only('email','password');
                if((Auth::attempt($credentials)) && Auth::user()->id)
                {
                    $user=User::where('email',$request['email'])->first(['id','name','email','telephone','role']);
                    // Supprimer tous les jetons de l'utilisateur
                    $user->tokens()->delete();
                    
                    return response()->json([
                        'message' => 'Déconnexion réussie',
                        'status' => true
                    ]);
                } else {
                    return response()->json([
                        'message' => 'Utilisateur non authentifié',
                        'status' => false,
                      
                    ]);
                }
                
            } catch (\Throwable $th) {
                return response()->json([
                    'message' => $th->getMessage(),
                    'status' => false
                ]);
            }
        }
    }
