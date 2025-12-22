<?php

namespace app\models;

use app\core\Model;
use InvalidArgumentException;
use PDO;

class Lancamento extends Model
{
    public function novoLancamento($lancamento)
    {
        $sql = "INSERT INTO lancamentos 
            SET descricao    = :descricao, 
                data         = :data, 
                valor        = :valor,
                tipo         = :tipo,
                id_conta     = :id_conta,
                id_usuario   = :id_usuario,
                id_pagamento = :id_pagamento,
                uuid         = :uuid";

        $qry = $this->db->prepare($sql);
        $qry->bindValue(":descricao",     $lancamento->descricao);
        $qry->bindValue(":data",          $lancamento->data);
        $qry->bindValue(":valor",         $lancamento->valor);
        $qry->bindValue(":tipo",          $lancamento->tipo);
        $qry->bindValue(":id_conta",      $lancamento->id_conta);
        $qry->bindValue(":id_usuario",    $lancamento->id_usuario);
        $qry->bindValue(":id_pagamento",  $lancamento->id_pagamento);
        $qry->bindValue(":uuid",          $lancamento->uuid);

        if (!$qry->execute()) {
            $error = $qry->errorInfo();
            return $error;
        }
        return $this->db->lastInsertId();
    }

    public function lancamentoAll($uuid, $datas)
    {
        $sql = "SELECT * FROM lancamentos WHERE uuid = :uuid and data BETWEEN :inicio and :fim";
        $qry = $this->db->prepare($sql);

        $qry->bindValue(":uuid",   $uuid, PDO::PARAM_STR);
        $qry->bindValue(":inicio", $datas['inicio'], PDO::PARAM_STR);
        $qry->bindValue(":fim",    $datas['fim'], PDO::PARAM_STR);
        $qry->execute();

        $result = $qry->fetchAll(\PDO::FETCH_OBJ);
        return $result ?: null;
    }

    public function excluir(int $id): bool
    {
        if ($id <= 0) {
            throw new InvalidArgumentException("ID inválido.");
        }

        $sql = "DELETE FROM LANCAMENTOS WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function visualizar(int $id)
    {
        $sql = "SELECT 
                lanc.id,
                lanc.descricao,
                lanc.data,
                lanc.valor,
                lanc.tipo,
                c.nome AS conta,
                p.nome AS pagamento,
                u.nome AS usuario
            FROM lancamentos lanc
            JOIN contas c ON lanc.id_conta = c.id
            JOIN pagamentos p ON lanc.id_pagamento = p.id
            JOIN usuarios u ON lanc.id_usuario = u.id
            WHERE lanc.id = :id";

        $qry = $this->db->prepare($sql);
        $qry->bindValue(':id', $id, PDO::PARAM_INT);
        $qry->execute();

        return $qry->fetch(PDO::FETCH_OBJ) ?: null;
    }
}
