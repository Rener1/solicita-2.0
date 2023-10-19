<?php

use App\Http\Controllers\ProcessoController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\AlunoController;
use App\Http\Controllers\BibliotecaController;
use App\Http\Controllers\UsuarioController;
use App\Http\Controllers\UnidadeController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\AdministradorController;
use App\Http\Controllers\ServidorController;
use App\Http\Controllers\RequisicaoController;
use App\Http\Controllers\PerfilAlunoController;
use App\Http\Controllers\BibliotecarioController;
use App\Models\Processo;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

//----------------------------------------------USUARIO----------------------------------------------------------------
Route::get('/', [AlunoController::class,'index'])->name('login');
Auth::routes(['verify' => true]);

// ---------------------------------------------ALUNO-------------------------------------------------------------------
Route::get('/cadastro',[AlunoController::class,'createAluno'])->name('cadastro');
Route::post('/cadastro',[AlunoController::class,'storeAluno'])->name('cadastro');
Route::get('/load-cursos/{id}',[AlunoController::class, 'loadCursos'])->name('loadCursos');

Route::get('/tratamento', [ProcessoController::class, 'tratamento'])->name('tratamento.create');
Route::get('/tratamento/form', [ProcessoController::class, 'tratamentoform'])->name('formtratamento.create');

Route::post('/tratamento/store', [ProcessoController::class, 'SolicitarTratamentoExcepcional'])->name('tratamento.store');
//----------------------------------------------ADMINISTRADOR-----------------------------------------------------------
Route::group(['middleware'=> ['CheckAdministrador', 'verified']], function(){
    Route::get('/home-administrador',[AdministradorController::class, 'index'])->name('home-administrador')->middleware('CheckAdministrador');
    Route::get('/cadastro-servidor',[ServidorController::class, 'homeServidor'])->name('cadastro-servidor')->middleware('CheckAdministrador');
    Route::post('/confirmacao-servidor',[ServidorController::class, 'storeServidor'])->name('confirmacao-servidor')->middleware('CheckAdministrador');
    Route::get('/cancela-cadastro',[ServidorController::class, 'cancel'])->name('cancela-cadastro')->middleware('CheckAdministrador');
    Route::get('/cadastro-biblioteca',[BibliotecaController::class,'createBiblioteca'])->name('cadastro-biblioteca');
    Route::post('/cadastro-biblioteca',[BibliotecaController::class,'storeBiblioteca'])->name('criar-biblioteca');
    Route::get('/cadastro-bibliotecario',[BibliotecarioController::class,'createBibliotecario'])->name('cadastro-bibliotecario');
    Route::post('/criar-bibliotecario',[BibliotecarioController::class,'storeBibliotecario'])->name('criar-bibliotecario');
    Route::get('/editar-biblioteca',[BibliotecaController::class,'editarBiblioteca'])->name('editar-biblioteca');
    Route::post('/editar-biblioteca',[BibliotecaController::class,'atualizarBiblioteca'])->name('atualizar-biblioteca');
    Route::get('/listar-bibliotecas',[BibliotecaController::class,'listarBiblioteca'])->name('listar-bibliotecas');
    Route::get('/listar-usuario',[UsuarioController::class,'listarUsuario'])->name('listar-usuario');
});

Route::group(['middleware'=> ['CheckAdministradorServidor', 'verified']], function(){
    Route::get('/editar-usuario',[UsuarioController::class,'editarUsuario'])->name('editar-usuario');
    Route::post('/editar-usuario',[UsuarioController::class,'atualizarUsuario'])->name('atualizar-usuario');
    Route::get('/cadastro-campus',[UnidadeController::class,'createCampus'])->name('cadastro-campus');
    Route::post('/cadastro-campus',[UnidadeController::class,'storeCampus'])->name('criar-campus');
    Route::get('/gerenciar-campi',[UnidadeController::class, 'gerenciarCampi'])->name('gerenciar-campi');
    Route::get('/cadastro-curso',[CursoController::class,'createCurso'])->name('cadastro-curso');
    Route::post('/cadastro-curso',[CursoController::class,'storeCurso'])->name('criar-curso');
    Route::get('/listar-cursos',[CursoController::class,'listarCursos'])->name('listar-cursos');
    Route::get('/editar-cursos',[CursoController::class,'editarCurso'])->name('editar-curso');
    Route::post('/editar-cursos',[CursoController::class,'atualizarCurso'])->name('atualizar-curso');


});

