<?php

namespace App\Http\Controllers;
use App\Models\PostModel;
use App\Models\RoleModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Validation\Rules;
class ApiController extends Controller
{
    //
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        // return $request;
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('api_token')->plainTextToken;
            // $user->update([
            //     'token' => $token,
            // ]);
            
            return response()->json(['token' => $token], 200);
        }
    
        return response()->json(['message' => 'Unauthorized'], 401);
    }
    public function register(Request $request)
    {
        
         $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
        
        ]);
       
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        event(new Registered($user));

        Auth::login($user);
      
        return response()->noContent();
    }
    public function content(Request $request)
    {
        $user = $request->user();
        $userId = $user->id;
        $data=RoleModel::where('user_id',$userId)->select('role')->first();
        if($data['role']=='admin'){
            return PostModel::all();
        }
        else{
           
            return PostModel::where('user_id',$userId)->get();
        }
        // return response()->json(['user_id' => $userId], 200);
    }
    public function roles(Request $request)
    {
        $user=$request->user();
        $userId=$user->id;
        return RoleModel::where('user_id',$userId)->first();

    }
    public function update(Request $request, $id)
    {
        $user=$request->user();
        $role=RoleModel::where('user_id',$user->id)->first();
        $data=$request->validate([
            'min_content'=>['required','min:10'],
            'content'=>['required','min:30'],
            'photo_path'=>'required',
        ]);
        if($role->role=='admin'){
            if($id){
                $post=PostModel::find($id);
                $post->update([
                    'min_content'=>$data['min_content'],
                    'content'=>$data['content'],
                    'photo_path'=>$data['photo_path'],
                ]);
                return ['relust'=>'success'];
            }
            else{
                return ['error'=>'id not found'];
            }
        }
        else{
            if($id){
                $ok=PostModel::find($id);
                if($ok->user_id==$user->id){
                    $post=PostModel::find($id);
                    $post->update([
                        'min_content'=>$data['min_content'],
                        'content'=>$data['content'],
                        'photo_path'=>$data['photo_path'],
                    ]);
                    return ['relust'=>'success'];
                }
                else{
                    return ['result'=>'you cannot update this post'];
                }
                
            }
            else{
                return ['error'=>'id not found'];
            }
        }
    }
    public function delete(Request $request,$id)
    {
        $user=$request->user();
        $role=RoleModel::where('user_id',$user->id)->first();
        if($role->role=='admin'){
            if($id){
                $post=PostModel::findOrFail($id);
                $post->delete();
                return ['relust'=>'information is disabled'];
            }
            else{
                return ['error'=>'id not found'];
            }
        }
        else{
            if($id){
                $ok=PostModel::find($id);
                if($ok->user_id==$user->id){
                    $post=PostModel::findOrFail($id);
                    $post->delete();
                    return ['relust'=>'information is disabled'];
                }
                else{
                    return ['result'=>'you cannot delete this post'];
                }
                
            }
            else{
                return ['error'=>'id not found'];
            }
        }
        
       
    }
    public function create(Request $request)
    {
        $user=$request->user();
        $data=$request->validate([
            'min_content'=>['required','min:10'],
            'content'=>['required','min:30'],
            'photo_path'=>'required',
        ]);
        
        
        PostModel::create([
            'user_id'=>$user->id,
            'min_content'=>$data['min_content'],
            'content'=>$data['content'],
            'photo_path'=>$data['photo_path'],
        ]);
        return ['relust'=>'information saved successfully'];
        
       
    }
    public function updateRole(Request $request, $id)
    {
        $user=$request->user();
        $data=$request->validate([
            'role'=>'required',
        ]);
        $role=RoleModel::where('user_id',$user->id)->first();
        if($role->role=='admin'){
            if($id){
                $post=RoleModel::find($id);
                $post->update([
                    
                    'role'=>$data['role'],
                ]);
                return ['relust'=>'success'];
            }
            else{
                return ['error'=>'id not found'];
            }
        }
        else{
            return ['result'=>'you cannot update this role'];
        }
    }
    
    public function deleteRole(Request $request ,$id)
    {
        $user=$request->user();
       
        $role=RoleModel::where('user_id',$user->id)->first();
        if($role->role=='admin'){
            
            if($id){
                $ok=RoleModel::find($id);
                if($ok){
                    $role=RoleModel::findOrFail($id);
                    $role->delete();
                    return ['relust'=>'information is disabled'];
                }
                else{
                    return ['result'=>'User with this ID is not registered'];
                }
            }
            else{
                return ['error'=>'id not found'];
            }
        }
        else{
            
                
                
            return ['result'=>'you cannot delete this role'];
                
                
            
        }
        
    }
   
}
