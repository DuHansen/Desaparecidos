<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class User extends Model
{
    protected $table = 'users'; // Certifique-se que a tabela exista no banco

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password'];

    // Método personalizado de login
    public static function login($email, $password)
    {
        $user = self::where('email', $email)->first();

        if (!$user) {
            return ['success' => false, 'message' => 'Usuário não encontrado'];
        }

        if (!Hash::check($password, $user->password)) {
            return ['success' => false, 'message' => 'Senha incorreta'];
        }

        // Aqui você poderia gerar um token se estiver usando Sanctum ou JWT
        return ['success' => true, 'user' => $user];
    }
}
