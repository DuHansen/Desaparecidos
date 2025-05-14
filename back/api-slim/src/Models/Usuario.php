<?php
namespace App\Models;
use App\Config\Database;

class Usuario {
    private $db;
    public function __construct() {
        $this->db = Database::connect();
    }

    public function criar($dados) {
        $senhaHash = password_hash($dados['senha'], PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO usuarios (nome, email, rule, telefone, senha) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([
            $dados['nome'],
            $dados['email'],
            $dados['rule'],
            $dados['telefone'],
            $senhaHash
        ]);
    }

    public function autenticar($email, $senha) {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user && password_verify($senha, $user['senha'])) {
            return $user;
        }
        return false;
    }
}
