<?php

class TecnicoDAO
{
    private $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function create(Tecnico $tecnico)
    {
        $sql = "INSERT INTO tecnicos (nome, email, telefone, senha) VALUES (:nome, :email, :telefone, :senha)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':nome', $tecnico->getNome());
        $stmt->bindValue(':email', $tecnico->getEmail());
        $stmt->bindValue(':telefone', $tecnico->getTelefone());
        $stmt->bindValue(':senha', password_hash($tecnico->getSenha(), PASSWORD_DEFAULT));
        return $stmt->execute();
    }

    public function findById($id)
    {
        $sql = "SELECT * FROM tecnicos WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findAll()
    {
        $sql = "SELECT * FROM tecnicos ORDER BY nome ASC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function update(Tecnico $tecnico)
    {
        $sql = "UPDATE tecnicos SET nome = :nome, email = :email, telefone = :telefone, senha = :senha WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':nome', $tecnico->getNome());
        $stmt->bindValue(':email', $tecnico->getEmail());
        $stmt->bindValue(':telefone', $tecnico->getTelefone());
        $stmt->bindValue(':senha', password_hash($tecnico->getSenha(), PASSWORD_DEFAULT));
        $stmt->bindValue(':id', $tecnico->getId(), PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function delete($id)
    {
        $sql = "DELETE FROM tecnicos WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function authenticate($email, $senha)
    {
        $sql = "SELECT * FROM tecnicos WHERE email = :email";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $tecnico = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($tecnico && password_verify($senha, $tecnico['senha'])) {
            return $tecnico;
        }
        return false;
    }
}
