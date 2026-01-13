<?php

namespace app\models;

use app\core\Model;
use InvalidArgumentException;
use PDO;

class Fluxo extends Model
{
    public function sintetico($uuid, $datas)
    {
        $sql = "SELECT
            SUM(T.TOTAL) AS total,
            T.fluxo,
            T.tipo,
            T.natureza
        FROM (
            -- LANCAMENTOS
            SELECT 
                SUM(L.valor) AS TOTAL,
                C.nome AS FLUXO,
                C.tipo,
                C.natureza
            FROM lancamentos L
            JOIN contas C ON C.id = L.id_conta
            where l.uuid = :uuid
            and l.data BETWEEN :inicio and :fim
            GROUP BY C.nome, C.tipo, C.natureza

            UNION ALL

            -- CONTAS A PAGAR (PAGAS)
            SELECT 
                SUM(P.valor) AS TOTAL,
                C.nome AS FLUXO,
                C.tipo,
                C.natureza
            FROM contas_pagar x
            join contas_pagar_parcelas P on x.id = p.id_conta_pagar
            JOIN contas C ON C.id = P.id_conta
            WHERE P.status = 'PAGO'
            and x.uuid = :uuid
            and p.data_pagamento BETWEEN :inicio and :fim
            GROUP BY C.nome, C.tipo, C.natureza

            UNION ALL

            -- CONTAS A RECEBER (RECEBIDAS)
            SELECT 
                SUM(R.valor) AS TOTAL,
                C.nome AS FLUXO,
                C.tipo,
                C.natureza
            FROM contas_receber v
            join contas_receber_parcelas R on v.id = r.id_conta_receber
            JOIN contas C ON C.id = R.id_conta
            WHERE R.status = 'RECEBIDO'
            and v.uuid = :uuid
            and r.data_pagamento BETWEEN :inicio and :fim
            GROUP BY C.nome, C.tipo, C.natureza
        ) T
        GROUP BY T.FLUXO, T.tipo, T.natureza
        ORDER BY T.tipo, T.FLUXO;";
        $qry = $this->db->prepare($sql);

        $qry->bindValue(":uuid",   $uuid, PDO::PARAM_STR);
        $qry->bindValue(":inicio", $datas['inicio'], PDO::PARAM_STR);
        $qry->bindValue(":fim",    $datas['fim'], PDO::PARAM_STR);
        $qry->execute();

        $result = $qry->fetchAll(\PDO::FETCH_OBJ);
        return $result;
    }

    public function analitico($uuid, $datas)
    {
        $sql = "SELECT
            COALESCE(SUM(T.total), 0) AS total,
            C.nome AS fluxo,
            C.tipo,
            C.natureza
        FROM contas C
        LEFT JOIN (
            SELECT
                Z.id_conta,
                SUM(Z.total) AS total
            FROM (

                -- LANCAMENTOS
                SELECT
                    L.id_conta,
                    SUM(L.valor) AS total
                FROM lancamentos L
                WHERE L.uuid = :uuid
                AND L.data BETWEEN :inicio AND :fim
                GROUP BY L.id_conta

                UNION ALL

                -- CONTAS A PAGAR
                SELECT
                    P.id_conta,
                    SUM(P.valor) AS total
                FROM contas_pagar X
                JOIN contas_pagar_parcelas P ON X.id = P.id_conta_pagar
                WHERE X.uuid = :uuid
                AND P.status = 'PAGO'
                AND P.data_pagamento BETWEEN :inicio AND :fim
                GROUP BY P.id_conta

                UNION ALL

                -- CONTAS A RECEBER
                SELECT
                    R.id_conta,
                    SUM(R.valor) AS total
                FROM contas_receber V
                JOIN contas_receber_parcelas R ON V.id = R.id_conta_receber
                WHERE V.uuid = :uuid
                AND R.status = 'RECEBIDO'
                AND R.data_pagamento BETWEEN :inicio AND :fim
                GROUP BY R.id_conta

            ) Z
            GROUP BY Z.id_conta
        ) T ON T.id_conta = C.id

        WHERE C.uuid = :uuid   -- 🔥 ESSA LINHA RESOLVE TUDO

        GROUP BY C.id, C.nome, C.tipo, C.natureza
        ORDER BY C.tipo, C.nome;";
        $qry = $this->db->prepare($sql);

        $qry->bindValue(":uuid",   $uuid, PDO::PARAM_STR);
        $qry->bindValue(":inicio", $datas['inicio'], PDO::PARAM_STR);
        $qry->bindValue(":fim",    $datas['fim'], PDO::PARAM_STR);
        $qry->execute();

        $result = $qry->fetchAll(\PDO::FETCH_OBJ);
        return $result;
    }

