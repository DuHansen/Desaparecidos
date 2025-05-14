<?php
namespace App\Models;

use App\Config\Database;
use PDO;

class Postagem
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function criar($data)
    {
        $sql = "INSERT INTO postagens (idusuario, imagem, video, titulo, descricao, flag) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['idusuario'],
            $data['imagem'],
            $data['video'],
            $data['titulo'],
            $data['descricao'],
            $data['flag']
        ]);
    }

    public function listarTodos()
    {
        $stmt = $this->db->query("SELECT * FROM postagens");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM postagens WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizar($id, $data)
    {
        $sql = "UPDATE postagens SET imagem=?, video=?, titulo=?, descricao=?, flag=? WHERE id=?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['imagem'],
            $data['video'],
            $data['titulo'],
            $data['descricao'],
            $data['flag'],
            $id
        ]);
    }

    public function deletar($id)
    {
        $stmt = $this->db->prepare("DELETE FROM postagens WHERE id = ?");
        $stmt->execute([$id]);
    }
}