//----------------------------------------------SERVIDOR----------------------------------------------------------------
Route::group(['middleware'=> ['CheckServidor', 'verified']], function(){

    // Route::post('/filtrar-requisicoes/{curso_id?}', 'RequisicaoController@filtrarCurso')->name('filtrar-requisicoes-post')->middleware('CheckServidor');

    Route::post('/indefere-requisicoes/{requisicao_id?}', [RequisicaoController::class, 'indeferirRequisicao'])->name('indefere-requisicoes-post')->middleware('CheckServidor');
    Route::get('/listar-requisicoes', [RequisicaoController::class, 'getRequisicoes'])->name('listar-requisicoes')->middleware('CheckServidor');
    Route::get('/relatorio-requisicoes',[RequisicaoController::class, 'exibirBusca'])->name('relatorio-requisicoes')->middleware('CheckServidor');
    Route::get('/listar-requisicoes-aluno-servidor/{id}',[RequisicaoController::class, 'listarRequisicoesAluno'])->name('listar-requisicoes-servidor')->middleware('CheckServidor');
    Route::post('/relatorio-requisicoes',[RequisicaoController::class, 'gerarRelatorio'])->name('listar-relatorio-post')->middleware('CheckServidor');
    Route::get('/pesquisar-aluno',[RequisicaoController::class, 'exibirPesquisa'])->name('pesquisar-aluno')->middleware('CheckServidor');
    Route::post('/pesquisar-aluno',[RequisicaoController::class, 'pesquisarAluno'])->name('pesquisar-aluno-post')->middleware('CheckServidor');
    Route::post('/listar-requisicoes',[RequisicaoController::class, 'concluirRequisicao'])->name('listar-requisicoes-post')->middleware('CheckServidor');
    Route::get('/home-servidor',[ServidorController::class, 'index'])->name('home_servidor')->middleware('CheckServidor');
    Route::get('/home-servidor',[RequisicaoController::class, 'index'])->name('cadastro-servidor')->middleware('CheckServidor');
    Route::post('/novo-servidor',[RequisicaoController::class, 'storeServidor'])->name('novo-servidor')->middleware('CheckServidor');
    Route::get('/alterar-senha-servidor',[ServidorController::class, 'alterarSenhaServidor'])->name('alterar-senha-servidor')->middleware('CheckServidor');
    Route::post('/alterar-senha-servidor',[ServidorController::class, 'storeAlterarSenhaServidor'])->name('alterar-senha-servidor')->middleware('CheckServidor');
    Route::get('/home-servidor',[RequisicaoController::class, 'index'])->name('home_servidor')->middleware('CheckServidor');
    Route::get('/listar-alunos', [UsuarioController::class, 'listarAlunos'])->name('listar_alunos');

});
//----------------------------------------------ALUNO---------------------------------------------------
// Route::group(['middleware'=> 'verified'], function(){
Route::group(['middleware' => 'CheckAluno'], function(){
    Route::get('/home-aluno', [AlunoController::class, 'index'])->name('home-aluno')->middleware('CheckAluno');
    Route::get('/home-aluno',[AlunoController::class, 'homeAluno'])->name('home-aluno')->middleware('CheckAluno');
    Route::get('/listar-requisicoes-aluno',[AlunoController::class, 'listarRequisicoes'])->name('listar-requisicoes-aluno')->middleware('CheckAluno');
    Route::post('/confirmacao-requisicao', [RequisicaoController::class, 'novaRequisicao'])->name('confirmacao-requisicao')->middleware('CheckAluno'); //------------
    Route::post('/finaliza-requisicao', [RequisicaoController::class, 'finalizaRequisicao'])->name('finaliza-requisicao')->middleware('CheckAluno');
    Route::get('/cancela-requisicao', [RequisicaoController::class, 'cancelaRequisicao'])->name('cancela-requisicao')->middleware('CheckAluno');
    Route::get('/prepara-requisicao', [RequisicaoController::class, 'preparaNovaRequisicao'])->name('prepara-requisicao')->middleware('CheckAluno');
    Route::get('/prepara-requisicao-bibli', [RequisicaoController::class, 'preparaNovaRequisicaoBibli'])->name('prepara-requisicao-bibli');
    Route::post('/excluir-requisicao/{id}',[RequisicaoController::class, 'excluirRequisicao'])->name('excluir-requisicao');

    Route::post('/cadastrar-documento-bibli', [RequisicaoController::class, 'cadastrarDocumento'])->name('cadastrarDocumento');
    Route::post('/criar-doc-bibli', [RequisicaoController::class, 'criarDocumento'])->name('criarDocumentoBibli');
    Route::post('/criar-nada-consta', [RequisicaoController::class, 'criarNadaConsta'])->name('criarNadaConsta');
    Route::post('/criar-deposito', [RequisicaoController::class, 'criarDeposito'])->name('criarDeposito');

    Route::get('/confirmacao-requisicao',function(){
        return view('autenticacao.confirmacao-requisicao');
    })->name('confirmacao-requisicao')->middleware('CheckAluno');
    Route::get('/perfil-aluno',[PerfilAlunoController::class, 'index'])->name('perfil-aluno')->middleware('CheckAluno');
    Route::get('/editar-perfil',[PerfilAlunoController::class, 'editarInfo'])->name('editar-info')->middleware('CheckAluno');
    Route::get('/exibir-perfil-aluno',[PerfilAlunoController::class, 'editarInfo'])->name('exibir-perfil-aluno')->middleware('CheckAluno');
    Route::post('/editar-perfil',[PerfilAlunoController::class, 'storeEditarInfo'])->name('editar-info')->middleware('CheckAluno');
    Route::post('/excluir-perfil{idPerfil?}',[PerfilAlunoController::class, 'excluirPerfil'])->name('excluir-perfil')->middleware('CheckAluno');
    Route::post('/perfil-padrao{idPerfilPadrao?}',[PerfilAlunoController::class, 'definirPerfilDefault'])->name('perfil-padrao')->middleware('CheckAluno');
    Route::get('/adiciona-perfil', [PerfilAlunoController::class, 'adicionaPerfil'])->name('adiciona-perfil')->middleware('CheckAluno');
    Route::post('/salva-novo-perfil-aluno', [PerfilAlunoController::class, 'salvaPerfil'])->name('salva-novo-perfil-aluno')->middleware('CheckAluno');
    Route::post('/salva-novo-perfil-aluno', [PerfilAlunoController::class, 'salvaPerfil'])->name('salva-novo-perfil-aluno')->middleware('CheckAluno');
    Route::get('/alterar-senha',[PerfilAlunoController::class, 'alterarSenha'])->name('alterar-senha')->middleware('CheckAluno');
    Route::post('/alterar-senha',[PerfilAlunoController::class, 'storeAlterarSenha'])->name('alterar-senha')->middleware('CheckAluno');
    Route::get('/formulario-requisicao',[RequisicaoController::class, 'index'])->name('formulario-requisicao')->middleware('CheckAluno');
    Route::post('/formulario-requisicao',[RequisicaoController::class, 'storeRequisicao'])->name('formulario-requisicao-post')->middleware('CheckAluno');
    Route::get('aluno/{requisicao_id}/gerar-ficha',[BibliotecarioController::class, 'gerarFicha'])->name('gerar-ficha-aluno')->middleware('CheckFichaAluno');

    Route::get('/baixar-nada-consta/{requisicao_documento}', [BibliotecarioController::class, 'baixarNadaConsta'])->name('baixar-nada-consta-aluno');
    Route::get('/baixar-deposito/{requisicao_documento}', [BibliotecarioController::class, 'baixarDeposito'])->name('baixar-deposito-aluno');
    Route::get('/baixar-retificacao/{retificacao}', [BibliotecarioController::class, 'baixarRetificacao'])->name('baixar-retificacao-aluno');
});

