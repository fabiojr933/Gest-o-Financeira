<?php

namespace app\controllers;

use app\core\Controller;
use app\models\Receita;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;

class ReceitaController extends Controller
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
      $this->dao = new Receita();
   }
   public function index()
   {
      $dados['dados'] = $this->dao->receitaAll($this->uuid);
      $dados["view"]       = "receita/index";
      $this->load("template", $dados);
   }
   public function novo()
   {
      $dados["view"]       = "receita/novo";
      $this->load("template", $dados);
   }
   public function editar($id)
   {
      $dados['dados'] = $this->dao->receitaId($id);
      $dados["view"]       = "receita/editar";
      $this->load("template", $dados);
   }
   public function salvar()
   {
      try {
         
         $receita = new \stdClass();
         $receita->uuid       = $this->uuid;
         $receita->nome       = $_POST['nome'];
         $receita->ativo      = 'S';
         $receita->natureza = isset($_POST['natureza']) ? 'FIXO' : 'VARIAVEL';

         if (isVazio($receita->nome) || isVazio($receita->ativo)) {
            setFlash('error', 'Preencha todos os campos!');
            $this->redirect(URL_BASE . 'receita/novo');
         }

         $existe = $this->dao->existe($receita);
         if($existe){
             setFlash('error', 'Já existe receita cadastrado com esse nome!');
            $this->redirect(URL_BASE . 'receita/novo');
         }

         $this->dao->criarReceita($receita);
         setFlash('success', 'Receita cadastrado com sucesso!');
         $this->redirect(URL_BASE . 'receita/index');
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'receita/index');
      }
   }
   public function atualizar()
   {
      try {
         $receita = new \stdClass();
         $receita->nome       = $_POST['nome'];
         $receita->ativo      =  $_POST['ativo'];
         $id                  =  $_POST['id'];
         $receita->natureza = isset($_POST['natureza']) ? 'FIXO' : 'VARIAVEL';
        
         if (isVazio($receita->nome) || isVazio($receita->ativo)) {
            setFlash('error', 'Preencha todos os campos obrigatorios!');
            $this->redirect(URL_BASE . 'receita/editar/' . $id);
         }
         $this->dao->atualizar($id, $receita);
         setFlash('success', 'Receita atualizado  com sucesso!');
         $this->redirect(URL_BASE . 'receita/index');
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'receita/index');
      }
   }
   public function excluir($id)
   {
      try {
         if (isVazio($id)) {
            setFlash('error', 'Precisa informar um ID !');
            $this->redirect(URL_BASE . 'receita/index/');
         }

         $this->dao->excluir($id);
         setFlash('success', 'Receita excluido  com sucesso!');
         $this->redirect(URL_BASE . 'receita/index');
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'receita/index');
      }
   }
}
