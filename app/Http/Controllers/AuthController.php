<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    //
    ///////////////// REGISTRO ///////////////////////

    public function register(Request $request)
    {

        try {

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:6',
            ]);

            if($validator->fails()){
                return response()->json($validator->errors()->toJson(),400);
            }

            $user = User::create([
                'name' => $request->get('name'),
                'lastname' => $request->get('lastname'),
                'email' => $request->get('email'),
                'password' => bcrypt($request->password),
            ]);

            //AÑADIR A LA TABLA ROLE_USERS EL USUARIO CREADO Y ASIGNARLE UN ROL DE LA TABLA ROLE
            // $user->roles()->attach(1);

            $token = JWTAuth::fromUser($user);

            return response()->json(compact('user','token'),201);

        } catch (\Exception $e) {

            Log::error($e->getMessage());
            return response()->json(['error' => 'Error al crear el usuario'], 500);

        }

    }


    ///////////////// LOGIN ///////////////////////

    public function login(Request $request)
    {

        try {
        
            $input = $request->only('email', 'password');
            $jwt_token = null;

            if (!$jwt_token = JWTAuth::attempt($input)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Email or Password',
                ], Response::HTTP_UNAUTHORIZED);
            }

            return response()->json([
                'success' => true,
                'token' => $jwt_token,
                'user' => JWTAuth::user(),
            ]);

        } catch (\Exception $e) {

            Log::error($e->getMessage());
            return response()->json(['error' => 'Error al realizar Login'], 500);

        }
    }


    ///////////////// PERFIL ///////////////////////

    public function me()
    {

        try{

            return response()->json(auth()->user());

        } catch (\Exception $e) {

            Log::error($e->getMessage());
            return response()->json(['error' => 'Error al obtener el usuario'], 500);
        }

    }


    ///////////////// LOGOUT ///////////////////////

    public function logout(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        try {
            JWTAuth::invalidate($request->token);

            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully'
            ]);

        } catch (\Exception $exception) {

            Log::error($e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    //Controlador para modificacion de los datos del usuario
    public function update(Request $request, $id)
    {
        try {

            // validator
            $userId = auth()->user()->id;

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'lastname' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:email',
            ]);

            $user = User::find($userId);

            $user->name = $request->name;
            $user->lastname = $request->lastname;
            $user->email = $request->email;

            $user->save();

            return response()->json(compact('user'),200);

        } catch (\Exception $e) {

            Log::error($e->getMessage());
            return response()->json(['error' => 'Error al actualizar el usuario'], 500);

        }
    }
    
}
