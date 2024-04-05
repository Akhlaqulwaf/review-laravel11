<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Register User",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  @OA\Property(
     *                      property="name",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="email",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="password",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="confirm_password",
     *                      type="string"
     *                  ),
     *                  example={"name": "atho", "email": "atho@gmail.com", "password": "Atho123", "confirm_password": "Atho123"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Register successfully",
     *         @OA\JsonContent(
     *             @OA\Schema(ref="#/components/schemas/TokenRequest"),
     *             @OA\Examples(example="result", value={"token": "371617ehwudhue37e673y"}, summary="An result object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid data",
     *         @OA\JsonContent()
     *     )
     * )
     */
    public function register(RegisterRequest $request){
        $data = $request->validated();

        $userGet = User::where('email', $data['email'])->first();

        if($userGet){
            throw new HttpResponseException(response([
                'error' => 'Sudah melakukan register'
            ],400));
        }

        $user = new User($data);
        $user->password = Hash::make($data['password']);
        $user->save();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token
        ],200);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Login User",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="aplication/json",
     *             @OA\Schema(
     *                  @OA\Property(
     *                      property="email",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="password",
     *                      type="string"
     *                  ),
     *                  example={"email": "atho@gmail.com", "password": "Atho123"}
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successfully",
     *         @OA\JsonContent(
     *             @OA\Schema(ref="#/components/schemas/TokenRequest"),
     *             @OA\Examples(example="result", value={"token": "371617ehwudhue37e673y"}, summary="An result object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid data",
     *         @OA\JsonContent()
     *     )
     * )
     */
    public function login(LoginRequest $request){
        $data = $request->validated();

        $user = User::where('email', $data['email'])->first();

        if(!$user || !Hash::check($data['password'], $user->password)){
            throw new HttpResponseException(response([
                'error' => 'Email atau password salah'
            ],400));
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token
        ],200);
    }


    /**
     * @OA\Delete(
     *     path="/api/logout",
     *     @OA\Response(
     *         response=200,
     *         description="Success logout",
     *         @OA\JsonContent(
     *             @OA\Schema(ref="#/components/schemas/result"),
     *             @OA\Examples(example="result", value={"message": "Success logout"}, summary="An result object")
     *         )
     *     ),
     *
     *     security={{ "sanctum": {} }}
     * )
     */
    public function logout(Request $request){
        // auth()->user()->tokens()->delete();
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Success logout'
        ],200);
    }
}
