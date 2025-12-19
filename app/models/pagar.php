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
        $qry->bindValue(":valor_total",    $pagar->valor_total);
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
    public function contasAberta($uuid, $id = null)
    {
        $sql = 'SELECT p.id, p.descricao, p.data_emissao, c.numero_parcela,
            c.valor, c.data_vencimento, c.status, f.nome AS fornecedor,
            h.nome AS conta, c.id as id_parcela, u.nome as usuario
        FROM contas_pagar p INNER JOIN contas_pagar_parcelas c ON p.id = c.id_conta_pagar
        INNER JOIN fornecedores f ON p.id_fornecedor = f.id
        INNER JOIN contas h ON c.id_conta = h.id
        INNER JOIN usuarios u ON u.id = p.id_usuario
        WHERE c.data_vencimento > CURRENT_DATE
          AND p.uuid = :uuid
          AND c.status = :status';

        if (!empty($id)) {
            $sql .= ' AND c.id = :id';
        }

        $qry = $this->db->prepare($sql);

        $qry->bindValue(':uuid', $uuid, PDO::PARAM_STR);
        $qry->bindValue(':status', 'ABERTO', PDO::PARAM_STR);

        if (!empty($id)) {
            $qry->bindValue(':id', $id, PDO::PARAM_INT);
        }

        $qry->execute();

        if (!empty($id)) {
            return $qry->fetch(\PDO::FETCH_OBJ) ?: null;
        } else {
            return $qry->fetchAll(\PDO::FETCH_OBJ) ?: null;
        }
    }
}
