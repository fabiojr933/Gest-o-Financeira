<?php

namespace app\util;

use app\models\Fornecedor;
use app\models\Cliente;
use app\models\Despesa;
use app\models\Receita;
use app\models\Pagamento;

class UtilService
{

    private $daoDespesa;
    private $daoReceita;
    private $daoPagamento;
    private $daoCliente;
    private $daoFornecedor;

    public function __construct()
    {
        $this->daoDespesa = new Despesa();
        $this->daoReceita = new Receita();
        $this->daoPagamento = new Pagamento();
        $this->daoCliente = new Cliente();
        $this->daoFornecedor = new Fornecedor();
    }
    public function insertDespesas($uuid)
    {
        $dados = [
            [
                'nome'       => 'Aluguel',
                'uuid'       => $uuid,
                'ativo'       => 'S',
                'tipo'       => 'DESPESA',
                'natureza'   => 'FIXO',
            ],
            [
                'nome'       => 'Energia',
                'uuid'       => $uuid,
                'ativo'       => 'S',
                'tipo'       => 'DESPESA',
                'natureza'   => 'FIXO',
            ],
            [
                'nome'       => 'Agua',
                'uuid'       =>   $uuid,
                'ativo'       => 'S',
                'tipo'       => 'DESPESA',
                'natureza'   => 'FIXO',
            ],
            [
                'nome'       => 'Internet',
                'uuid'       => $uuid,
                'ativo'       => 'S',
                'tipo'       => 'DESPESA',
                'natureza'   => 'FIXO',
            ],
            [
                'nome'       => 'Mensalidade de sistema',
                'uuid'       => $uuid,
                'ativo'       => 'S',
                'tipo'       => 'DESPESA',
                 'natureza'   => 'FIXO',
                 'natureza'   => 'FIXO',
            ],
            [
                'nome'       => 'Segurança',
                'uuid'       => $uuid,
                'ativo'       => 'S',
                'tipo'       => 'DESPESA',
                'natureza'   => 'FIXO',
            ],
            [
                'nome'       => 'Seguro',
                'uuid'       => $uuid,
                'ativo'       => 'S',
                'tipo'       => 'DESPESA',
                'natureza'   => 'FIXO',
            ],
            [
                'nome'       => 'Honorarios profissionais',
                'uuid'       => $uuid,
                'ativo'       => 'S',
                'tipo'       => 'DESPESA',
                'natureza'   => 'FIXO',
            ],
            [
                'nome'       => 'Associações',
                'uuid'       => $uuid,
                'ativo'       => 'S',
                'tipo'       => 'DESPESA',
                'natureza'   => 'FIXO',
            ],
            [
                'nome'       => 'Cartorio',
                'uuid'       => $uuid,
                'ativo'      => 'S',
                'tipo'       => 'DESPESA',
                'natureza'   => 'VARIAVEL',
            ],
            [
                'nome'       => 'Correios',
                'uuid'       => $uuid,
                'ativo'      => 'S',
                'tipo'       => 'DESPESA',
                'natureza'   => 'VARIAVEL',
            ],
            [
                'nome'       => 'Energia Elétrica',
                'uuid'       => $uuid,
                'ativo'      => 'S',
                'tipo'       => 'DESPESA',
                'natureza'   => 'VARIAVEL',
            ],
            [
                'nome'       => 'Material de Escritório',
                'uuid'       => $uuid,
                'ativo'      => 'S',
                'tipo'       => 'DESPESA',
                'natureza'   => 'VARIAVEL',
            ],
            [
                'nome'       => 'Gastos com Viagens e Estadias',
                'uuid'       => $uuid,
                'ativo'      => 'S',
                'tipo'       => 'DESPESA',
                'natureza'   => 'VARIAVEL',
            ],
            [
                'nome'       => 'Despesas Bancárias',
                'uuid'       => $uuid,
                'ativo'      => 'S',
                'tipo'       => 'DESPESA',
                'natureza'   => 'VARIAVEL',
            ],
            [
                'nome'       => 'Impressos e formularios',
                'uuid'       => $uuid,
                'ativo'      => 'S',
                'tipo'       => 'DESPESA',
                'natureza'   => 'VARIAVEL',
            ],
            [
                'nome'       => 'Manutenção casa',
                'uuid'       => $uuid,
                'ativo'      => 'S',
                'tipo'       => 'DESPESA',
                'natureza'   => 'VARIAVEL',
            ],
            [
                'nome'       => 'Manutenção maquina e equipamento',
                'uuid'       => $uuid,
                'ativo'      => 'S',
                'tipo'       => 'DESPESA',
                'natureza'   => 'VARIAVEL',
            ],
            [
                'nome'       => 'Manutenção de veiculo',
                'uuid'       => $uuid,
                'ativo'      => 'S',
                'tipo'       => 'DESPESA',
                'natureza'   => 'VARIAVEL',
            ],
            [
                'nome'       => 'Combustivel moto',
                'uuid'       => $uuid,
                'ativo'      => 'S',
                'tipo'       => 'DESPESA',
                'natureza'   => 'VARIAVEL',
            ],
            [
                'nome'       => 'Combustivel carro',
                'uuid'       => $uuid,
                'ativo'      => 'S',
                'tipo'       => 'DESPESA',
                'natureza'   => 'VARIAVEL',
            ],
            [
                'nome'       => 'Roupas e vestuarios',
                'uuid'       => $uuid,
                'ativo'      => 'S',
                'tipo'       => 'DESPESA',
                'natureza'   => 'VARIAVEL',
            ],
            [
                'nome'       => 'Eventos diversos',
                'uuid'       => $uuid,
                'ativo'      => 'S',
                'tipo'       => 'DESPESA',
                'natureza'   => 'VARIAVEL',
            ],
            [
                'nome'       => 'Refeições e lanches',
                'uuid'       => $uuid,
                'ativo'      => 'S',
                'tipo'       => 'DESPESA',
                'natureza'   => 'VARIAVEL',
            ],
            [
                'nome'       => 'Custos de Manutenção de Sites de E-commerce',
                'uuid'       => $uuid,
                'ativo'      => 'S',
                'tipo'       => 'DESPESA',
                'natureza'   => 'VARIAVEL',
            ],
            [
                'nome'       => 'Iptu',
                'uuid'       => $uuid,
                'ativo'      => 'S',
                'tipo'       => 'DESPESA',
                'natureza'   => 'VARIAVEL',
            ],
            [
                'nome'       => 'Alvara',
                'uuid'       => $uuid,
                'ativo'      => 'S',
                'tipo'       => 'DESPESA',
                'natureza'   => 'VARIAVEL',
            ],
            [
                'nome'       => 'Ipva e seguro obrigatorios',
                'uuid'       => $uuid,
                'ativo'      => 'S',
                'tipo'       => 'DESPESA',
                'natureza'  => 'VARIAVEL',
            ],
        ];
        foreach ($dados as $item) {
            $despesa = (object) $item;
            $this->daoDespesa->criarDespesas($despesa);
        }
    }

