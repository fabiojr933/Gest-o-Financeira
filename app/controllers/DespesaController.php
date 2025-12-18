<?php

namespace app\controllers;

use app\core\Controller;
use app\models\Despesa;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;

class DespesaController extends Controller
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
      $this->dao = new Despesa();
   }
   public function index()
   {
      try {
         $dados['dados'] = $this->dao->despesaAll($this->uuid);
         $dados["view"]       = "despesa/index";
         $this->load("template", $dados);
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'despesa/index');
      }
   }
   public function novo()
   {
      try {
         $dados["view"]       = "despesa/novo";
         $this->load("template", $dados);
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'despesa/index');
      }
   }
   public function editar($id)
   {
      try {
         $dados['dados'] = $this->dao->despesaId($id);
         $dados["view"]       = "despesa/editar";
         $this->load("template", $dados);
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'despesa/index');
      }
   }
   public function salvar()
   {
      try {

         $despesa = new \stdClass();
         $despesa->uuid       = $this->uuid;
         $despesa->nome       = $_POST['nome'];
         $despesa->ativo      = 'S';
         $despesa->natureza = isset($_POST['natureza']) ? 'FIXO' : 'VARIAVEL';


         if (isVazio($despesa->nome) || isVazio($despesa->ativo)) {
            setFlash('error', 'Preencha todos os campos!');
            $this->redirect(URL_BASE . 'despesa/novo');
         }

         $existe = $this->dao->existe($despesa);
         if ($existe) {
            setFlash('error', 'Já existe despesa cadastrado com esse nome!');
            $this->redirect(URL_BASE . 'despesa/novo');
         }

         $this->dao->criarDespesas($despesa);
         setFlash('success', 'Despesa cadastrado com sucesso!');
         $this->redirect(URL_BASE . 'despesa/index');
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'despesa/index');
      }
   }
   public function atualizar()
   {
      try {
         $tipo = isset($_POST['natureza']) ? $_POST['natureza'] : 0;
         $despesa = new \stdClass();
         $despesa->nome       = $_POST['nome'];
         $despesa->ativo      =  $_POST['ativo'];
         $id                  =  $_POST['id'];
         $despesa->natureza       = $tipo == '1' ? 'FIXO' : 'VARIAVEL';

         if (isVazio($despesa->nome) || isVazio($despesa->ativo)) {
            setFlash('error', 'Preencha todos os campos obrigatorios!');
            $this->redirect(URL_BASE . 'despesa/editar/' . $id);
         }
         $this->dao->atualizar($id, $despesa);
         setFlash('success', 'Despesa atualizado  com sucesso!');
         $this->redirect(URL_BASE . 'despesa/index');
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'despesa/index');
      }
   }
   public function excluir($id)
   {
      try {
         if (isVazio($id)) {
            setFlash('error', 'Precisa informar um ID !');
            $this->redirect(URL_BASE . 'despesa/index/');
         }

         $existe = $this->dao->existeMovimento($id);
         if ($existe) {
            setFlash('error', 'Esta despesa não pode ser excluída porque já possui movimentações registradas. Se preferir, você pode desativá-la.');
            $this->redirect(URL_BASE . 'despesa/index');
         }

         $this->dao->excluir($id);
         setFlash('success', 'Despesa excluido  com sucesso!');
         $this->redirect(URL_BASE . 'despesa/index');
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'despesa/index');
      }
   }
}
