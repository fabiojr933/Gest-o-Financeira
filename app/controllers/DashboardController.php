<?php

namespace app\controllers;

use app\core\Controller;
use app\models\Fluxo;

class DashboardController extends Controller
{

    private $daoFluxo;
    private $uuid;

    public function __construct()
    {
        $usuario = $_SESSION['SESSION_LOGIN'] ?? null;
        if (!$usuario) {
            $this->redirect(URL_BASE . 'login');
        }
        $this->uuid = $usuario->uuid;
        $this->daoFluxo = new Fluxo();
    }
    public function index()
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
            $totaisFluxo = [];
            foreach ($fluxo as $item) {
                $tipo  = $item->tipo;
                $valor = (float) $item->total;

                if (!isset($totais[$tipo])) {
                    $totais[$tipo] = 0;
                }

                $totais[$tipo] += $valor;
            }

            foreach ($fluxo as $item) {
                $fluxoFinanceiro = $item->fluxo;
                $valor = (float) $item->total;

                if (!isset($totaisFluxo[$fluxoFinanceiro])) {
                    $totaisFluxo[$fluxoFinanceiro] = 0;
                }

                $totaisFluxo[$fluxoFinanceiro] += $valor;
            }

            $contasReceberAbertas = $this->daoFluxo->contasReceberAbertas($this->uuid, $datas);
            $contasPagarAbertas = $this->daoFluxo->contasPagarAbertas($this->uuid, $datas);
            $vendasCondicaoPagamento = $this->daoFluxo->vendasPorCondicaoPgamento($this->uuid, $datas);

            $vendasCondicaoPagamento = $this->daoFluxo->vendasPorCondicaoPgamento($this->uuid, $datas);
            $totaisCondicaoPagamento = [];
            foreach ($vendasCondicaoPagamento as $item) {
                $tipo  = $item->condicao;
                $valor = (float) $item->total;

                if (!isset($totaisCondicaoPagamento[$tipo])) {
                    $totaisCondicaoPagamento[$tipo] = 0;
                }

                $totaisCondicaoPagamento[$tipo] += $valor;
            }

            $vendasPorUsuario = $this->daoFluxo->vendasPorUsuario($this->uuid, $datas);
            $totaisUsuario = [];
            foreach ($vendasPorUsuario as $item) {
                $tipo  = $item->usuario;
                $valor = (float) $item->total;

                if (!isset($totaisUsuario[$tipo])) {
                    $totaisUsuario[$tipo] = 0;
                }

                $totaisUsuario[$tipo] += $valor;
            }
            

            $dados = [
                "datas" => $datas,
                "graficoTotaisTipo" => $totais,
                "graficoTotaisFluxo" => $totaisFluxo,
                "contasReceberAbertas" => $contasReceberAbertas,
                "contasPagarAbertas" => $contasPagarAbertas,
                "totaisCondicaoPagamento" => $totaisCondicaoPagamento,
                "totaisUsuario" => $totaisUsuario,
                "view"  => "dashboard"
            ];

            $this->load("template", $dados);
        } catch (\Throwable $th) {
            setFlash('error', 'Erro interno: ' . $th->getMessage());
            $this->redirect(URL_BASE . 'login');
        }
    }
}
