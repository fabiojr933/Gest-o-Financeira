<?php

namespace app\controllers;

use app\core\Controller;
use app\models\Pagamento;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;

class PagamentoController extends Controller
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
      $this->dao  = new Pagamento();
   }
   public function index()
   {
      try {
         $dados['dados'] = $this->dao->pagamentoAll($this->uuid);
         $dados["view"]  = "pagamento/index";
         $this->load("template", $dados);
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'pagamento/index');
      }
   }
   public function novo()
   {
      try {
         $dados["view"] = "pagamento/novo";
         $this->load("template", $dados);
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'pagamento/index');
      }
   }
   public function editar($id)
   {
      try {
         $dados['dados'] = $this->dao->pagamentoId($id);
         $dados["view"]  = "pagamento/editar";
         $this->load("template", $dados);
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'pagamento/index');
      }
   }
   public function salvar()
   {
      try {

         $pagamento         = new \stdClass();
         $pagamento->uuid   = $this->uuid;
         $pagamento->nome   = $_POST['nome'];
         $pagamento->ativo  = 'S';

         if (isVazio($pagamento->nome) || isVazio($pagamento->ativo)) {
            setFlash('error', 'Preencha todos os campos!');
            $this->redirect(URL_BASE . 'pagamento/novo');
         }

         $existe = $this->dao->existe($pagamento);
         if ($existe) {
            setFlash('error', 'Já existe condição de pagamento cadastrado com esse nome!');
            $this->redirect(URL_BASE . 'pagamento/novo');
         }

         $this->dao->criarPagamento($pagamento);
         setFlash('success', 'Condicção de pagamento cadastrado com sucesso!');
         $this->redirect(URL_BASE . 'pagamento/index');
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'pagamento/index');
      }
   }
   public function atualizar()
   {
      try {
         $pagamento           = new \stdClass();
         $pagamento->nome     = $_POST['nome'];
         $pagamento->ativo    =  $_POST['ativo'];
         $id                  =  $_POST['id'];

         if (isVazio($pagamento->nome) || isVazio($pagamento->ativo)) {
            setFlash('error', 'Preencha todos os campos obrigatorios!');
            $this->redirect(URL_BASE . 'pagamento/editar/' . $id);
         }
         $this->dao->atualizar($id, $pagamento);
         setFlash('success', 'Condicao de Pagamento atualizado  com sucesso!');
         $this->redirect(URL_BASE . 'pagamento/index');
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'pagamento/index');
      }
   }
   public function excluir($id)
   {
      try {
         if (isVazio($id)) {
            setFlash('error', 'Precisa informar um ID !');
            $this->redirect(URL_BASE . 'pagamento/index/');
         }

         $existe = $this->dao->existeMovimento($id);
         if ($existe) {
            setFlash('error', 'Esta condição de pagamento não pode ser excluída porque já possui movimentações registradas. Se preferir, você pode desativá-la.');
            $this->redirect(URL_BASE . 'pagamento/index');
         }

         $this->dao->excluir($id);
         setFlash('success', 'Condicao de pagamento excluido  com sucesso!');
         $this->redirect(URL_BASE . 'pagamento/index');
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'pagamento/index');
      }
   }
}
