<?php

namespace app\controllers;

use app\core\Controller;
use app\models\Usuario;
use app\models\Pagamento;
use app\models\Despesa;
use app\models\Receita;

class LancamentoController extends Controller
{
   private $uuid;
   private $daoUsuario;
   private $daoPagamento;
   private $daoDespesa;
   private $daoReceita;

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
   }
   public function index()
   {
      $dados["view"]       = "lancamento/index";
      $this->load("template", $dados);
   }
   public function novo()
   {
      $dados['usuario'] = $this->daoUsuario->usuarioAll($this->uuid);
      $dados['pagamento'] = $this->daoPagamento->pagamentoAll($this->uuid);
      $dados['despesa'] = $this->daoDespesa->despesaAll($this->uuid);
      $dados['receita'] = $this->daoReceita->receitaAll($this->uuid);
      $dados["view"]       = "lancamento/novo";
      $this->load("template", $dados);
   }
   public function salvar()
   {
      $lancamento = new \stdClass();
      $lancamento->descricao = $_POST['descricao'];
      $lancamento->id_pagamento = $_POST['id_pagamento'];
      $lancamento->data = $_POST['data'];
      $lancamento->valor = $_POST['valor'];
      $lancamento->id_usuario = $_POST['id_usuario'];
      $lancamento->tipo = $_POST['tipo'];
      $lancamento->id_conta = $_POST['id_conta'];
      

      dd($lancamento);
      try {
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'cliente/index');
      }
   }

   public function excluir($id)
   {
      try {
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'cliente/index');
      }
   }
}