//----------------------------------------------BIBLIOTECARIO---------------------------------------------------
// Route::group(['middleware'=> 'verified'], function(){
Route::group(['middleware'=> ['CheckBibliotecario', 'banned']], function(){
    Route::get('/home-bibliotecario', [BibliotecarioController::class, 'index'])->name('home-bibliotecario')->middleware('CheckBibliotecario');
    Route::get('/perfil-bibliotecario',[BibliotecarioController::class, 'perfil'])->name('perfil-bibliotecario')->middleware('CheckBibliotecario');
    Route::get('/editar-bibliotecario',[BibliotecarioController::class, 'editarBibliotecario'])->name('editar-bibliotecario')->middleware('CheckBibliotecario');
    Route::post('/atualizar-bibliotecario',[BibliotecarioController::class, 'atualizarBibliotecario'])->name('atualizar-bibliotecario')->middleware('CheckBibliotecario');
    Route::get('/editar-senha-bibliotecario',[BibliotecarioController::class, 'editarSenha'])->name('editar-senha-bibliotecario')->middleware('CheckBibliotecario');
    Route::post('/atualizar-senha-bibliotecario',[BibliotecarioController::class, 'atualizarSenha'])->name('atualizar-senha-bibliotecario')->middleware('CheckBibliotecario');
    Route::get('/listar-fichas',[BibliotecarioController::class, 'listarSolicitacoes'])->name('listar-fichas');
    Route::get('/visualizar-ficha/{requisicao_id}',[BibliotecarioController::class, 'visualizarFicha'])->name('visualizar-ficha')->middleware('CheckBibliotecario');

    Route::get('/editar-ficha/{requisicao_id}',[BibliotecarioController::class, 'editarFicha'])->name('editar-ficha')->middleware('CheckBibliotecario');
    Route::post('/atualizar-ficha',[BibliotecarioController::class, 'atualizarFicha'])->name('atualizar-ficha')->middleware('CheckBibliotecario');
    Route::get('/editar-ficha/{requisicao_id}/rejeitar',[BibliotecarioController::class, 'rejeitarFicha'])->name('rejeitar-ficha')->middleware('CheckBibliotecario');
    Route::post('/editar-ficha/{requisicao_id}/rejeitar-ficha',[BibliotecarioController::class, 'atualizarRejeicao'])->name('atualizar-rejeicao')->middleware('CheckBibliotecario');
    Route::get('/editar-ficha/{requisicao_id}/gerar-ficha',[BibliotecarioController::class, 'gerarFicha'])->name('gerar-ficha');
    Route::get('/editar-ficha/{requisicao_id}/baixarAnexo',[BibliotecarioController::class, 'baixarAnexo'])->name('baixar-anexo');
    Route::post('/preview/{requisicao_id}', [BibliotecarioController::class, 'previewFicha'])->name('preview');

    Route::post('/atualizar-nome-nada-consta/{nadaConstaId}',[\App\Http\Controllers\RequisicaoController::class, 'EditarNomeAutorNadaConsta'])->name('atualizar-nome-nada-consta');
    Route::get('/avaliar-nada-consta/{requisicao_id}',[\App\Http\Controllers\BibliotecarioController::class, 'avaliarNadaConsta'])->name('avaliar-nada-consta')->middleware('CheckBibliotecario');
    Route::get('/editar-nada-consta/{requisicao_id}',[\App\Http\Controllers\BibliotecarioController::class, 'editarNomeNadaConsta'])->name('editar-nada-consta')->middleware('CheckBibliotecario');
    Route::get('/avaliar-nada-consta/{requisicao_id}/baixarAnexoComprovante',[\App\Http\Controllers\BibliotecarioController::class, 'baixarAnexoComprovante'])->name('baixa-anexo-comprovante');
    Route::get('/avaliar-nada-consta/{requisicao_id}/baixarAnexoTermoAceitacao',[\App\Http\Controllers\BibliotecarioController::class, 'baixarAnexoTermoAceitacao'])->name('baixar-anexo-termo-aceitacao');
    Route::get('/visualizar-nada-consta/{retificacao}/baixarAnexoRetificacao',[\App\Http\Controllers\BibliotecarioController::class, 'baixarAnexoRetificacao'])->name('baixar-anexo-retificacao');
    Route::post('/deferir-nada-consta',[\App\Http\Controllers\BibliotecarioController::class, 'deferirNadaConsta'])->name('deferir-nada-consta')->middleware('CheckBibliotecario');
    Route::post('/indeferir-nada-consta',[\App\Http\Controllers\BibliotecarioController::class, 'indeferirNadaConsta'])->name('indeferir-nada-consta')->middleware('CheckBibliotecario');
    Route::post('/retificar-requisicao-documento',[\App\Http\Controllers\BibliotecarioController::class, 'retificarRequisicaoDocumento'])->name('retificar-requisicao-documento')->middleware('CheckBibliotecario');
    Route::get('/visualizar-nada-consta/{requisicao_id}',[\App\Http\Controllers\BibliotecarioController::class, 'visualizarNadaConsta'])->name('visualizar-nada-consta')->middleware('CheckBibliotecario');
    Route::post('/gerar-nada-consta/{requisicao_documento}',[\App\Http\Controllers\BibliotecarioController::class, 'gerarNadaConsta'])->name('gerar-nada-consta')->middleware('CheckBibliotecario');

    Route::post('/atualizar-nome-deposito/{depositoId}',[\App\Http\Controllers\RequisicaoController::class, 'EditarNomeAutorDeposito'])->name('atualizar-nome-deposito');
    Route::get('/avaliar-deposito/{requisicao_id}',[\App\Http\Controllers\BibliotecarioController::class, 'avaliarDeposito'])->name('avaliar-deposito');
    Route::get('/editar-deposito/{requisicao_id}',[\App\Http\Controllers\BibliotecarioController::class, 'editarDeposito'])->name('editar-deposito');
    Route::get('/visualizar-deposito/{requisicao_id}',[\App\Http\Controllers\BibliotecarioController::class, 'visualizarDeposito'])->name('visualizar-deposito');
    Route::get('/visualizar-deposito/{requisicao}/baixar-comprovante',[\App\Http\Controllers\BibliotecarioController::class, 'baixarAnexoComprovanteDeposito'])->name('baixar-anexo-comprovante-deposito');
    Route::get('/baixar-anexo-tcc-deposito/{requisicao}',[\App\Http\Controllers\BibliotecarioController::class, 'baixarAnexoTccDeposito'])->name('baixar-anexo-tcc-deposito');
    Route::get('/baixar-anexo-autorizacao-deposito/{requisicao}',[\App\Http\Controllers\BibliotecarioController::class, 'baixarAnexoAutorizacaoDeposito'])->name('baixar-anexo-autorizacao-deposito');
    Route::get('/baixar-anexo_publicacao_parcial/{requisicao}',[\App\Http\Controllers\BibliotecarioController::class, 'baixarAnexoPublicacaoParcial'])->name('baixar-anexo_publicacao_parcial');
    Route::post('/deferir-deposito',[\App\Http\Controllers\BibliotecarioController::class, 'deferirDeposito'])->name('deferir-deposito');
    Route::post('/indeferir-deposito',[\App\Http\Controllers\BibliotecarioController::class, 'indeferirDeposito'])->name('indeferir-deposito');
    Route::post('/gerar-deposito/{requisicao_documento}',[\App\Http\Controllers\BibliotecarioController::class, 'gerarDeposito'])->name('gerar-deposito');
});

