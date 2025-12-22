<?php

namespace app\controllers;

use app\core\Controller;
use app\models\Fornecedor;

class FornecedorController extends Controller
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
      $this->dao  = new Fornecedor();
   }
   public function index()
   {
      try {
         $dados['dados'] = $this->dao->fornecedorAll($this->uuid);
         $dados["view"]  = "fornecedor/index";
         $this->load("template", $dados);
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'fornecedor/index');
      }
   }
   public function novo()
   {
      try {
         $dados["view"] = "fornecedor/novo";
         $this->load("template", $dados);
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'fornecedor/index');
      }
   }
   public function editar($id)
   {
      try {
         $dados['dados'] = $this->dao->fornecedorId($id);
         $dados["view"]  = "fornecedor/editar";
         $this->load("template", $dados);
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'fornecedor/index');
      }
   }
   public function salvar()
   {
      try {

         $fornecedor          = new \stdClass();
         $fornecedor->uuid    = $this->uuid;
         $fornecedor->nome    = $_POST['nome'];
         $fornecedor->ativo   = 'S';

         if (isVazio($fornecedor->nome) || isVazio($fornecedor->ativo)) {
            setFlash('error', 'Preencha todos os campos!');
            $this->redirect(URL_BASE . 'fornecedor/novo');
         }

         $existe = $this->dao->existe($fornecedor);
         if ($existe) {
            setFlash('error', 'Já existe um fornecedor cadastrado com esse nome!');
            $this->redirect(URL_BASE . 'fornecedor/novo');
         }

         $this->dao->criarFornecedor($fornecedor);
         setFlash('success', 'Fornecedor cadastrado com sucesso!');
         $this->redirect(URL_BASE . 'fornecedor/index');
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'fornecedor/index');
      }
   }
   public function atualizar()
   {
      try {
         $fornecedor         = new \stdClass();
         $fornecedor->nome   = $_POST['nome'];
         $fornecedor->ativo  =  $_POST['ativo'];
         $id                 =  $_POST['id'];

         if (isVazio($fornecedor->nome) || isVazio($fornecedor->ativo)) {
            setFlash('error', 'Preencha todos os campos obrigatorios!');
            $this->redirect(URL_BASE . 'fornecedor/editar/' . $id);
         }
         $this->dao->atualizar($id, $fornecedor);
         setFlash('success', 'Fornecedor atualizado  com sucesso!');
         $this->redirect(URL_BASE . 'fornecedor/index');
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'fornecedor/index');
      }
   }
   public function excluir($id)
   {
      try {
         if (isVazio($id)) {
            setFlash('error', 'Precisa informar um ID !');
            $this->redirect(URL_BASE . 'fornecedor/index/');
         }

         $existe = $this->dao->existeMovimento($id);
         if ($existe) {
            setFlash('error', 'Este fornecedor não pode ser excluída porque já possui movimentações registradas. Se preferir, você pode desativá-la.');
            $this->redirect(URL_BASE . 'fornecedor/index');
         }

         $this->dao->notFornecedorPadrao($id);
         $this->dao->excluir($id);
         setFlash('success', 'Fornecedor excluido  com sucesso!');
         $this->redirect(URL_BASE . 'fornecedor/index');
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'fornecedor/index');
      }
   }
}
