<?php

namespace app\controllers;

use app\core\Controller;


class DashboardController extends Controller
{

    public function __construct()
    {
        $usuario = $_SESSION['SESSION_LOGIN'] ?? null;
        if (!$usuario) {
            $this->redirect(URL_BASE . 'login');
        }
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

            // Apenas aqui monta dados
            $dados = [
                "datas" => $datas,
                "view"  => "dashboard"
            ];

            $this->load("template", $dados);
        } catch (\Throwable $th) {
            setFlash('error', 'Erro interno: ' . $th->getMessage());
            $this->redirect(URL_BASE . 'login');
        }
    }
}
