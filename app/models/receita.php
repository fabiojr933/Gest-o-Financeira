<?php

namespace app\models;

use app\core\Model;
use InvalidArgumentException;
use PDO;

class Receita extends Model
{
    public function criarReceita($receita)
    {
        $sql = "INSERT INTO CONTAS SET 
            uuid     = :uuid, 
            nome     = :nome, 
            ativo    = :ativo,
            natureza = :natureza,
            tipo     = 'RECEITA'";

        $qry = $this->db->prepare($sql);

        $qry->bindValue(":uuid",     $receita->uuid);
        $qry->bindValue(":nome",     $receita->nome);
        $qry->bindValue(":ativo",    $receita->ativo);
        $qry->bindValue(":natureza", $receita->natureza);

        if (!$qry->execute()) {
            $error = $qry->errorInfo();
            return $error;
        }
        return $this->db->lastInsertId();
    }

    public function receitaAll($uuid)
    {
        $sql = "SELECT * FROM CONTAS WHERE uuid = :uuid and tipo = :tipo";
        $qry = $this->db->prepare($sql);

        $qry->bindValue(":uuid", $uuid, PDO::PARAM_STR);
        $qry->bindValue(":tipo", 'RECEITA');
        $qry->execute();

        $result = $qry->fetchAll(\PDO::FETCH_OBJ);
        return $result ?: null;
    }
    public function receitaId(int $id): ?object
    {
        if ($id <= 0) {
            throw new InvalidArgumentException("ID inválido.");
        }

        $sql = "SELECT * FROM CONTAS WHERE id = :id";
        $qry = $this->db->prepare($sql);

        $qry->bindValue(":id", $id, PDO::PARAM_INT);
        $qry->execute();

        $result = $qry->fetch(\PDO::FETCH_OBJ);
        return $result ?: null;
    }

    public function atualizar(int $id, object $receita): bool
    {
        if ($id <= 0) {
            throw new InvalidArgumentException("ID inválido.");
        }

        $sql = "UPDATE CONTAS SET 
            nome     = :nome, 
            ativo    = :ativo, 
            natureza = :natureza, 
            tipo     = :tipo
        WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':nome',     $receita->nome,     PDO::PARAM_STR);
        $stmt->bindValue(':ativo',    $receita->ativo,    PDO::PARAM_STR);
        $stmt->bindValue(':natureza', $receita->natureza, PDO::PARAM_STR);
        $stmt->bindValue(':tipo',     'RECEITA');
        $stmt->bindValue(':id',       $id,                PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
    public function excluir(int $id): bool
    {
        if ($id <= 0) {
            throw new InvalidArgumentException("ID inválido.");
        }

        $sql = "DELETE FROM contas WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
    public function existe(object $receita): bool
    {
        if (isVazio($receita->uuid)) {
            throw new InvalidArgumentException("Você precisa fazer login");
        }

        $sql = "SELECT COUNT(*) AS total FROM contas WHERE nome = :nome and uuid = :uuid and tipo = :tipo";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':nome', $receita->nome, PDO::PARAM_STR);
        $stmt->bindValue(':uuid', $receita->uuid, PDO::PARAM_STR);
        $stmt->bindValue(':tipo', 'RECEITA');
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result->total;
    }
    public function existeMovimento(int $id): bool
    {
        if (isVazio($id)) {
            throw new InvalidArgumentException("Você precisa fazer login");
        }

        $sql = "SELECT COUNT(l.id) AS total FROM lancamentos l join contas c on l.id_conta = c.id
                        WHERE c.id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result->total;
    }
}
