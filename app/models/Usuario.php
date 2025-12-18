<?php

namespace app\models;

use app\core\Model;
use InvalidArgumentException;
use PDO;

class Usuario extends Model
{
    public function criarUsuario($usuario)
    {
        $sql = "INSERT INTO USUARIOS 
            SET uuid = :uuid, 
                nome = :nome, 
                email = :email, 
                senha = :senha, 
                telefone = :telefone, 
                ativo = :ativo";

        $qry = $this->db->prepare($sql);

        $qry->bindValue(":uuid",     $usuario->uuid);
        $qry->bindValue(":nome",     $usuario->nome);
        $qry->bindValue(":email",    $usuario->email);
        $qry->bindValue(":senha",    $usuario->senha);
        $qry->bindValue(":telefone", $usuario->telefone);
        $qry->bindValue(":ativo",    $usuario->ativo);

        if (!$qry->execute()) {
            $error = $qry->errorInfo();
            return $error;
        }
        return $this->db->lastInsertId();
    }

    public function autenticar($usuario)
    {
        $sql = 'SELECT * FROM USUARIOS WHERE email = :email LIMIT 1';
        $qry = $this->db->prepare($sql);

        $qry->bindValue(":email", $usuario->email);
        $qry->execute();

        return $qry->fetch(\PDO::FETCH_OBJ);
    }

    public function usuarioAll($uuid)
    {
        $sql = 'SELECT * FROM USUARIOS WHERE uuid = :uuid';
        $qry = $this->db->prepare($sql);

        $qry->bindValue(":uuid", $uuid, PDO::PARAM_STR);
        $qry->execute();

        $result = $qry->fetchAll(\PDO::FETCH_OBJ);
        return $result ?: null;
    }
    public function usuarioId(int $id): ?object
    {
        if ($id <= 0) {
            throw new InvalidArgumentException("ID inválido.");
        }

        $sql = 'SELECT * FROM USUARIOS WHERE id = :id';
        $qry = $this->db->prepare($sql);

        $qry->bindValue(":id", $id, PDO::PARAM_INT);
        $qry->execute();

        $result = $qry->fetch(\PDO::FETCH_OBJ);
        return $result ?: null;
    }

    public function atualizar(int $id, object $usuario): bool
    {
        if ($id <= 0) {
            throw new InvalidArgumentException("ID inválido.");
        }

        $sql = 'UPDATE USUARIOS SET 
                        nome = :nome, email = :email, telefone = :telefone, senha = :senha, ativo = :ativo
            WHERE id = :id';

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':nome',     $usuario->nome,     PDO::PARAM_STR);
        $stmt->bindValue(':email',    $usuario->email,    PDO::PARAM_STR);
        $stmt->bindValue(':telefone', $usuario->telefone, PDO::PARAM_STR);
        $stmt->bindValue(':senha',    $usuario->senha,    PDO::PARAM_STR);
        $stmt->bindValue(':ativo',    $usuario->ativo,    PDO::PARAM_STR);
        $stmt->bindValue(':id',       $id,                PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->rowCount() > 0;
    }
    public function excluir(int $id): bool
    {
        if ($id <= 0) {
            throw new InvalidArgumentException("ID inválido.");
        }

        $sql = 'DELETE FROM USUARIOS WHERE id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function existeMovimento(int $id): bool
    {
        if (isVazio($id)) {
            throw new InvalidArgumentException("Você precisa fazer login");
        }

        $sql = 'SELECT COUNT(l.id) AS total FROM lancamentos l join usuarios c on l.id_usuario = c.id
                        WHERE c.id = :id';
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_OBJ);
        return $result->total;
    }
}
