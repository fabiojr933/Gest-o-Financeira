<?php

namespace app\controllers;

use app\core\Controller;
use app\models\Usuario;
use app\models\Pagamento;
use app\models\Despesa;
use app\models\Lancamento;
use app\models\Receita;

class LancamentoController extends Controller
{
   private $uuid;
   private $daoUsuario;
   private $daoPagamento;
   private $daoDespesa;
   private $daoReceita;
   private $daoLancamento;

   public function __construct()
   {
      $usuario = $_SESSION['SESSION_LOGIN'] ?? null;
      if (!$usuario) {
         $this->redirect(URL_BASE . 'login');
      }
      $this->uuid = $usuario->uuid;
      $this->daoUsuario = new Usuario();
      $this->daoPagamento = new Pagamento();
      $this->daoDespesa = new Despesa();
      $this->daoReceita = new Receita();
      $this->daoLancamento = new Lancamento();
   }
   public function index()
   {
      try {
         $inicio = $_POST['inicio'] ?? null;
         $fim    = $_POST['fim'] ?? null;

         $datas = [
            'inicio' => $inicio,
            'fim'    => $fim
         ];

         if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            if (!validarDatas($datas)) {
               $data  = getMesAtualInicioFim();
               $datas = getMesInicioFim($data['ano'], $data['mes']);
            } else {
               $data  = getMesAtualInicioFim();
               $datas = getMesInicioFim($data['ano'], $data['mes']);
            }
         }
         $dados['datas'] = $datas;
         $dados['dados'] = $this->daoLancamento->lancamentoAll($this->uuid, $datas);
         $dados["view"]       = "lancamento/index";
         $this->load("template", $dados);
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'Lançamento/novo');
      }
   }
   public function novo()
   {
      try {
         $dados['usuario'] = $this->daoUsuario->usuarioAll($this->uuid);
         $dados['pagamento'] = $this->daoPagamento->pagamentoAll($this->uuid);
         $dados['despesa'] = $this->daoDespesa->despesaAll($this->uuid);
         $dados['receita'] = $this->daoReceita->receitaAll($this->uuid);
         $dados["view"]       = "lancamento/novo";
         $this->load("template", $dados);
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'Lançamento/novo');
      }
   }
   public function salvar()
   {
      try {
         $valor = str_replace(',', '.', $_POST['valor']);
         $valor = (float) $valor;
         $lancamento = new \stdClass();
         $lancamento->descricao = $_POST['descricao'];
         $lancamento->id_pagamento = $_POST['id_pagamento'];
         $lancamento->data = $_POST['data'];
         $lancamento->valor = $valor;
         $lancamento->id_usuario = $_POST['id_usuario'];
         $lancamento->tipo = $_POST['tipo'];
         $lancamento->id_conta = $_POST['id_conta'];
         $lancamento->uuid = $this->uuid;

         if (isVazio($lancamento->descricao) || isVazio($lancamento->tipo || $lancamento->id_conta)) {
            setFlash('error', 'Preencha todos os campos!');
            $this->redirect(URL_BASE . 'lancamento/novo');
         }
         $this->daoLancamento->novoLancamento($lancamento);
         setFlash('success', 'Lançamento realizado com sucesso!');
         $this->redirect(URL_BASE . 'lancamento/novo');
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'Lançamento/novo');
      }
   }

   public function excluir($id)
   {
      try {
         if (isVazio($id)) {
            setFlash('error', 'Precisa informar um ID !');
            $this->redirect(URL_BASE . 'pagamento/index/');
         }

         $this->daoLancamento->excluir($id);
         setFlash('success', 'Lançamento excluido  com sucesso!');
         $this->redirect(URL_BASE . 'lancamento/index');
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'lancamento/index');
      }
   }
   public function visualizar($id)
   {
      try {
         if (isVazio($id)) {
            setFlash('error', 'Precisa informar um ID !');
            $this->redirect(URL_BASE . 'lançamento/visualizar');
         }

         $dados['dados'] = $this->daoLancamento->visualizar($id);
         $dados["view"]       = "lancamento/visualizar";
         $this->load("template", $dados);
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'lancamento/index');
      }
   }
}
