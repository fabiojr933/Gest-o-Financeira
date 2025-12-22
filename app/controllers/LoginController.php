<?php

namespace app\controllers;

use app\core\Controller;
use Ramsey\Uuid\Uuid;
use app\models\Usuario;
use app\util\UtilService;

class LoginController extends Controller
{
   private $dao;
   private $util;
   public function __construct()
   {
      $this->dao  = new Usuario();
      $this->util = new UtilService();
   }
   public function index()
   {
      $dados["view"] = "login";
      $this->load("login", $dados);
   }
   public function cadastrar()
   {
      $dados["view"] = "cadastrar";
      $this->load("cadastrar", $dados);
   }

   public function criarUsuario()
   {
      try {
         $uuid                = Uuid::uuid4();
         $usuario             = new \stdClass();
         $usuario->uuid       = $uuid->toString();
         $usuario->nome       = $_POST['nome'];
         $usuario->email      = $_POST['email'];
         $usuario->senha      = md5($_POST['senha']);
         $usuario->telefone   = $_POST['telefone'];
         $usuario->ativo      = 'S';

         if (isVazio($usuario->nome) || isVazio($usuario->senha) || isVazio($usuario->email)) {
            setFlash('error', 'Preencha todos os campos!');
            $this->redirect(URL_BASE . 'login/cadastrar');
         }

         $this->dao->criarUsuario($usuario);
         $this->util->insertDespesas($usuario->uuid);
         $this->util->insertReceita($usuario->uuid);
         $this->util->insertPagamento($usuario->uuid);
         $this->util->insertCliente($usuario->uuid);
         $this->util->insertFornecedor($usuario->uuid);
         setFlash('success', 'Usuario cadastrado com sucesso!');
         $this->redirect(URL_BASE . 'login');
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'login/cadastrar');
      }
   }

   public function autenticar()
   {
      try {
         $usuario        = new \stdClass();
         $usuario->email = $_POST["email"];
         $usuario->senha = md5($_POST["senha"]);
         $dados          = $this->dao->autenticar($usuario);

         if (empty($dados)) {
            setFlash('error', 'Usuário não encontrado!');
            $this->redirect(URL_BASE . 'login');
         }

         if ($dados->senha !== $usuario->senha) {
            setFlash('error', 'Senha incorreta!');
            $this->redirect(URL_BASE . 'login');
         }

         $_SESSION['SESSION_LOGIN'] = $dados;
         //  setFlash('success', 'Bem vindo(a) ' . $dados->nome . '!');
         $this->redirect(URL_BASE . 'dashboard');
      } catch (\Throwable $th) {
         setFlash('error', 'Erro interno: ' . $th->getMessage());
         $this->redirect(URL_BASE . 'login');
      }
   }


   public function sair()
   {
      try {
         unset($_SESSION['SESSION_LOGIN']);
         $this->redirect(URL_BASE . "login");
      } catch (\Throwable $th) {
         setFlash('error', 'Erro interno: ' . $th->getMessage());
         $this->redirect(URL_BASE . 'login');
      }
   }
}
