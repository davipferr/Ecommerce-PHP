<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public static function validateRefreshToken($refreshTokenExpirationTime) {

        if (!$refreshTokenExpirationTime || $refreshTokenExpirationTime < date('Y-m-d H:i:s', strtotime('-3 hour'))) {

            return false;

        }

        return true;
    }

    public static function newExpirationTime($add_time = null, $time_type = null) {
        $newExpirationTime = date('Y-m-d H:i:s', strtotime("-3 hour, {$add_time} {$time_type}"));

        return $newExpirationTime;
    }

    public static function addNewExpirationTime($client_id, $new_token_expiration) {
        $addNewExpirationTime = ClientAccessToken::where('client_id', $client_id)->update([
            'access_token_expiration_time' => $new_token_expiration,
        ]);

        if (!$addNewExpirationTime) {
            return false;
        }

        return true;
    }

    public static function createAccessToken($client_id, $new_access_token, $new_expiration_time) {

        $add_token = new ClientAccessToken([
            'client_id' => $client_id,
            'access_token' => $new_access_token,
            'access_token_expiration_time' => $new_expiration_time,
        ]);

        $add_token->save();

        if (!$add_token) {
            return false;
        }

        return true;
    }

    public static function addRefreshToken($client_id, $new_refresh_token, $new_expiration_time) {

        $add_refresh_token = ClientAccessToken::where('client_id', $client_id)
                                        ->first()
                                        ->update([
                                            'refresh_token' => $new_refresh_token,
                                            'refresh_token_expiration_time' => $new_expiration_time,
                                        ]);

        if (!$add_refresh_token) {
            return false;
        }

        return true;
    }

    public static function deleteAllClientTokens($client_id) {
        $delete_all_tokens = ClientAccessToken::where('client_id', $client_id)
                                    ->first()
                                    ->delete();
        if (!$delete_all_tokens) {
            return false;
        }

        return true;
    }
}
