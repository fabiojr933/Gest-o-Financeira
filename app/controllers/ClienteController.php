<?php

namespace app\controllers;

use app\core\Controller;
use app\models\Cliente;

class ClienteController extends Controller
{
   private $uuid;
   private $dao;
   public function __construct()
   {
      $usuario = $_SESSION['SESSION_LOGIN'] ?? null;
      if (!$usuario) {
         $this->redirect(URL_BASE . 'login');
      }
      $this->uuid = $usuario->uuid;
      $this->dao  = new Cliente();
   }
   public function index()
   {
      try {
         $dados['dados'] = $this->dao->clienteAll($this->uuid);
         $dados["view"]  = "cliente/index";
         $this->load("template", $dados);
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'cliente/index');
      }
   }
   public function novo()
   {
      try {
         $dados["view"] = "cliente/novo";
         $this->load("template", $dados);
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'cliente/index');
      }
   }
   public function editar($id)
   {
      try {
         $dados['dados'] = $this->dao->clienteId($id);
         $dados["view"]  = "cliente/editar";
         $this->load("template", $dados);
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'cliente/index');
      }
   }
   public function salvar()
   {
      try {

         $cliente          = new \stdClass();
         $cliente->uuid    = $this->uuid;
         $cliente->nome    = $_POST['nome'];
         $cliente->ativo   = 'S';

         if (isVazio($cliente->nome) || isVazio($cliente->ativo)) {
            setFlash('error', 'Preencha todos os campos!');
            $this->redirect(URL_BASE . 'cliente/novo');
         }

         $existe = $this->dao->existe($cliente);
         if ($existe) {
            setFlash('error', 'Já existe um cliente cadastrado com esse nome!');
            $this->redirect(URL_BASE . 'cliente/novo');
         }

         $this->dao->criarCliente($cliente);
         setFlash('success', 'Cliente cadastrado com sucesso!');
         $this->redirect(URL_BASE . 'cliente/index');
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'cliente/index');
      }
   }
   public function atualizar()
   {
      try {
         $cliente          = new \stdClass();
         $cliente->nome    = $_POST['nome'];
         $cliente->ativo   =  $_POST['ativo'];
         $id               =  $_POST['id'];

         if (isVazio($cliente->nome) || isVazio($cliente->ativo)) {
            setFlash('error', 'Preencha todos os campos obrigatorios!');
            $this->redirect(URL_BASE . 'cliente/editar/' . $id);
         }
         $this->dao->atualizar($id, $cliente);
         setFlash('success', 'Cliente atualizado  com sucesso!');
         $this->redirect(URL_BASE . 'cliente/index');
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'cliente/index');
      }
   }
   public function excluir($id)
   {
      try {
         if (isVazio($id)) {
            setFlash('error', 'Precisa informar um ID !');
            $this->redirect(URL_BASE . 'cliente/index/');
         }

         $existe = $this->dao->existeMovimento($id);
         if ($existe) {
            setFlash('error', 'Este cliente não pode ser excluída porque já possui movimentações registradas. Se preferir, você pode desativá-la.');
            $this->redirect(URL_BASE . 'cliente/index');
         }

         $this->dao->notClientePadrao($id);
         $this->dao->excluir($id);
         setFlash('success', 'Cliente excluido  com sucesso!');
         $this->redirect(URL_BASE . 'cliente/index');
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'cliente/index');
      }
   }
}
