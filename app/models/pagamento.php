<?php

namespace app\models;

use app\core\Model;
use InvalidArgumentException;
use PDO;

class Pagamento extends Model
{
    public function criarPagamento($pagamento)
    {
        $sql = "INSERT INTO PAGAMENTOS 
            SET uuid = :uuid, 
                nome = :nome, 
                ativo = :ativo";

        $qry = $this->db->prepare($sql);

        $qry->bindValue(":uuid",     $pagamento->uuid);
        $qry->bindValue(":nome",     $pagamento->nome);
        $qry->bindValue(":ativo",    $pagamento->ativo);

        if (!$qry->execute()) {
            $error = $qry->errorInfo();
            return $error;
        }
        return $this->db->lastInsertId();
    }

    public function pagamentoAll($uuid)
    {
        $sql = 'SELECT * FROM PAGAMENTOS WHERE uuid = :uuid';
        $qry = $this->db->prepare($sql);

        $qry->bindValue(":uuid", $uuid, PDO::PARAM_STR);
        $qry->execute();

        $result = $qry->fetchAll(\PDO::FETCH_OBJ);
        return $result ?: null;
    }
    public function pagamentoId(int $id): ?object
    {
        if ($id <= 0) {
            throw new InvalidArgumentException("ID inválido.");
        }

        $sql = 'SELECT * FROM PAGAMENTOS WHERE id = :id';
        $qry = $this->db->prepare($sql);

        $qry->bindValue(":id", $id, PDO::PARAM_INT);
        $qry->execute();

        $result = $qry->fetch(\PDO::FETCH_OBJ);
        return $result ?: null;
    }

    public function atualizar(int $id, object $pagamento): bool
    {
        if ($id <= 0) {
            throw new InvalidArgumentException("ID inválido.");
        }

        $sql = 'UPDATE PAGAMENTOS SET 
                        nome = :nome, ativo = :ativo
                WHERE id = :id';

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':nome',     $pagamento->nome,     PDO::PARAM_STR);
        $stmt->bindValue(':ativo',    $pagamento->ativo,    PDO::PARAM_STR);       
        $stmt->bindValue(':id',       $id,                PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
    public function excluir(int $id): bool
    {
        if ($id <= 0) {
            throw new InvalidArgumentException("ID inválido.");
        }

        $sql = 'DELETE FROM PAGAMENTOS WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
    public function existe(object $pagamento): bool
    {
        if (isVazio($pagamento->uuid)) {
            throw new InvalidArgumentException("Você precisa fazer login");
        }

        $sql = 'SELECT COUNT(*) AS total FROM PAGAMENTOS WHERE nome = :nome and uuid = :uuid';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':nome', $pagamento->nome, PDO::PARAM_STR);
        $stmt->bindValue(':uuid', $pagamento->uuid, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result->total;
    }
}
