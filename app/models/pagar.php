<?php

namespace app\models;

use app\core\Model;
use InvalidArgumentException;
use PDO;

class Pagar extends Model
{
    public function criarDocumentoPagar($pagar)
    {
        $sql = "INSERT INTO CONTAS_PAGAR  SET 
                     descricao      = :descricao, 
                     qtde_parcelas  = :qtde_parcelas, 
                     parcelado      = :parcelado,                  
                     uuid           = :uuid,
                     id_usuario     = :id_usuario,
                     valor_total    = :valor_total,
                     id_fornecedor  = :id_fornecedor";

        $qry = $this->db->prepare($sql);

        $qry->bindValue(":descricao",     $pagar->descricao);
        $qry->bindValue(":qtde_parcelas", $pagar->qtde_parcelas);
        $qry->bindValue(":parcelado",     $pagar->parcelado);
        $qry->bindValue(":uuid",          $pagar->uuid);
        $qry->bindValue(":id_usuario",    $pagar->id_usuario);
        $qry->bindValue(":valor_total",   $pagar->valor_total);
        $qry->bindValue(":id_fornecedor", $pagar->id_fornecedor);

        if (!$qry->execute()) {
            $error = $qry->errorInfo();
            return $error;
        }
        return $this->db->lastInsertId();
    }
    public function criarDocumentoPagarParcelas($pagar)
    {
        $sql = "INSERT INTO CONTAS_PAGAR_PARCELAS  SET 
                     id_conta_pagar  = :id_conta_pagar, 
                     id_conta        = :id_conta, 
                     status          = :status,                  
                     numero_parcela  = :numero_parcela,
                     valor           = :valor,
                     data_vencimento = :data_vencimento,             
                     pago_por        = :pago_por";

        $qry = $this->db->prepare($sql);

        $qry->bindValue(":id_conta_pagar",  $pagar['id_conta_pagar']);
        $qry->bindValue(":id_conta",        $pagar['id_conta']);
        $qry->bindValue(":status",          $pagar['status']);
        $qry->bindValue(":numero_parcela",  $pagar['numero_parcela']);
        $qry->bindValue(":valor",           $pagar['valor']);
        $qry->bindValue(":data_vencimento", $pagar['data_vencimento']);
        $qry->bindValue(":pago_por",        $pagar['pago_por']);

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
            f.nome AS fornecedor, 
            h.id AS id_conta,
            h.nome AS conta, 
            c.id AS id_parcela, 
            u.nome AS usuario
        FROM contas_pagar p 
        INNER JOIN contas_pagar_parcelas c ON p.id = c.id_conta_pagar
        INNER JOIN fornecedores f          ON p.id_fornecedor = f.id
        INNER JOIN contas h                ON c.id_conta = h.id
        INNER JOIN usuarios u              ON u.id = p.id_usuario
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
            f.nome AS fornecedor, 
            h.id AS id_conta,
            h.nome AS conta, 
            c.id AS id_parcela, 
            u.nome AS usuario
        FROM contas_pagar p 
        INNER JOIN contas_pagar_parcelas c ON p.id = c.id_conta_pagar
        INNER JOIN fornecedores f          ON p.id_fornecedor = f.id
        INNER JOIN contas h                ON c.id_conta = h.id
        INNER JOIN usuarios u              ON u.id = p.id_usuario
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
            f.nome AS fornecedor, 
            h.id AS id_conta,
            h.nome AS conta, 
            c.id AS id_parcela, 
            u.nome AS usuario
        FROM contas_pagar p 
        INNER JOIN contas_pagar_parcelas c ON p.id = c.id_conta_pagar
        INNER JOIN fornecedores f          ON p.id_fornecedor = f.id
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
    public function contasPagas($uuid)
    {
        $sql = "SELECT 
            p.id, 
            p.descricao, 
            p.data_emissao, 
            c.numero_parcela,
            c.valor, 
            c.data_vencimento, 
            c.status, 
            f.nome AS fornecedor, 
            h.id AS id_conta,
            h.nome AS conta, 
            c.id AS id_parcela, 
            u.nome AS usuario
        FROM contas_pagar p 
        INNER JOIN contas_pagar_parcelas c ON p.id = c.id_conta_pagar
        INNER JOIN fornecedores f          ON p.id_fornecedor = f.id
        INNER JOIN contas h                ON c.id_conta = h.id
        INNER JOIN usuarios u              ON u.id = p.id_usuario
        WHERE c.status = :status
          AND p.uuid   = :uuid";

        $qry = $this->db->prepare($sql);

        $qry->bindValue(':uuid', $uuid, PDO::PARAM_STR);
        $qry->bindValue(':status', 'PAGO', PDO::PARAM_STR);
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
        FROM contas_pagar p
        INNER JOIN contas_pagar_parcelas c ON p.id = c.id_conta_pagar
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

        $sql = "DELETE FROM contas_pagar_parcelas WHERE id = :id";
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

        $sql = "DELETE FROM contas_pagar WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id_parcela, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
    public function pagar(int $id, object $pagar): bool
    {
        if ($id <= 0) {
            throw new InvalidArgumentException("ID inválido.");
        }

        $sql = "UPDATE contas_pagar_parcelas SET 
            id_conta       = :id_conta, 
            id_pagamento   = :id_pagamento, 
            data_pagamento = :data_pagamento, 
            status         = :status
        WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':id_conta',       $pagar->id_conta,     PDO::PARAM_INPUT_OUTPUT);
        $stmt->bindValue(':id_pagamento',   $pagar->id_pagamento,    PDO::PARAM_INT);
        $stmt->bindValue(':data_pagamento', $pagar->data_pagamento, PDO::PARAM_STR);
        $stmt->bindValue(':status',         $pagar->status, PDO::PARAM_STR);
        $stmt->bindValue(':id',             $id,                PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
    public function cancelarPagamento(int $id): bool
    {
        if ($id <= 0) {
            throw new InvalidArgumentException("ID inválido.");
        }

        $sql = "UPDATE contas_pagar_parcelas SET 
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
