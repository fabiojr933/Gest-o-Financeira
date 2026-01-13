<?php

namespace app\models;

use app\core\Model;
use InvalidArgumentException;
use PDO;

class Receber extends Model
{
    public function criarDocumentoReceber($receber)
    {
        $sql = "INSERT INTO CONTAS_RECEBER  SET 
                     descricao      = :descricao, 
                     qtde_parcelas  = :qtde_parcelas, 
                     parcelado      = :parcelado,                  
                     uuid           = :uuid,
                     id_usuario     = :id_usuario,
                     valor_total    = :valor_total,
                     id_cliente     = :id_cliente";

        $qry = $this->db->prepare($sql);

        $qry->bindValue(":descricao",     $receber->descricao);
        $qry->bindValue(":qtde_parcelas", $receber->qtde_parcelas);
        $qry->bindValue(":parcelado",     $receber->parcelado);
        $qry->bindValue(":uuid",          $receber->uuid);
        $qry->bindValue(":id_usuario",    $receber->id_usuario);
        $qry->bindValue(":valor_total",   $receber->valor_total);
        $qry->bindValue(":id_cliente",    $receber->id_cliente);

        if (!$qry->execute()) {
            $error = $qry->errorInfo();
            return $error;
        }
        return $this->db->lastInsertId();
    }
    public function criarDocumentoReceberParcelas($receber)
    {
        $sql = "INSERT INTO CONTAS_RECEBER_PARCELAS  SET 
                     id_conta_receber  = :id_conta_receber, 
                     id_conta          = :id_conta, 
                     status            = :status,                  
                     numero_parcela    = :numero_parcela,
                     valor             = :valor,
                     data_vencimento   = :data_vencimento,             
                     recebido_por      = :recebido_por";

        $qry = $this->db->prepare($sql);

        $qry->bindValue(":id_conta_receber",  $receber['id_conta_receber']);
        $qry->bindValue(":id_conta",          $receber['id_conta']);
        $qry->bindValue(":status",            $receber['status']);
        $qry->bindValue(":numero_parcela",    $receber['numero_parcela']);
        $qry->bindValue(":valor",             $receber['valor']);
        $qry->bindValue(":data_vencimento",   $receber['data_vencimento']);
        $qry->bindValue(":recebido_por",          $receber['recebido_por']);

        if (!$qry->execute()) {
            $error = $qry->errorInfo();
            return $error;
        }
        return $this->db->lastInsertId();
    }
    public function visualizar($uuid, $id = null)
    {
        $sql = "SELECT 
            p.id, 
            p.descricao, 
            p.data_emissao, 
            c.numero_parcela,
            c.valor, 
            c.data_vencimento, 
            c.status, 
            f.nome AS cliente, 
            h.id AS id_conta,
            h.nome AS conta, 
            c.id AS id_parcela, 
            u.nome AS usuario
        FROM contas_receber p 
        INNER JOIN contas_receber_parcelas c ON p.id = c.id_conta_receber
        INNER JOIN clientes f                ON p.id_cliente = f.id
        INNER JOIN contas h                  ON c.id_conta = h.id
        INNER JOIN usuarios u                ON u.id = p.id_usuario
        WHERE c.id = :id
          AND p.uuid = :uuid";

        $qry = $this->db->prepare($sql);

        $qry->bindValue(':uuid', $uuid, PDO::PARAM_STR);
        $qry->bindValue(':id', $id, PDO::PARAM_INT);

        $qry->execute();
        return $qry->fetch(\PDO::FETCH_OBJ) ?: null;
    }
    public function contasAberta($uuid)
    {
        $sql = "SELECT 
            p.id, 
            p.descricao, 
            p.data_emissao, 
            c.numero_parcela,
            c.valor, 
            c.data_vencimento, 
            c.status, 
            f.nome AS cliente, 
            h.id AS id_conta,
            h.nome AS conta, 
            c.id AS id_parcela, 
            u.nome AS usuario
        FROM contas_receber p 
        INNER JOIN contas_receber_parcelas c ON p.id = c.id_conta_receber
        INNER JOIN clientes f                ON p.id_cliente = f.id
        INNER JOIN contas h                  ON c.id_conta = h.id
        INNER JOIN usuarios u                ON u.id = p.id_usuario
        WHERE c.data_vencimento >= CURRENT_DATE
          AND p.uuid = :uuid
          AND c.status = :status";

        $qry = $this->db->prepare($sql);

        $qry->bindValue(':uuid', $uuid, PDO::PARAM_STR);
        $qry->bindValue(':status', 'ABERTO', PDO::PARAM_STR);


        $qry->execute();
        return $qry->fetchAll(\PDO::FETCH_OBJ) ?: null;
    }
    public function contasVencida($uuid)
    {
        $sql = "SELECT 
            p.id, 
            p.descricao, 
            p.data_emissao, 
            c.numero_parcela,
            c.valor, 
            c.data_vencimento, 
            c.status, 
            f.nome AS cliente, 
            h.id AS id_conta,
            h.nome AS conta, 
            c.id AS id_parcela, 
            u.nome AS usuario
        FROM contas_receber p 
        INNER JOIN contas_receber_parcelas c ON p.id = c.id_conta_receber
        INNER JOIN clientes f              ON p.id_cliente = f.id
        INNER JOIN contas h                ON c.id_conta = h.id
        INNER JOIN usuarios u              ON u.id = p.id_usuario
        WHERE c.data_vencimento < CURRENT_DATE
          AND p.uuid = :uuid
          AND c.status = :status";

        $qry = $this->db->prepare($sql);

        $qry->bindValue(':uuid', $uuid, PDO::PARAM_STR);
        $qry->bindValue(':status', 'ABERTO', PDO::PARAM_STR);

        if (!empty($id)) {
            $qry->bindValue(':id', $id, PDO::PARAM_INT);
        }

        $qry->execute();
        return $qry->fetchAll(\PDO::FETCH_OBJ) ?: null;
    }
    public function contasRecebidas($uuid)
    {
        $sql = "SELECT 
            p.id, 
            p.descricao, 
            p.data_emissao, 
            c.numero_parcela,
            c.valor, 
            c.data_vencimento, 
            c.status, 
            f.nome AS cliente, 
            h.id AS id_conta,
            h.nome AS conta, 
            c.id AS id_parcela, 
            u.nome AS usuario
        FROM contas_receber p 
        INNER JOIN contas_receber_parcelas c ON p.id = c.id_conta_receber
        INNER JOIN clientes f                ON p.id_cliente = f.id
        INNER JOIN contas h                  ON c.id_conta = h.id
        INNER JOIN usuarios u                ON u.id = p.id_usuario
        WHERE c.status = :status
          AND p.uuid   = :uuid";

        $qry = $this->db->prepare($sql);

        $qry->bindValue(':uuid', $uuid, PDO::PARAM_STR);
        $qry->bindValue(':status', 'RECEBIDO', PDO::PARAM_STR);
        $qry->execute();
        return $qry->fetchAll(\PDO::FETCH_OBJ) ?: null;
    }
    public function existeParcela(int $id_doc): bool
    {
        if (isVazio($id_doc)) {
            throw new InvalidArgumentException("Você precisa fazer login");
        }

        $sql = "SELECT 
            COUNT(p.id) AS total 
        FROM contas_receber p
        INNER JOIN contas_receber_parcelas c ON p.id = c.id_conta_receber
        WHERE p.id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id_doc, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result->total;
    }
    public function excluirPacela(int $id_parcela): bool
    {
        if ($id_parcela <= 0) {
            throw new InvalidArgumentException("ID inválido.");
        }

        $sql = "DELETE FROM contas_receber_parcelas WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id_parcela, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
    public function excluirDoc(int $id_parcela): bool
    {
        if ($id_parcela <= 0) {
            throw new InvalidArgumentException("ID inválido.");
        }

        $sql = "DELETE FROM contas_receber WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id_parcela, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
    public function receber(int $id, object $receber): bool
    {
        if ($id <= 0) {
            throw new InvalidArgumentException("ID inválido.");
        }

        $sql = "UPDATE contas_receber_parcelas SET 
            id_conta       = :id_conta, 
            id_pagamento   = :id_pagamento, 
            data_pagamento = :data_pagamento, 
            status         = :status
        WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':id_conta',       $receber->id_conta,     PDO::PARAM_INPUT_OUTPUT);
        $stmt->bindValue(':id_pagamento',   $receber->id_pagamento,    PDO::PARAM_INT);
        $stmt->bindValue(':data_pagamento', $receber->data_pagamento, PDO::PARAM_STR);
        $stmt->bindValue(':status',         $receber->status, PDO::PARAM_STR);
        $stmt->bindValue(':id',             $id,                PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
    public function cancelarRecebimento(int $id): bool
    {
        if ($id <= 0) {
            throw new InvalidArgumentException("ID inválido.");
        }

        $sql = "UPDATE contas_receber_parcelas SET 
            id_pagamento   = :id_pagamento, 
            data_pagamento = :data_pagamento, 
            status         = :status
        WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':id_pagamento',   null,    PDO::PARAM_INT);
        $stmt->bindValue(':data_pagamento', null, PDO::PARAM_STR);
        $stmt->bindValue(':status',         'ABERTO', PDO::PARAM_STR);
        $stmt->bindValue(':id',             $id,                PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
}
