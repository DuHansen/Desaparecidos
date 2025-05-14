<?php
use Slim\App;

return function (App $app) {
    (require __DIR__ . '/user.php')($app);
    (require __DIR__ . '/postagem.php')($app);
    (require __DIR__ . '/desaparecido.php')($app);
};
