<?php

namespace app\controllers;

use app\core\Controller;
use app\models\Usuario;
use InvalidArgumentException;
use Ramsey\Uuid\Uuid;

class UsuarioController extends Controller
{

   private $uuid;
   private $dao;
   private $id_usuario_sessao;
   public function __construct()
   {
      $usuario = $_SESSION['SESSION_LOGIN'] ?? null;
      if (!$usuario) {
         $this->redirect(URL_BASE . 'login');
      }
      $this->uuid = $usuario->uuid;
      $this->id_usuario_sessao = $usuario->id;
      $this->dao = new Usuario();
   }
   public function index()
   {
      try {
         $dados['dados'] = $this->dao->usuarioAll($this->uuid);
         $dados["view"]       = "usuario/index";
         $this->load("template", $dados);
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'usuario/index');
      }
   }
   public function novo()
   {
      try {
         $dados["view"]       = "usuario/novo";
         $this->load("template", $dados);
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'usuario/index');
      }
   }
   public function editar($id)
   {
      try {
         $dados['dados'] = $this->dao->usuarioId($id);
         $dados["view"]       = "usuario/editar";
         $this->load("template", $dados);
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'usuario/index');
      }
   }
   public function salvar()
   {
      try {
         $usuario = new \stdClass();
         $usuario->uuid       = $this->uuid;
         $usuario->nome       = $_POST['nome'];
         $usuario->email      = $_POST['email'];
         $usuario->senha      = md5($_POST['senha']);
         $usuario->telefone   = $_POST['telefone'];
         $usuario->ativo      = 'S';

         if (isVazio($usuario->nome) || isVazio($usuario->senha) || isVazio($usuario->email)) {
            setFlash('error', 'Preencha todos os campos!');
            $this->redirect(URL_BASE . 'usuario/novo');
         }

         $this->dao->criarUsuario($usuario);
         setFlash('success', 'Usuario cadastrado com sucesso!');
         $this->redirect(URL_BASE . 'usuario/index');
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'usuario/index');
      }
   }
   public function atualizar()
   {
      try {
         $usuario = new \stdClass();
         $usuario->nome       = $_POST['nome'];
         $usuario->email      = $_POST['email'];
         $usuario->senha      = md5($_POST['senha']);
         $usuario->telefone   = $_POST['telefone'];
         $usuario->ativo      =  $_POST['ativo'];
         $id                  =  $_POST['id'];

         if (isVazio($usuario->nome) || isVazio($usuario->senha) || isVazio($usuario->email)) {
            setFlash('error', 'Preencha todos os campos obrigatorios!');
            $this->redirect(URL_BASE . 'usuario/editar/' . $id);
         }
         $this->dao->atualizar($id, $usuario);
         setFlash('success', 'Usuario atualizado  com sucesso!');
         $this->redirect(URL_BASE . 'usuario/index');
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'usuario/index');
      }
   }
   public function excluir($id)
   {
      try {
         if (isVazio($id)) {
            setFlash('error', 'Precisa informar um ID !');
            $this->redirect(URL_BASE . 'usuario/index/');
         }
         $existe = $this->dao->existeMovimento($id);
         if ($existe) {
            setFlash('error', 'Este usuario não pode ser excluída porque já possui movimentações registradas. Se preferir, você pode desativá-la.');
            $this->redirect(URL_BASE . 'usuario/index');
         }
         if ($id == $this->id_usuario_sessao) {
            throw new InvalidArgumentException("Você não pode excluir esse usuario, você esta logando com ele");
         }
         $this->dao->excluir($id);
         setFlash('success', 'Usuario excluido  com sucesso!');
         $this->redirect(URL_BASE . 'usuario/index');
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'usuario/index');
      }
   }
}
