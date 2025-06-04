use app\Http\Controllers\Api\AuthController;

Route::post('/login', [AuthController::class, 'login']);


