<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Desaparecidos extends Model
{
    protected $table = 'desaparecidos';

    protected $fillable = [
        'nome_completo',
        'apelido',
        'data_nascimento',
        'data_desaparecimento',
        'local_desaparecimento',
        'cidade',
        'estado',
        'pais',
        'altura',
        'cor_pele',
        'cor_olhos',
        'cor_cabelo',
        'tipo_fisico',
        'caracteristicas',
        'foto',
        'contato_responsavel',
        'telefone_contato',
        'email_contato',
        'status',
        'boletim_ocorrencia',
        'observacoes',
        'data_cadastro',
        'data_atualizacao',
        'face_embedding'
    ];
}
