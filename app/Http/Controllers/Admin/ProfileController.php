<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Profiler\Profile;

class ProfileController extends Controller
{

    public function profile(){
        return view('admin.profile.profile');
    }
    
    public function updateProfile(Request $request){
        $user = User::find(Auth::id());
        // dd($request->all());
       
        if ($request->file('img')) {
            $path = $request->file('img');
            $target = 'public/profile_images';
            $file = Storage::putFile($target, $path);
            $file = substr($file, 7, strlen($file) - 7);
        }

        $user->update([
            'name' => $request->name,   
            'email' => $request->email,
            'mob' => $request->mob,
            'img' => $request->img ? $file : $user->img, 
        ]);

        return back()->withSuccess('Profile Updated!');
    }

    public function updatePassword(Request $request){

        $request->validate([
            'current_password' => "required",
            "password" => "required|min:6|confirmed",
        ]);
        
        $user = Auth::user();
        $user = User::where('id',$user->id)->first();

        if(!Hash::check($request->current_password, $user->password)){
            return redirect()->back()->with('error', 'The current password is incorrect.');
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->back()->withSuccess("Password Updated Successfully!");
    }
}
