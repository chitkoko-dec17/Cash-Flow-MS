<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use App\Models\User;
use Hash;

class AuthController extends Controller
{
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function index()
    {
        if(Auth::check()){
            return redirect("/");
        }
        return view('cfms.authentication.login');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function registration()
    {
        // return view('content.authentications.register');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function postLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {

            // Get the user role from related model
            $user_role = Auth::user()->role->name;
            $user_business_unit_id = null;
            $user_business_unit_name = null;

            if($user_role == "Admin"){
                $user_business_unit_id = null;
            }elseif($user_role == "Manager"){
                $user_business_unit_id = isset(Auth::user()->businessunit->id) ? Auth::user()->businessunit->id : null;
                $user_business_unit_name = isset(Auth::user()->businessunit->name) ? Auth::user()->businessunit->name : null;
            }elseif($user_role == "Staff"){
                $user_business_unit_id = null;
            }

            Session::put('user_business_unit_id', $user_business_unit_id);
            Session::put('user_business_unit_name', $user_business_unit_name);

            return redirect()->intended('dashboard')
                        ->withSuccess('You have Successfully loggedin');
        }

        return redirect("login")->withSuccess('Oppes! You have entered invalid credentials');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function postRegistration(Request $request)
    {
        // $request->validate([
        //     'name' => 'required',
        //     'email' => 'required|email|unique:users',
        //     'password' => 'required|min:6',
        // ]);

        // $data = $request->all();
        // $check = $this->create($data);

        // return redirect("dashboard")->withSuccess('Great! You have Successfully loggedin');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function dashboard()
    {
        if(Auth::check()){
            return view('dashboard');
        }

        return redirect("login")->withSuccess('Opps! You do not have access');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function create(array $data)
    {
      // return User::create([
      //   'name' => $data['name'],
      //   'email' => $data['email'],
      //   'password' => Hash::make($data['password'])
      // ]);
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function logout() {
        Session::flush();
        Auth::logout();

        return Redirect('login');
    }
}
