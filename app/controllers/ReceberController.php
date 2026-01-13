<?php

namespace app\controllers;

use app\core\Controller;
use app\models\Pagamento;
use app\models\Usuario;
use app\models\Cliente;
use app\models\Receita;
use app\models\Receber;

class ReceberController extends Controller
{
   private $uuid;
   private $nomeUsuario;
   private $daoPagamento;
   private $daoUsuario;
   private $daoCliente;
   private $daoReceita;
   private $daoReceber;

   public function __construct()
   {
      $usuario = $_SESSION['SESSION_LOGIN'] ?? null;
      if (!$usuario) {
         $this->redirect(URL_BASE . 'login');
      }
      $this->uuid          = $usuario->uuid;
      $this->nomeUsuario   = $usuario->nome;
      $this->daoPagamento  = new Pagamento();
      $this->daoUsuario    = new Usuario();
      $this->daoCliente    = new Cliente();
      $this->daoReceita    = new Receita();
      $this->daoReceber    = new Receber();
   }

   public function novo()
   {
      try {
         $dados['usuario']   = $this->daoUsuario->usuarioAll($this->uuid);
         $dados['cliente']   = $this->daoCliente->clienteAll($this->uuid);
         $dados['receita']   = $this->daoReceita->receitaAll($this->uuid);
         $dados["view"]      = "receber/novo";
         $this->load("template", $dados);
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'receber/novo');
      }
   }
   public function salvar()
   {
      try {
         $receberDoc                     = new \stdClass();
         $receberDoc->descricao          = $_POST['descricao'];
         $receberDoc->id_cliente         = $_POST['id_cliente'];
         $receberDoc->qtde_parcelas      = intval($_POST['parcela']);
         $receberDoc->parcelado          = $_POST['id_parcelado'];
         $receberDoc->valor_total        = $_POST['valor_total'];
         $receberDoc->uuid               = $this->uuid;
         $receberDoc->id_usuario         = $_POST['id_usuario'];

         $id_receber                     = $this->daoReceber->criarDocumentoReceber($receberDoc);

         $receberParc                    = new \stdClass();
         $receberParc->id_conta          = $_POST['origem_id'];
         $receberParc->valor_parcela     = $_POST['valor_parcela'];
         $receberParc->parcela           = $_POST['numero_parcela'];
         $receberParc->data_vencimento   = $_POST['vencimento'];
         $receberParc->status            = $_POST['tipo'];
         $receberParc->id_conta_receber  = intval($id_receber);

         $parcelasSeparadas = [];

         for ($i = 0; $i < (int) $receberDoc->qtde_parcelas; $i++) {
            $valorOriginal             = $receberParc->valor_parcela[$i];
            $valorConvertido           = str_replace(
               ['R$', '.', ','],
               ['',   '',  '.'],
               $valorOriginal
            );
            $valorConvertido           = (float) trim($valorConvertido);
            $parcelasSeparadas[]       = [
               'id_conta'              => (int) $receberParc->id_conta,
               'valor'                 => $valorConvertido,
               'numero_parcela'        => (int) $receberParc->parcela[$i],
               'data_vencimento'       => $receberParc->data_vencimento[$i],
               'status'                => $receberParc->status,
               'id_conta_receber'        => (int) $receberParc->id_conta_receber,
               'recebido_por'              => $this->nomeUsuario,
            ];
         }

         foreach ($parcelasSeparadas as $dados) {
            $this->daoReceber->criarDocumentoReceberParcelas($dados);
         }
         setFlash('success', 'contas a receber cadastrado com sucesso!');
         $this->redirect(URL_BASE . "receber/novo");
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'receber/novo');
      }
   }
   public function aberta()
   {
      try {
         $dados['dados'] = $this->daoReceber->contasAberta($this->uuid);
         $dados["view"]  = "receber/aberta";
         $this->load("template", $dados);
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'receber/novo');
      }
   }
   public function vencida()
   {
      try {
         $dados['dados'] = $this->daoReceber->contasVencida($this->uuid);
         $dados["view"]  = "receber/vencida";
         $this->load("template", $dados);
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'receber/novo');
      }
   }
   public function recebido()
   {
      try {
         $dados['dados'] = $this->daoReceber->contasRecebidas($this->uuid);
         $dados["view"]  = "receber/recebido";
         $this->load("template", $dados);
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'receber/novo');
      }
   }
   public function visualizar($id)
   {
      try {
         $dados['metedo'] = $_SERVER['HTTP_REFERER'];
         $dados['dados']  = $this->daoReceber->visualizar($this->uuid, $id);
         $dados["view"]   = "receber/visualizar";
         $this->load("template", $dados);
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'receber/novo');
      }
   }
   public function cancelarRecebimento()
   {
      try {
         $id               = $_POST['id_parcela'];
         $this->daoReceber->cancelarRecebimento($id);
         setFlash('success', 'Recebimento cancelado com sucesso!');
         $dados["view"]    = "receber/recebido";
         $this->load("template", $dados);
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'receber/recebido');
      }
   }
   public function receber($id)
   {
      try {
         $dados['dados']     = $this->daoReceber->visualizar($this->uuid, $id);
         $dados['pagamento'] = $this->daoPagamento->pagamentoAll($this->uuid);
         $dados['receita']   = $this->daoReceita->receitaAll($this->uuid);
         $dados["view"]      = "receber/receber";
         $this->load("template", $dados);
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'receber/novo');
      }
   }
   public function excluir($id_parcela)
   {
      try {
         if (!$id_parcela) {
            setFlash('error', 'Precisa informar ID parcela!');
            $this->redirect(URL_BASE . 'receber/aberta');
         }
         $parcela = $this->daoReceber->visualizar($this->uuid, $id_parcela);
         $this->daoReceber->excluirPacela($id_parcela);
         $existaDOc = $this->daoReceber->existeParcela($parcela->id);
         if (!$existaDOc) {
            $this->daoReceber->excluirDoc($parcela->id);
         }
         setFlash('success', 'contas a receber excluido com sucesso!');
         $this->redirect(URL_BASE . "receber/aberta");
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'receber/aberta');
      }
   }
   public function pagamento()
   {
      try {
         $receber                 = new \stdClass();
         $receber->id_pagamento   = $_POST['id_pagamento'];
         $receber->id_conta       =  $_POST['id_conta'];
         $receber->status         = 'RECEBIDO';
         $receber->data_pagamento = date('Y-m-d');
         $id                    =  $_POST['id_parcela'];

         if (isVazio($receber->id_pagamento) || isVazio($receber->id_conta) || isVazio($id)) {
            setFlash('error', 'Preencha todos os campos obrigatorios!');
            $this->redirect(URL_BASE . 'receber/aberta');
         }
         $this->daoReceber->receber($id, $receber);
         setFlash('success', 'Recebimento realizado com sucesso!');
         $this->redirect(URL_BASE . 'receber/aberta');
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'receber/aberta');
      }
   }
}
