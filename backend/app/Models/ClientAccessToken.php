<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientAccessToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'access_token',
        'access_token_expiration_time',
        'refresh_token',
        'refresh_token_expiration_time',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public static function validateRefreshToken($client_id) {

        $refreshToken = ClientAccessToken::where('client_id', $client_id)
                                        ->select('refresh_token_expiration_time')
                                        ->first();

        if (!$refreshToken || $refreshToken < date('Y-m-d H:i:s', strtotime('-3 hour'))) {

            $deleteClientTokens = ClientAccessToken::where('client_id', $client_id)
                                                    ->first()
                                                    ->delete();

            if (!$deleteClientTokens) {
                return response()->json([
                    'errorMessage' => 'Não foi posível excluir o token de acesso',
                ], 500);
            }

            return response()->json([
                'message' => 'Token de acesso expirou. Faça login novamente',
                'tokensDeleted' => true,
            ], 401);
        }

        return true;
    }

    public static function newExpirationTime($add_time = null, $time_type = null) {
        $newExpirationTime = date('Y-m-d H:i:s', strtotime("-3 hour, {$add_time} {$time_type}"));

        return $newExpirationTime;
    }

    public static function addNewExpirationTime($client_id, $new_token) {
        $addNewExpirationTime = ClientAccessToken::where('client_id', $client_id)->update([
            'access_token_expiration_time' => $new_token,
        ]);

        if (!$addNewExpirationTime) {
            return response()->json([
                'errorMessage' => 'Não foi possível atualizar o token de acesso'
            ], 500);
        }

        return true;
    }

    public static function createAccessToken($client_id, $new_access_token) {

        $new_expiration_time = ClientAccessToken::newExpirationTime(10, "second");

        $add_token = new ClientAccessToken([
            'client_id' => $client_id,
            'access_token' => $new_access_token,
            'access_token_expiration_time' => $new_expiration_time,
        ]);

        $add_token->save();

        if (!$add_token) {
            return response()->json([
                'errorMessage' => 'Não foi possível salvar o novo token de acesso.
                Tente novamente mais tarde',
            ], 500);
        }

        return [
            'newAccessToken' => $new_access_token,
            'newExpirationTime'=> $new_expiration_time,
        ];
    }

    public static function createRefreshToken($client_id, $new_refresh_token) {
        $new_expiration_time = ClientAccessToken::newExpirationTime(60, "second");

        $add_refresh_token = ClientAccessToken::where('client_id', $client_id)
                                        ->update(
                                            'refresh_token', $new_refresh_token,
                                            'refresh_token_expiration_time', $new_expiration_time,
                                        )
                                        ->first();

        if (!$add_refresh_token) {
            return response()->json([
                'errorMessage' => 'Não foi possível salvar o token de lembrança.
                Tente novamente mais tarde',
            ], 500);
        }

        return [
            'newRefreshToken' => $new_refresh_token,
            'newExpirationTime'=> $new_expiration_time,
        ];
    }
}