// ---------------------------------------REQUISICAO------------------------------------------------------------------
Route::get('/home', [\App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('verified');
// Route::get('/mail-send', 'MailController@send');

// Route::get('/edita-perfil','PerfilController@editaPerfil')->name('edita-perfil');
// Route::get('/adiciona-perfil', 'PerfilController@adicionaPerfil')->name('adiciona-perfil');//SUPRIMIR
// Route::post('/excluir-perfil',[\App\Http\Controllers\PerfilAlunoController::class, 'excluirPerfil')->name('excluir-perfil');
// Route::get('/', 'UsuarioController@index')->name('login');
// Route::post('/confirmacao-requisicao', [\App\Http\Controllers\AlunoController::class, 'novaRequisicao')->name('confirmacao-requisicao'); //------------
// Route::post('/finaliza-requisicao', [\App\Http\Controllers\AlunoController::class, 'finalizaRequisicao')->name('finaliza-requisicao');
// Route::get('/cancela-requisicao', [\App\Http\Controllers\AlunoController::class, 'cancelaRequisicao')->name('cancela-requisicao');
// Route::get('/prepara-requisicao', [\App\Http\Controllers\AlunoController::class, 'preparaNovaRequisicao')->name('prepara-requisicao');
// Route::post('/excluir-perfil',[\App\Http\Controllers\PerfilAlunoController::class, 'excluirPerfil')->name('excluir-perfil');

