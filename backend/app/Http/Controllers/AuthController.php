<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClientAccessToken;
use App\Http\Requests\RefreshAccessTokenRequest;
use App\Http\Requests\CreateAccessTokenRequest;
use App\Http\Requests\CreateRefreshTokenRequest;

class AuthController extends Controller
{
    public function refreshAccessToken(RefreshAccessTokenRequest $request) {

        $validateRefreshToken = ClientAccessToken::validateRefreshToken($request->client_id);

        if ($validateRefreshToken) {
            $newAccessTokenExpirationTime = ClientAccessToken::newAccessTokenExpirationTime();
        }

        ClientAccessToken::addNewExpirationTime(
            $request->client_id,
            $newAccessTokenExpirationTime,
        );

        return response()->json([
            'successMessage' => 'Token de acesso atualizado!',
            'newAccessTokenExpirationTime' => $newAccessTokenExpirationTime,
        ]);
    }

    public function createAccessToken(CreateAccessTokenRequest $request) {

        $new_token = ClientAccessToken::createAccessToken(
            $request->client_id,
            $request->new_access_token,
        );

        return response()->json([
            'successMessage' => 'Token de acesso criado!',
            'newAccessToken' => $new_token['newAccessToken'],
            'newExpirationTime'=> $$new_token['newExpirationTime'],
        ]);
    }

    public function createRefreshToken(CreateRefreshTokenRequest $request) {

        $new_refresh_token = ClientAccessToken::createRefreshToken(
            $request->client_id,
            $request->new_refresh_token,
        );

        return response()->json([
            'successMessage' => 'Token de lembrança criado!',
            'newRefreshToken' => $new_refresh_token['newRefreshToken'],
            'newExpirationTime'=> $new_refresh_token['newExpirationTime'],
        ]);
    }
}
