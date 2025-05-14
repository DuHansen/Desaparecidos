<?php
namespace App\Models;

use App\Config\Database;
use PDO;
class Desaparecido
{
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
    }

    public function criar($data)
    {
        $sql = "INSERT INTO desaparecidos (
            nome_completo, apelido, data_nascimento, idade, sexo, cor_raca,
            imagem, altura, peso, cor_cabelo, tipo_cabelo, cor_olhos,
            marcas_tatuagens, data_desaparecimento, local_desaparecimento,
            cidade, estado, cep, circunstancias,
            nome_contato, parentesco, telefone, email
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['nome_completo'],
            $data['apelido'],
            $data['data_nascimento'],
            $data['idade'],
            $data['sexo'],
            $data['cor_raca'],
            $data['imagem'],
            $data['altura'],
            $data['peso'],
            $data['cor_cabelo'],
            $data['tipo_cabelo'],
            $data['cor_olhos'],
            $data['marcas_tatuagens'],
            $data['data_desaparecimento'],
            $data['local_desaparecimento'],
            $data['cidade'],
            $data['estado'],
            $data['cep'],
            $data['circunstancias'],
            $data['nome_contato'],
            $data['parentesco'],
            $data['telefone'],
            $data['email']
        ]);
    }

    public function listarTodos()
    {
        $stmt = $this->db->query("SELECT * FROM desaparecidos");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function buscarPorId($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM desaparecidos WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function atualizar($id, $data)
    {
        $sql = "UPDATE desaparecidos SET
            nome_completo=?, apelido=?, data_nascimento=?, idade=?, sexo=?, cor_raca=?,
            imagem=?, altura=?, peso=?, cor_cabelo=?, tipo_cabelo=?, cor_olhos=?,
            marcas_tatuagens=?, data_desaparecimento=?, local_desaparecimento=?,
            cidade=?, estado=?, cep=?, circunstancias=?,
            nome_contato=?, parentesco=?, telefone=?, email=?
            WHERE id=?";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['nome_completo'],
            $data['apelido'],
            $data['data_nascimento'],
            $data['idade'],
            $data['sexo'],
            $data['cor_raca'],
            $data['imagem'],
            $data['altura'],
            $data['peso'],
            $data['cor_cabelo'],
            $data['tipo_cabelo'],
            $data['cor_olhos'],
            $data['marcas_tatuagens'],
            $data['data_desaparecimento'],
            $data['local_desaparecimento'],
            $data['cidade'],
            $data['estado'],
            $data['cep'],
            $data['circunstancias'],
            $data['nome_contato'],
            $data['parentesco'],
            $data['telefone'],
            $data['email'],
            $id
        ]);
    }

    public function deletar($id)
    {
        $stmt = $this->db->prepare("DELETE FROM desaparecidos WHERE id = ?");
        $stmt->execute([$id]);
    }
}
