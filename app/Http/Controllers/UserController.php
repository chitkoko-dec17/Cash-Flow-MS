<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function profile()
    {
        $user = User::find(Auth::id());
        return view('cfms.user.edit', compact('user'));
    }

    public function updateprofile(Request $request)
    {
        //Validation
        $request->validate([
            'phone' => 'required',
            'address' => 'required',
        ]);

        //Update the new data
        User::whereId(auth()->user()->id)->update([
            'phone' => $request->phone,
            'address' => $request->address
        ]);

        return back()->with("success", "User Info updated successfully!");

    }

    public function updatePassword(Request $request)
    {
        //Validation
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|min:6',
            'confirmpassword' => 'required|same:new_password',
        ]);

        //Match The Old Password
        if(!Hash::check($request->old_password, auth()->user()->password)){
            return back()->with("error", "Old Password Doesn't match!");
        }

        //Update the new Password
        User::whereId(auth()->user()->id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with("success", "Password changed successfully!");
    }

    //user edit
    public function user_profile($id){

        $user = User::find($id);
        return view('cfms.user.user_password_edit', compact('user'));
    }

    public function user_updatePassword(Request $request, $id)
    {
        //Validation
        $request->validate([
            'new_password' => 'required|min:6',
            'confirmpassword' => 'required|same:new_password',
        ]);

        //Update the new Password
        User::whereId($id)->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with("success", "Password changed successfully!");
    }
}
