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
        $sqlCheck = "SELECT COUNT(*) FROM tecnicos WHERE email = :email";
        $stmtCheck = $this->conn->prepare($sqlCheck);
        $stmtCheck->bindValue(':email', $tecnico->getEmail());
        $stmtCheck->execute();
        
        if ($stmtCheck->fetchColumn() > 0) {
            throw new Exception('Já existe um técnico cadastrado com este email.');
        }

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
        // Query compatível com sql_mode=only_full_group_by
        // Seleciona apenas o registro mais antigo (menor ID) para cada email
        $sql = "SELECT t1.id, t1.nome, t1.email, t1.telefone
                FROM tecnicos t1
                LEFT JOIN tecnicos t2 ON t1.email = t2.email AND t1.id > t2.id
                WHERE t2.id IS NULL
                ORDER BY t1.nome ASC";
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
        $stmt->bindValue(':email', trim((string) $email));
        $stmt->execute();
        $tecnico = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$tecnico || !password_verify($senha, $tecnico['senha'])) {
            return false;
        }

        if (password_needs_rehash($tecnico['senha'], PASSWORD_DEFAULT)) {
            $novoHash = password_hash($senha, PASSWORD_DEFAULT);
            $update = $this->conn->prepare("UPDATE tecnicos SET senha = :senha WHERE id = :id");
            $update->bindValue(':senha', $novoHash);
            $update->bindValue(':id', $tecnico['id'], PDO::PARAM_INT);
            $update->execute();

            $tecnico['senha'] = $novoHash;
        }

        return $tecnico;
    }
}
