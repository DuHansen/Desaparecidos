<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Desaparecidos;

class DesaparecidosController extends Controller
{
public function index(Request $request)
{
    $itensPorPagina = $request->get('limit', 10); // padrão 10 por página
    $busca = $request->get('filtro');
    $valor = $request->get('valor');

    $query = Desaparecidos::select([
        'id',
        'nome_completo',
        'foto',
        'data_nascimento',
        'data_desaparecimento',
        'cidade',
        'estado',
        'status'
    ]);

    if ($busca && $valor) {
        if (in_array($busca, ['nome', 'cidade'])) {
            $query->where($busca === 'nome' ? 'nome_completo' : $busca, 'like', "%$valor%");
        }
    }

    return response()->json($query->paginate($itensPorPagina));
}

    /**
     * Cadastra um novo desaparecido.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'nome_completo' => 'required|string|max:100',
            'apelido' => 'nullable|string|max:50',
            'data_nascimento' => 'required|date',
            'data_desaparecimento' => 'required|date',
            'local_desaparecimento' => 'nullable|string|max:255',
            'cidade' => 'nullable|string|max:100',
            'estado' => 'nullable|string|max:2',
            'pais' => 'nullable|string|max:50',
            'altura' => 'nullable|numeric|between:0,9.99',
            'cor_pele' => 'nullable|in:branca,negra,parda,amarela,indigena,outra',
            'cor_olhos' => 'nullable|in:castanhos,azuis,verdes,pretos,outros',
            'cor_cabelo' => 'nullable|in:preto,castanho,loiro,ruivo,grisalho,branco,outro',
            'tipo_fisico' => 'nullable|in:magro,medio,robusto,obeso',
            'caracteristicas' => 'nullable|string',
            'foto' => 'nullable|string|max:255',
            'contato_responsavel' => 'nullable|string|max:100',
            'telefone_contato' => 'nullable|string|max:20',
            'email_contato' => 'nullable|email|max:100',
            'status' => 'nullable|in:desaparecido,encontrado_vivo,encontrado_falecido,outro',
            'boletim_ocorrencia' => 'nullable|string|max:50',
            'observacoes' => 'nullable|string',
            'face_embedding' => 'nullable|string',
        ]);

        $data['data_cadastro'] = now();
        $data['data_atualizacao'] = now();

        return Desaparecidos::create($data);
    }

    /**
     * Exibe um desaparecido específico.
     */
    public function show(string $id)
    {
        $registro = Desaparecidos::find($id);

        if (!$registro) {
            return response()->json(['message' => 'Desaparecido não encontrado'], 404);
        }

        return $registro;
    }

    /**
     * Atualiza um desaparecido.
     */
    public function update(Request $request, string $id)
    {
        $registro = Desaparecidos::find($id);

        if (!$registro) {
            return response()->json(['message' => 'Desaparecido não encontrado'], 404);
        }

        $validated = $request->validate([
            'nome_completo' => 'required|string|max:100',
            'cidade' => 'nullable|string|max:100',
            'foto' => 'nullable|string|max:255',
            'data_desaparecimento' => 'nullable|date'
        ]);

        $validated['data_atualizacao'] = now();

        $registro->update($validated);

        return response()->json($registro);
    }


    /**
     * Remove um desaparecido.
     */
    public function destroy(string $id)
    {
        $registro = Desaparecidos::find($id);

        if (!$registro) {
            return response()->json(['message' => 'Desaparecido não encontrado'], 404);
        }

        $registro->delete();

        return response()->json(['message' => 'Desaparecido removido com sucesso']);
    }
}
