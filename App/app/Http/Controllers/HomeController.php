<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $persona = $user->persona;

        $ultimaSesion = DB::table('historiallogin')
            ->where('idUsuario', $user->id)
            ->orderBy('FechaIngreso', 'desc')
            ->skip(1) // Salta la sesión más reciente
            ->first();

        return view('home', [
            'user' => $user,
            'persona' => $persona,
            'ultimaSesion' => $ultimaSesion,
        ]);
    }
}
