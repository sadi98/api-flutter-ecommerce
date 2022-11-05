<?php

namespace App\Http\Controllers\API;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use Laravel\Fortify\Rules\Password;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required','string','max:255'],
                'username' => ['required','string','max:255','unique:users'],
                'email' => ['required','string','email','max:255','unique:users'],
                'phone_number' => ['nullable','string','max:255'],
                'password' => ['required','string', new Password],  // new password bawaan laravel fortify karna install (jetstream)
            ]);
            User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'password' => Hash::make($request->password),
            ]);
            $user = User::where('email',$request->email)->first();  /* query menampilkan email user ketika berhasil register */

            /* membuat token api dengan createToken bawaan laravel fortify karna install (jetstream)*/
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer', /* Bearer tidak ada makna hanya penamaan di token Api */
                'user' => $user
            ], 'User Register Successful');
        } catch(Exception $error) {     /* jika gagal maka akan di tangkap oleh Exception */
            return ResponseFormatter::error([
                'message' => 'Something Went Wrong',
                'error' => $error
            ], 'Authentication Failed', 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $request->validate([
                'email' => 'email|required',
                'password' => 'required'
            ]);
            $credentials = request(['email', 'password']);
            if(!Auth::attempt($credentials)) {
                return ResponseFormatter::error([
                    'message' => 'Unauthorized'
                ], 'Authentication Failed', 500);
            }

            $user = User::where('email', $request->email)->first();

            if(!Hash::check($request->password, $user->password,[]))    {       /* Kredensial tidak valid */
                throw new \Exception('Invalid Credentials');
            }
            $tokenResult = $user->createToken('authToken')->plainTextToken;
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'Authenticated');
        } catch (\Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Shomething Went Wrong',
                'error' => $error
            ], 'Authentication Failed', 500);
        }
    }

    public function fetch(Request $request)
    {
        /* method ini untuk mengambil data user yg sudah login */
        return ResponseFormatter::success($request->user(), 'Data profile user berhasil diambil');
    }
    public function updateProfile(Request $request)
    {
        $validateData = $request->validate([
            'name' => ['required','string','max:255'],
            'username' => ['required','string','max:255','unique:users'],
            'email' => ['required','string','email','max:255','unique:users'],
            'phone_number' => ['nullable','string','max:255'],
            'password' => ['string', new Password],  // new password bawaan laravel fortify karna install (jetstream)
        ]);
        $user = Auth::user();  /* Auth::user() berfungsi untuk mengambil data user yg sedang login */
        $user->update($validateData);

        /* return ke json */
        return ResponseFormatter::success($user, 'Profile Updated Successfull');
    }

    public function logout(Request $request)
    {
        $token =$request->user()->currentAccessToken()->delete();
        return ResponseFormatter::success($token, 'Token Revoked');
    }
}
