<?php

namespace app\controllers;

use app\core\Controller;
use app\models\Fluxo;

class FluxoController extends Controller
{
   private $uuid;
   private $daoFluxo;
   public function __construct()
   {
      $usuario = $_SESSION['SESSION_LOGIN'] ?? null;
      if (!$usuario) {
         $this->redirect(URL_BASE . 'login');
      }
      $this->uuid = $usuario->uuid;
      $this->daoFluxo = new Fluxo();
   }
   public function sintetico()
   {
      try {
         $inicio = $_POST['inicio'] ?? null;
         $fim    = $_POST['fim'] ?? null;

         $datas = [
            'inicio' => $inicio,
            'fim'    => $fim
         ];

         if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            if (!validarDatas($datas)) {
               $data  = getMesAtualInicioFim();
               $datas = getMesInicioFim($data['ano'], $data['mes']);
            } else {
               $data  = getMesAtualInicioFim();
               $datas = getMesInicioFim($data['ano'], $data['mes']);
            }
         }
         $fluxo = $this->daoFluxo->sintetico($this->uuid, $datas);
         $totais = [];

         foreach ($fluxo as $item) {
            $tipo     = $item->tipo;       // DESPESA | RECEITA
            $natureza = $item->natureza;   // FIXO | VARIAVEL
            $valor    = (float) $item->total;

            if (!isset($totais[$tipo])) {
               $totais[$tipo] = [];
            }

            if (!isset($totais[$tipo][$natureza])) {
               $totais[$tipo][$natureza] = 0;
            }

            $totais[$tipo][$natureza] += $valor;
         }
        
         $dados['totais'] = $totais;
         $dados['datas'] = $datas;
         $dados['dados'] = $fluxo;
         $dados["view"]  = "fluxo/sintetico";
         $this->load("template", $dados);
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'fluxo/sintetico');
      }
   }

   public function analitico()
   {
      try {
         $inicio = $_POST['inicio'] ?? null;
         $fim    = $_POST['fim'] ?? null;

         $datas = [
            'inicio' => $inicio,
            'fim'    => $fim
         ];

         if ($_SERVER['REQUEST_METHOD'] === 'GET') {

            if (!validarDatas($datas)) {
               $data  = getMesAtualInicioFim();
               $datas = getMesInicioFim($data['ano'], $data['mes']);
            } else {
               $data  = getMesAtualInicioFim();
               $datas = getMesInicioFim($data['ano'], $data['mes']);
            }
         }
         $fluxo = $this->daoFluxo->analitico($this->uuid, $datas);
         $totais = [];

         foreach ($fluxo as $item) {
            $tipo     = $item->tipo;       // DESPESA | RECEITA
            $natureza = $item->natureza;   // FIXO | VARIAVEL
            $valor    = (float) $item->total;

            if (!isset($totais[$tipo])) {
               $totais[$tipo] = [];
            }

            if (!isset($totais[$tipo][$natureza])) {
               $totais[$tipo][$natureza] = 0;
            }

            $totais[$tipo][$natureza] += $valor;
         }
       
         $dados['totais'] = $totais;
         $dados['datas'] = $datas;
         $dados['dados'] = $fluxo;
         $dados["view"]  = "fluxo/analitico";
         $this->load("template", $dados);
      } catch (\Throwable $th) {
         setFlash('error', 'Ocorreu um erro! ' . $th->getMessage());
         $this->redirect(URL_BASE . 'fluxo/analitico');
      }
   }
}
