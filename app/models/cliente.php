<?php

namespace app\models;

use app\core\Model;
use InvalidArgumentException;
use PDO;

class Cliente extends Model
{
    public function criarCliente($cliente)
    {
        $sql = "INSERT INTO CLIENTES 
            SET uuid = :uuid, 
                nome = :nome, 
                ativo = :ativo";

        $qry = $this->db->prepare($sql);

        $qry->bindValue(":uuid",     $cliente->uuid);
        $qry->bindValue(":nome",     $cliente->nome);
        $qry->bindValue(":ativo",    $cliente->ativo);

        if (!$qry->execute()) {
            $error = $qry->errorInfo();
            return $error;
        }
        return $this->db->lastInsertId();
    }

    public function clienteAll($uuid)
    {
        $sql = 'SELECT * FROM CLIENTES WHERE uuid = :uuid';
        $qry = $this->db->prepare($sql);

        $qry->bindValue(":uuid", $uuid, PDO::PARAM_STR);
        $qry->execute();

        $result = $qry->fetchAll(\PDO::FETCH_OBJ);
        return $result ?: null;
    }
    public function clienteId(int $id): ?object
    {
        if ($id <= 0) {
            throw new InvalidArgumentException("ID inválido.");
        }

        $sql = 'SELECT * FROM CLIENTES WHERE id = :id';
        $qry = $this->db->prepare($sql);

        $qry->bindValue(":id", $id, PDO::PARAM_INT);
        $qry->execute();

        $result = $qry->fetch(\PDO::FETCH_OBJ);
        return $result ?: null;
    }

    public function atualizar(int $id, object $cliente): bool
    {
        if ($id <= 0) {
            throw new InvalidArgumentException("ID inválido.");
        }

        $sql = 'UPDATE CLIENTES SET 
                        nome = :nome, ativo = :ativo
                WHERE id = :id';

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':nome',     $cliente->nome,     PDO::PARAM_STR);
        $stmt->bindValue(':ativo',    $cliente->ativo,    PDO::PARAM_STR);
        $stmt->bindValue(':id',       $id,                PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
    public function excluir(int $id): bool
    {
        if ($id <= 0) {
            throw new InvalidArgumentException("ID inválido.");
        }

        $sql = 'DELETE FROM CLIENTES WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }
    public function existe(object $cliente): bool
    {
        if (isVazio($cliente->uuid)) {
            throw new InvalidArgumentException("Você precisa fazer login");
        }

        $sql = 'SELECT COUNT(*) AS total FROM CLIENTES WHERE nome = :nome and uuid = :uuid';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':nome', $cliente->nome, PDO::PARAM_STR);
        $stmt->bindValue(':uuid', $cliente->uuid, PDO::PARAM_STR);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result->total;
    }
    public function notClientePadrao($id)
    {
        if (isVazio($id)) {
            throw new InvalidArgumentException("Você precisa fazer login");
        }

        $sql = 'SELECT nome FROM CLIENTES WHERE id = :id LIMIT 1';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_OBJ);
        if (mb_strtoupper($result->nome) == 'CLIENTE PADRAO' || mb_strtoupper($result->nome) == 'CLIENTE PADRÃO') {
            throw new InvalidArgumentException("Você não pode excluir o Cliente padrão, voce pode desativar!.");
        }
    }
}
