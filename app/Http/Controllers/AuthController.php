<?php
namespace App\Http\Controllers;
use App\Models\{User,Doctor,Patient};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class AuthController extends Controller {
 public function showLogin(){return view('auth.login');}
 public function login(Request $request){$data=$request->validate(['email'=>'required|email','password'=>'required']); if(Auth::attempt($data,$request->boolean('remember'))){$request->session()->regenerate();return redirect()->intended('/dashboard');} return back()->withErrors(['email'=>'The provided details are incorrect.'])->onlyInput('email');}
 public function showRegister(){return view('auth.register');}
 public function register(Request $request){$data=$request->validate(['name'=>'required|string|max:255','email'=>'required|email|unique:users','password'=>'required|confirmed|min:6','role'=>'required|in:doctor,patient']);$data['password']=Hash::make($data['password']);$user=User::create($data);$name=explode(' ',trim($user->name),2);$profile=['user_id'=>$user->id,'first_name'=>$name[0],'last_name'=>$name[1]??'','phone'=>'Not provided','email'=>$user->email];if($user->role==='doctor'){Doctor::create(array_merge($profile,['specialization'=>'General Medicine']));}else{Patient::create(array_merge($profile,['gender'=>'other']));}Auth::login($user);return redirect('/dashboard');}
 public function logout(Request $request){Auth::logout();$request->session()->invalidate();$request->session()->regenerateToken();return redirect('/login');}
}
