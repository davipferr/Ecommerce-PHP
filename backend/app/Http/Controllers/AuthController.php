<?php

namespace App\Http\Controllers;

use App\Models\ClientAccessToken;
use App\Http\Requests\OnlyClientIdRequest;
use App\Http\Requests\CreateAccessTokenRequest;
use App\Http\Requests\CreateRefreshTokenRequest;
use App\Http\Requests\ValidateRefreshTokenRequest;

class AuthController extends Controller
{
    public function validateRefreshToken(ValidateRefreshTokenRequest $request) {

        $validate_refresh_token = ClientAccessToken::validateRefreshToken(
            $request->refresh_token_expiration_time
        );

        if (!$validate_refresh_token) {
            return response()->json([
                'message' => 'Refresh Token inválido!',
                'refreshTokenIsValid' => $validate_refresh_token,
            ]);
        }

        return response()->json([
            'successMessage' => 'Refresh Token válido!',
            'refreshTokenIsValid' => $validate_refresh_token,
        ]);
    }

    public function refreshAccessToken(OnlyClientIdRequest $request) {

        $newAccessTokenExpirationTime = ClientAccessToken::newExpirationTime(7, "day");

        $add_new_expiration_time = ClientAccessToken::addNewExpirationTime(
            $request->client_id,
            $newAccessTokenExpirationTime,
        );

        if (!$add_new_expiration_time) {
            return response()->json([
                'errorMessage' => 'Não foi possível Atualizar o Token de acesso',
            ], 500);
        }

        return response()->json([
            'successMessage' => 'Token de acesso atualizado!',
            'newAccessTokenExpirationTime' => $newAccessTokenExpirationTime,
        ]);
    }

    public function createAccessToken(CreateAccessTokenRequest $request) {

        $new_expiration_time = ClientAccessToken::newExpirationTime(7, "day");

        $new_token = ClientAccessToken::createAccessToken(
            $request->client_id,
            $request->new_access_token,
            $new_expiration_time,
        );

        if (!$new_token) {
            return response()->json([
                'errorMessage' => 'Não foi possível criar o Token de acesso',
            ], 500);
        }

        return response()->json([
            'successMessage' => 'Token de acesso criado!',
            'newAccessToken' => $request->new_access_token,
            'newExpirationTime'=> $new_expiration_time,
        ]);
    }

    public function addRefreshToken(CreateRefreshTokenRequest $request) {

        $new_expiration_time = ClientAccessToken::newExpirationTime(30, "day");

        $add_refresh_token = ClientAccessToken::addRefreshToken(
            $request->client_id,
            $request->new_refresh_token,
            $new_expiration_time,
        );

        if (!$add_refresh_token) {
            return response()->json([
                'errorMessage' => 'Não foi possível adicionar o refresh Token',
            ], 500);
        }

        return response()->json([
            'successMessage' => 'Token de lembrança criado!',
            'newRefreshToken' => $request->new_refresh_token,
            'newExpirationTime'=> $new_expiration_time,
        ]);
    }

    public function deleteAllClientTokens (OnlyClientIdRequest $request) {
        $delete_all_tokens = ClientAccessToken::deleteAllClientTokens($request->client_id);

        if (!$delete_all_tokens) {
            return response()->json([
                'errorMessage' => 'Não foi possível deletar os Tokens de acesso',
                'tokensDeleted' => $delete_all_tokens,
            ], 500);
        }

        return response()->json([
            'successMessage' => 'Refresh Token válido!',
            'tokensDeleted' => $delete_all_tokens,
        ]);
    }
}
