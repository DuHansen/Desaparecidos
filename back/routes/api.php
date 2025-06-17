<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

// Agrupe as rotas que precisam de session com o middleware 'web'
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

     Route::post('/logout', function (\Illuminate\Http\Request $request) {
        $request->session()->invalidate();      // Destroi a sessão
        $request->session()->regenerateToken(); // Gera um novo CSRF token

        return response()->json([
            'success' => true,
            'message' => 'Logout realizado com sucesso'
        ]);
    });
});