    public function insertReceita($uuid)
    {
        $dados = [
            [
                'nome'       => 'Recebimento de duplicata',
                'uuid'       => $uuid,
                'ativo'      => 'S',
                'tipo'       => 'RECEITA',
                'natureza'   => 'VARIAVEL',
            ],
            [
                'nome'       => 'Salario',
                'uuid'       => $uuid,
                'ativo'      => 'S',
                'tipo'       => 'RECEITA',
                'natureza'   => 'FIXO',
            ],
            [
                'nome'       => 'Decimo 13° salario',
                'uuid'       =>   $uuid,
                'ativo'      => 'S',
                'tipo'       => 'RECEITA',
                'natureza'   => 'VARIAVEL',
            ],
            [
                'nome'       => 'Aluguel de Propriedades',
                'uuid'       => $uuid,
                'ativo'      => 'S',
                'tipo'       => 'RECEITA',
                'natureza'   => 'FIXO',
            ],
            [
                'nome'       => 'Venda de Ativos',
                'uuid'       => $uuid,
                'ativo'      => 'S',
                'tipo'       => 'RECEITA',
                'natureza'   => 'VARIAVEL',
            ],
            [
                'nome'       => 'Comissões',
                'uuid'       => $uuid,
                'ativo'      => 'S',
                'tipo'       => 'RECEITA',
                'natureza'   => 'VARIAVEL',
            ],
            [
                'nome'       => 'Cartao de alimentação',
                'uuid'       => $uuid,
                'ativo'      => 'S',
                'tipo'       => 'RECEITA',
                'natureza'   => 'FIXO',
            ],
            [
                'nome'       => 'Outras entrada',
                'uuid'       => $uuid,
                'ativo'      => 'S',
                'tipo'       => 'RECEITA',
                'natureza'   => 'VARIAVEL',
            ],
        ];
        foreach ($dados as $item) {
            $receita = (object) $item;
            $this->daoReceita->criarReceita($receita);
        }
    }

    public function insertPagamento($uuid)
    {
        $dados = [
            [
                'nome'       => 'Pix',
                'uuid'       => $uuid,
                'ativo'       => 'S'
            ],
            [
                'nome'       => 'Cartão de credito',
                'uuid'       => $uuid,
                'ativo'       => 'S'
            ],
            [
                'nome'       => 'Cartão de debito',
                'uuid'       => $uuid,
                'ativo'       => 'S'
            ],
            [
                'nome'       => 'Dinheiro',
                'uuid'       => $uuid,
                'ativo'       => 'S'
            ],
            [
                'nome'       => 'Cheque',
                'uuid'       => $uuid,
                'ativo'       => 'S'
            ],
            [
                'nome'       => 'Transferencia bancaria',
                'uuid'       => $uuid,
                'ativo'       => 'S',
            ],
        ];
        foreach ($dados as $item) {
            $Pagamento = (object) $item;
            $this->daoPagamento->criarPagamento($Pagamento);
        }
    }
    public function insertCliente($uuid)
    {
        $dados = [
            [
                'nome'       => 'Cliente padrão',
                'uuid'       => $uuid,
                'ativo'       => 'S'
            ],
        ];
        foreach ($dados as $item) {
            $Cliente = (object) $item;
            $this->daoCliente->criarCliente($Cliente);
        }
    }
    public function insertFornecedor($uuid)
    {
        $dados = [
            [
                'nome'       => 'Fornecedor padrão',
                'uuid'       => $uuid,
                'ativo'       => 'S'
            ],
        ];
        foreach ($dados as $item) {
            $Fornecedor = (object) $item;
            $this->daoFornecedor->criarFornecedor($Fornecedor);
        }
    }
}
