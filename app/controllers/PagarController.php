<?php

namespace app\controllers;

use app\core\Controller;
use app\models\Pagamento;
use app\models\Usuario;
use app\models\Fornecedor;
use app\models\Despesa;
use app\models\Pagar;

class PagarController extends Controller
{
   private $uuid;
   private $nomeUsuario;
   private $daoPagamento;
   private $daoUsuario;
   private $daoFornecedor;
   private $daoDespesa;
   private $daoPagar;

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
      $this->daoFornecedor = new Fornecedor();
      $this->daoDespesa    = new Despesa();
      $this->daoPagar      = new Pagar();
   }

   public function novo()
   {
      try {
         $dados['usuario']    = $this->daoUsuario->usuarioAll($this->uuid);
         $dados['fornecedor'] = $this->daoFornecedor->fornecedorAll($this->uuid);
         $dados['despesa']    = $this->daoDespesa->despesaAll($this->uuid);
         $dados["view"]       = "pagar/novo";
         $this->load("template", $dados);
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'pagar/novo');
      }
   }
   public function salvar()
   {
      try {
         $pagarDoc                     = new \stdClass();
         $pagarDoc->descricao          = $_POST['descricao'];
         $pagarDoc->id_fornecedor      = $_POST['id_fornecedor'];
         $pagarDoc->qtde_parcelas      = intval($_POST['parcela']);
         $pagarDoc->parcelado          = $_POST['id_parcelado'];
         $pagarDoc->valor_total        = $_POST['valor_total'];
         $pagarDoc->uuid               = $this->uuid;
         $pagarDoc->id_usuario         = $_POST['id_usuario'];

         $id_pagar                     = $this->daoPagar->criarDocumentoPagar($pagarDoc);

         $pagarParc                    = new \stdClass();
         $pagarParc->id_conta          = $_POST['origem_id'];
         $pagarParc->valor_parcela     = $_POST['valor_parcela'];
         $pagarParc->parcela           = $_POST['numero_parcela'];
         $pagarParc->data_vencimento   = $_POST['vencimento'];
         $pagarParc->status            = $_POST['tipo'];
         $pagarParc->id_conta_pagar    = intval($id_pagar);

         $parcelasSeparadas = [];

         for ($i = 0; $i < (int) $pagarDoc->qtde_parcelas; $i++) {
            $valorOriginal             = $pagarParc->valor_parcela[$i];
            $valorConvertido           = str_replace(
               ['R$', '.', ','],
               ['',   '',  '.'],
               $valorOriginal
            );
            $valorConvertido           = (float) trim($valorConvertido);
            $parcelasSeparadas[]       = [
               'id_conta'              => (int) $pagarParc->id_conta,
               'valor'                 => $valorConvertido,
               'numero_parcela'        => (int) $pagarParc->parcela[$i],
               'data_vencimento'       => $pagarParc->data_vencimento[$i],
               'status'                => $pagarParc->status,
               'id_conta_pagar'        => (int) $pagarParc->id_conta_pagar,
               'pago_por'              => $this->nomeUsuario,
            ];
         }

         foreach ($parcelasSeparadas as $dados) {
            $this->daoPagar->criarDocumentoPagarParcelas($dados);
         }
         setFlash('success', 'contas a pagar cadastrado com sucesso!');
         $this->redirect(URL_BASE . "pagar/novo");
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'pagar/novo');
      }
   }
   public function aberta()
   {
      try {
         $dados['dados'] = $this->daoPagar->contasAberta($this->uuid);
         $dados["view"]  = "pagar/aberta";
         $this->load("template", $dados);
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'pagar/novo');
      }
   }
   public function vencida()
   {
      try {
         $dados['dados'] = $this->daoPagar->contasVencida($this->uuid);
         $dados["view"]  = "pagar/vencida";
         $this->load("template", $dados);
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'pagar/novo');
      }
   }
   public function pagas()
   {
      try {
         $dados['dados'] = $this->daoPagar->contasPagas($this->uuid);
         $dados["view"]  = "pagar/pagas";
         $this->load("template", $dados);
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'pagar/novo');
      }
   }
   public function visualizar($id)
   {
      try {
         $dados['dados'] = $this->daoPagar->visualizar($this->uuid, $id);
         $dados["view"]  = "pagar/visualizar";
         $this->load("template", $dados);
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'pagar/novo');
      }
   }
   public function cancelarPagamento()
   {
      try {
         $id               = $_POST['id_parcela'];
         $this->daoPagar->cancelarPagamento($id);
         setFlash('success', 'Pagamento cancelado com sucesso!');
         $dados["view"]    = "pagar/pagas";
         $this->load("template", $dados);
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'pagar/pagas');
      }
   }
   public function pagar($id)
   {
      try {
         $dados['dados']     = $this->daoPagar->visualizar($this->uuid, $id);
         $dados['pagamento'] = $this->daoPagamento->pagamentoAll($this->uuid);
         $dados['despesa']   = $this->daoDespesa->despesaAll($this->uuid);
         $dados["view"]      = "pagar/pagar";
         $this->load("template", $dados);
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'pagar/novo');
      }
   }
   public function excluir($id_parcela)
   {
      try {
         if (!$id_parcela) {
            setFlash('error', 'Precisa informar ID parcela!');
            $this->redirect(URL_BASE . 'pagar/aberta');
         }
         $parcela = $this->daoPagar->visualizar($this->uuid, $id_parcela);
         $this->daoPagar->excluirPacela($id_parcela);
         $existaDOc = $this->daoPagar->existeParcela($parcela->id);
         if (!$existaDOc) {
            $this->daoPagar->excluirDoc($parcela->id);
         }
         setFlash('success', 'contas a pagar excluido com sucesso!');
         $this->redirect(URL_BASE . "pagar/aberta");
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'pagar/aberta');
      }
   }
   public function pagamento()
   {
      try {
         $pagar                 = new \stdClass();
         $pagar->id_pagamento   = $_POST['id_pagamento'];
         $pagar->id_conta       =  $_POST['id_conta'];
         $pagar->status         = 'PAGO';
         $pagar->data_pagamento = date('Y-m-d');
         $id                    =  $_POST['id_parcela'];

         if (isVazio($pagar->id_pagamento) || isVazio($pagar->id_conta) || isVazio($id)) {
            setFlash('error', 'Preencha todos os campos obrigatorios!');
            $this->redirect(URL_BASE . 'pagar/aberta');
         }
         $this->daoPagar->pagar($id, $pagar);
         setFlash('success', 'Pagamento realizado com sucesso!');
         $this->redirect(URL_BASE . 'pagar/aberta');
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'pagar/aberta');
      }
   }
}
