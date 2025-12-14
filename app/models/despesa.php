<?php

namespace app\models;

use app\core\Model;
use InvalidArgumentException;
use PDO;

class Despesa extends Model
{
    public function criarDespesas($despesa)
    {
        $sql = "INSERT INTO CONTAS 
            SET uuid = :uuid, 
                nome = :nome, 
                ativo = :ativo,
                natureza = :natureza,
                tipo = 'DESPESA'";

        $qry = $this->db->prepare($sql);

        $qry->bindValue(":uuid",     $despesa->uuid);
        $qry->bindValue(":nome",     $despesa->nome);
        $qry->bindValue(":ativo",    $despesa->ativo);
        $qry->bindValue(":natureza", $despesa->natureza);
       

        if (!$qry->execute()) {
            $error = $qry->errorInfo();
            return $error;
        }
        return $this->db->lastInsertId();
    }

    public function despesaAll($uuid)
    {
        $sql = 'SELECT * FROM CONTAS WHERE uuid = :uuid and tipo = :tipo';
        $qry = $this->db->prepare($sql);

        $qry->bindValue(":uuid", $uuid, PDO::PARAM_STR);
        $qry->bindValue(":tipo", 'DESPESA');
        $qry->execute();

        $result = $qry->fetchAll(\PDO::FETCH_OBJ);
        return $result ?: null;
    }
    public function despesaId(int $id): ?object
    {
        if ($id <= 0) {
            throw new InvalidArgumentException("ID inválido.");
        }

        $sql = 'SELECT * FROM CONTAS WHERE id = :id';
        $qry = $this->db->prepare($sql);

        $qry->bindValue(":id", $id, PDO::PARAM_INT);
        $qry->execute();

        $result = $qry->fetch(\PDO::FETCH_OBJ);
        return $result ?: null;
    }

    public function atualizar(int $id, object $despesa): bool
    {
        if ($id <= 0) {
            throw new InvalidArgumentException("ID inválido.");
        }

        $sql = 'UPDATE CONTAS SET 
                        nome = :nome, ativo = :ativo, natureza = :natureza
                WHERE id = :id';

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':nome',     $despesa->nome,     PDO::PARAM_STR);
        $stmt->bindValue(':ativo',    $despesa->ativo,    PDO::PARAM_STR);
        $stmt->bindValue(':natureza', $despesa->natureza, PDO::PARAM_STR);
        $stmt->bindValue(':id',       $id,                PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
    public function excluir(int $id): bool
    {
        if ($id <= 0) {
            throw new InvalidArgumentException("ID inválido.");
        }

        $sql = 'DELETE FROM contas WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
    public function existe(object $despesa): bool
    {
        if (isVazio($despesa->uuid)) {
            throw new InvalidArgumentException("Você precisa fazer login");
        }

        $sql = 'SELECT COUNT(*) AS total FROM contas WHERE nome = :nome and uuid = :uuid and tipo = :tipo';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':nome', $despesa->nome, PDO::PARAM_STR);
        $stmt->bindValue(':uuid', $despesa->uuid, PDO::PARAM_STR);
        $stmt->bindValue(':tipo', 'DESPESA');
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result->total;
    }
}
