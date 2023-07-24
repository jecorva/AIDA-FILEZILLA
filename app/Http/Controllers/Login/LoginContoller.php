<?php

namespace App\Http\Controllers\Login;

use App\Http\Controllers\Controller;
use App\Models\Person;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\ValidationException;

class LoginContoller extends Controller
{
    //
    public function index()
    {
        return view('login.index');
    }

    public function verify(Request $request)
    {

        $credentials = $request->validate([
            'email' => ['required', 'email', 'string'],
            'password' => ['required', 'string']
        ]);

        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            // $request->authenticate();
            $request->session()->regenerate();

            if( Auth::user()->id != 1 ) {
                $typeEmployee = Person::select(
                    'people.typeperson_id',
                )
                    ->join('users', 'users.id', '=', 'people.user_id')
                    ->where('users.id', Auth::user()->id)
                    ->get();

                Session::put('type_employee', $typeEmployee[0]->typeperson_id);
            }else {
                Session::put('type_employee', 7);
            }

            return redirect()
                ->intended('dashboard')
                ->with('status', 'Estas logeado');
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed')
        ]);
    }

    public function logout(Request $request)
    {
        //return view('maestros.trabajador.index');
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        //session_destroy();

        return redirect()
            ->intended('/');
    }
}