    public function contasReceberAbertas($uuid, $datas)
    {
        $sql = "SELECT 
                'A RECEBER' AS tipo,
                SUM(R.valor) AS total
            FROM contas_receber B
            join contas_receber_parcelas R ON B.id = R.id_conta_receber
            WHERE R.status = 'ABERTO'
            AND B.uuid = :uuid
            AND R.data_vencimento BETWEEN :inicio AND :fim";
        $qry = $this->db->prepare($sql);

        $qry->bindValue(":uuid",   $uuid, PDO::PARAM_STR);
        $qry->bindValue(":inicio", $datas['inicio'], PDO::PARAM_STR);
        $qry->bindValue(":fim",    $datas['fim'], PDO::PARAM_STR);
        $qry->execute();

        $result = $qry->fetch(\PDO::FETCH_OBJ);
        return $result;
    }

    public function contasPagarAbertas($uuid, $datas)
    {
        $sql = "SELECT 
                'A RECEBER' AS tipo,
                SUM(P.valor) AS total
            FROM contas_pagar A 
            JOIN contas_pagar_parcelas P ON A.id = p.id_conta_pagar
            WHERE P.status = 'ABERTO'
            AND A.uuid = :uuid
            AND P.data_vencimento BETWEEN :inicio AND :fim";
        $qry = $this->db->prepare($sql);

        $qry->bindValue(":uuid",   $uuid, PDO::PARAM_STR);
        $qry->bindValue(":inicio", $datas['inicio'], PDO::PARAM_STR);
        $qry->bindValue(":fim",    $datas['fim'], PDO::PARAM_STR);
        $qry->execute();

        $result = $qry->fetch(\PDO::FETCH_OBJ);
        return $result;
    }

    public function vendasPorCondicaoPgamento($uuid, $datas)
    {
        $sql = "SELECT
            COALESCE(SUM(T.total), 0) AS total,
            T.condicao
        FROM (
            -- LANCAMENTOS
            SELECT 
                SUM(L.valor) AS total,
                C.nome AS condicao
            FROM lancamentos L
            JOIN pagamentos C ON C.id = L.id_pagamento
            WHERE L.uuid = :uuid
            AND L.tipo = :tipo
            AND L.data BETWEEN :inicio AND :fim
            GROUP BY C.nome

            UNION ALL

            -- CONTAS A PAGAR (PAGAS)
            SELECT 
                SUM(P.valor) AS total,
                C.nome AS condicao
            FROM contas_pagar X
            JOIN contas_pagar_parcelas P ON X.id = P.id_conta_pagar
            JOIN pagamentos C ON C.id = P.id_pagamento
            WHERE P.status = 'PAGO'
            AND X.uuid = :uuid
            AND P.data_pagamento BETWEEN :inicio AND :fim
            GROUP BY C.nome) T

        GROUP BY T.condicao
        ORDER BY T.condicao;";
        $qry = $this->db->prepare($sql);

        $qry->bindValue(":uuid",   $uuid, PDO::PARAM_STR);
        $qry->bindValue(":tipo",   'DEBITO');
        $qry->bindValue(":inicio", $datas['inicio'], PDO::PARAM_STR);
        $qry->bindValue(":fim",    $datas['fim'], PDO::PARAM_STR);
        $qry->execute();

        $result = $qry->fetchAll(\PDO::FETCH_OBJ);
        return $result;
    }

    public function vendasPorUsuario($uuid, $datas)
    {
        $sql = "SELECT
            SUM(T.TOTAL) AS total,
            T.usuario
        FROM (
            -- LANCAMENTOS
            SELECT 
                SUM(L.valor) AS TOTAL,
                C.nome AS usuario                
            FROM lancamentos L
            JOIN usuarios C ON C.id = L.id_usuario
            where l.uuid = :uuid
            and l.tipo = :tipo
            and l.data BETWEEN :inicio and :fim
            GROUP BY C.nome

            UNION ALL

            -- CONTAS A PAGAR (PAGAS)
            SELECT 
                SUM(P.valor) AS TOTAL,
                C.nome AS usuario
            FROM contas_pagar x
            join contas_pagar_parcelas P on x.id = p.id_conta_pagar
            JOIN usuarios C ON C.id = x.id_usuario
            WHERE P.status = 'PAGO'
            and x.uuid = :uuid
            and p.data_pagamento BETWEEN :inicio and :fim
            GROUP BY C.nome
        ) T
        GROUP BY T.usuario
        ORDER BY T.usuario;";
        $qry = $this->db->prepare($sql);

        $qry->bindValue(":uuid",   $uuid, PDO::PARAM_STR);
        $qry->bindValue(":tipo",   'DEBITO');
        $qry->bindValue(":inicio", $datas['inicio'], PDO::PARAM_STR);
        $qry->bindValue(":fim",    $datas['fim'], PDO::PARAM_STR);
        $qry->execute();

        $result = $qry->fetchAll(\PDO::FETCH_OBJ);
        return $result;
    }
}
