<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Persona;
use App\Models\User;
use App\Rules\NoConsecutiveDigits;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Expr\Cast\String_;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'min:8', 'max:20', 'regex:/^[A-Za-z0-9]+$/', 'regex:/[A-Z]/', 'regex:/\d/', 'unique:users'],
            'nombres' => ['required', 'string', 'max:60'],
            'apellidos' => ['required', 'string', 'max:60'],
            'identificacion' => ['required', 'numeric', 'digits:10',new NoConsecutiveDigits(4)],
            'email' => ['nullable','string', 'email', 'max:255', 'unique:users'],
            'password' => [
                'required',
                'string',
                'min:8',                       // Longitud mínima
                'confirmed',                   // Confirmación de contraseña
                'regex:/[A-Z]/',               // Al menos una letra mayúscula
                'regex:/[\W_]/',               // Al menos un signo especial
                'regex:/^\S*$/',               // No contiene espacios
            ],
        ], [
            'name.required' => 'El nombre de usuario es obligatorio.',
            'name.string' => 'El nombre de usuario debe ser una cadena de texto.',
            'name.min' => 'El nombre de usuario debe tener al menos :min caracteres.',
            'name.max' => 'El nombre de usuario no puede tener más de :max caracteres.',
            'name.regex' => 'El nombre de usuario debe contener solo letras y números, al menos una letra mayúscula y un número.',
            'name.unique' => 'El nombre de usuario ya está en uso.',
            'nombres.required' => 'El campo Nombres es obligatorio.',
            'apellidos.required' => 'El campo Apellidos es obligatorio.',
            'identificacion.required' => 'El campo Identificación es obligatorio.',
            'identificacion.digits' => 'La Identificación debe tener exactamente :digits dígitos.',
            'email.required' => 'El campo Email es obligatorio.',
            'password.required' => 'El campo Contraseña es obligatorio.',
            'password.min' => 'La contraseña debe tener al menos :min caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'password.regex' => 'La contraseña debe tener al menos una letra mayúscula, un signo especial y no debe contener espacios.',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $persona = Persona::create([
            'Nombres' => $data['nombres'],
            'Apellidos' => $data['apellidos'],
            'Identificacion' => $data['identificacion'],
        ]);

        ;

        return User::create([
            'name' => $data['name'],
            'email' => $this->definirEmail($data),
            'password' => Hash::make($data['password']),
            'Persona_idPersona2' => $persona->idPersona,
        ]);
    }

    private function definirEmail(array $data): String{
        if (!$data['email']) {
            $primerCaracterNombre = substr($data['nombres'], 0, 1);
            $apellidos = explode(' ', $data['apellidos']);
            $primerApellido = $apellidos[0];
            $primerCaracterSegundoApellido = isset($apellidos[1]) ? substr($apellidos[1], 0, 1) : '';
            $data['email'] = strtolower("{$primerCaracterNombre}{$primerApellido}{$primerCaracterSegundoApellido}@mail.com");
        }
        return $data['email'];
    }
}
