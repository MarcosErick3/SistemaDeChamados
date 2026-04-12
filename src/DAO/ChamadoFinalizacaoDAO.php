<?php

class ChamadoFinalizacaoDAO
{
    private $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
        $this->ensureSchema();
    }

    private function ensureSchema()
    {
        $dbName = $this->conn->query('SELECT DATABASE()')->fetchColumn();
        $stmt = $this->conn->prepare(
            'SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = :schema AND table_name = :table'
        );
        $stmt->execute([
            ':schema' => $dbName,
            ':table' => 'chamado_finalizacoes'
        ]);

        if ((int) $stmt->fetchColumn() === 0) {
            $sql = "CREATE TABLE chamado_finalizacoes (
                id INT AUTO_INCREMENT PRIMARY KEY,
                chamado_id INT NOT NULL UNIQUE,
                solucao TEXT DEFAULT NULL,
                pdf_path VARCHAR(255) DEFAULT NULL,
                data_finalizacao DATETIME DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_chamado_id (chamado_id)
            )";
            $this->conn->exec($sql);
        }
    }

    public function create(ChamadoFinalizacao $finalizacao)
    {
        $sql = "INSERT INTO chamado_finalizacoes (
                chamado_id,
                solucao,
                pdf_path
            ) VALUES (
                :chamado_id,
                :solucao,
                :pdf_path
            )";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':chamado_id', $finalizacao->getChamadoId(), PDO::PARAM_INT);
        $stmt->bindValue(':solucao', $finalizacao->getSolucao());
        $stmt->bindValue(':pdf_path', $finalizacao->getPdfPath());

        $executed = $stmt->execute();

        if ($executed) {
            return (int) $this->conn->lastInsertId();
        }

        return 0;
    }

    public function findByChamadoId($chamadoId)
    {
        $sql = "SELECT * FROM chamado_finalizacoes WHERE chamado_id = :chamado_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':chamado_id', $chamadoId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update(ChamadoFinalizacao $finalizacao)
    {
        $sql = "UPDATE chamado_finalizacoes SET
                solucao = :solucao,
                pdf_path = :pdf_path,
                data_finalizacao = NOW()
            WHERE chamado_id = :chamado_id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':solucao', $finalizacao->getSolucao());
        $stmt->bindValue(':pdf_path', $finalizacao->getPdfPath());
        $stmt->bindValue(':chamado_id', $finalizacao->getChamadoId(), PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function save(ChamadoFinalizacao $finalizacao)
    {
        $existing = $this->findByChamadoId($finalizacao->getChamadoId());
        if ($existing) {
            return $this->update($finalizacao);
        }
        return $this->create($finalizacao);
    }
}
