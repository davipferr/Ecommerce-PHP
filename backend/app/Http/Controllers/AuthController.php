<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function refreshAccessToken(Request $request) {
        $request->user()->token()->revoke();

        $token = $request->user()->createToken($request->user()->name);

        return response()->json([
            'token' => $token->accessToken
        ]);
    }
}
