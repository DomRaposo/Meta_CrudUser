<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model as MongoModel;
use Laravel\Sanctum\Contracts\HasAbilities;

class PersonalAccessToken extends MongoModel implements HasAbilities
{
    protected $connection = 'mongodb';
    protected $collection = 'personal_access_tokens';

    protected $fillable = [
        'name',
        'token',
        'abilities',
        'expires_at',
        'tokenable_type',
        'tokenable_id',
    ];

    protected $hidden = [
        'token',
    ];



    public function tokenable()
    {
        return $this->morphTo('tokenable');
    }

    public static function findToken($token)
    {
        if (strpos($token, '|') === false) {
            return static::where('token', hash('sha256', $token))->first();
        }

        [$id, $token] = explode('|', $token, 2);

        if ($instance = static::find($id)) {
            // Verificar se o hash do token corresponde
            $expectedHash = hash('sha256', $token);
            return hash_equals($instance->token, $expectedHash) ? $instance : null;
        }

        return null;
    }

    public function can($ability)
    {
        $abilities = is_array($this->abilities) ? $this->abilities : json_decode($this->abilities, true);
        return in_array('*', $abilities) ||
               array_key_exists($ability, array_flip($abilities));
    }

    public function cant($ability)
    {
        return ! $this->can($ability);
    }
} 