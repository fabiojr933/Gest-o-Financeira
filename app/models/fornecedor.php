<?php

namespace app\models;

use app\core\Model;
use InvalidArgumentException;
use PDO;

class Fornecedor extends Model
{
    public function criarFornecedor($fornecedor)
    {
        $sql = "INSERT INTO FORNECEDORES 
            SET uuid = :uuid, 
                nome = :nome, 
                ativo = :ativo";

        $qry = $this->db->prepare($sql);

        $qry->bindValue(":uuid",     $fornecedor->uuid);
        $qry->bindValue(":nome",     $fornecedor->nome);
        $qry->bindValue(":ativo",    $fornecedor->ativo);

        if (!$qry->execute()) {
            $error = $qry->errorInfo();
            return $error;
        }
        return $this->db->lastInsertId();
    }

    public function fornecedorAll($uuid)
    {
        $sql = 'SELECT * FROM FORNECEDORES WHERE uuid = :uuid';
        $qry = $this->db->prepare($sql);

        $qry->bindValue(":uuid", $uuid, PDO::PARAM_STR);
        $qry->execute();

        $result = $qry->fetchAll(\PDO::FETCH_OBJ);
        return $result ?: null;
    }
    public function fornecedorId(int $id): ?object
    {
        if ($id <= 0) {
            throw new InvalidArgumentException("ID inválido.");
        }

        $sql = 'SELECT * FROM FORNECEDORES WHERE id = :id';
        $qry = $this->db->prepare($sql);

        $qry->bindValue(":id", $id, PDO::PARAM_INT);
        $qry->execute();

        $result = $qry->fetch(\PDO::FETCH_OBJ);
        return $result ?: null;
    }

    public function atualizar(int $id, object $fornecedor): bool
    {
        if ($id <= 0) {
            throw new InvalidArgumentException("ID inválido.");
        }

        $sql = 'UPDATE FORNECEDORES SET 
                        nome = :nome, ativo = :ativo
                WHERE id = :id';

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':nome',     $fornecedor->nome,     PDO::PARAM_STR);
        $stmt->bindValue(':ativo',    $fornecedor->ativo,    PDO::PARAM_STR);
        $stmt->bindValue(':id',       $id,                PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
    public function excluir(int $id): bool
    {
        if ($id <= 0) {
            throw new InvalidArgumentException("ID inválido.");
        }

        $sql = 'DELETE FROM FORNECEDORES WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
    public function existe(object $fornecedor): bool
    {
        if (isVazio($fornecedor->uuid)) {
            throw new InvalidArgumentException("Você precisa fazer login");
        }

        $sql = 'SELECT COUNT(*) AS total FROM FORNECEDORES WHERE nome = :nome and uuid = :uuid';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':nome', $fornecedor->nome, PDO::PARAM_STR);
        $stmt->bindValue(':uuid', $fornecedor->uuid, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result->total;
    }
    public function notFornecedorPadrao($id)
    {
        if (isVazio($id)) {
            throw new InvalidArgumentException("Você precisa fazer login");
        }

        $sql = 'SELECT nome FROM FORNECEDORES WHERE id = :id LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        if (mb_strtoupper($result->nome) == 'FORNECEDOR PADRAO' || mb_strtoupper($result->nome) == 'FORNECEDOR PADRÃO') {
            throw new InvalidArgumentException("Você não pode excluir o Fornecedor padrão, voce pode desativar!.");
        }
    }
}
