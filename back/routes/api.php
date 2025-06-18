<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Controllers\Api\DesaparecidosController;

// Rotas de login com sessão (usam o middleware web)
Route::middleware(['web'])->group(function () {
    Route::post('/login', function (Request $request) {
        $credentials = $request->only('email', 'password');
        $user = User::where('email', $credentials['email'])->first();

        if ($user && Hash::check($credentials['password'], $user->password)) {
            session([
                'user' => [
                    'id'    => $user->id,
                    'nome'  => $user->name,
                    'email' => $user->email,
                    'role'  => $user->role,
                    'login_time' => now()->format('Y-m-d H:i:s'),
                ]
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Login realizado com sucesso',
                'user' => [
                    'id'    => $user->id,
                    'nome'  => $user->name,
                    'email' => $user->email,
                    'role'  => $user->role,
                ]
            ]);
        } else {
            return response()->json([
                'success' => false,
                'error' => 'Credenciais inválidas'
            ], 401);
        }
    });

    Route::post('/logout', function (Request $request) {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'success' => true,
            'message' => 'Logout realizado com sucesso'
        ]);
    });
});


// Listar todos com filtro/paginação
Route::get('/desaparecidos', [DesaparecidosController::class, 'index']);

// Cadastrar novo desaparecido
Route::post('/desaparecidos', [DesaparecidosController::class, 'store']);

// Ver um desaparecido específico
Route::get('/desaparecidos/{id}', [DesaparecidosController::class, 'show']);

// Atualizar um desaparecido
Route::put('/desaparecidos/{id}', [DesaparecidosController::class, 'update']);
Route::post('/desaparecidos/{id}', [DesaparecidosController::class, 'update']); // compatível com override _method

// Deletar um desaparecido
Route::delete('/desaparecidos/{id}', [DesaparecidosController::class, 'destroy']);

