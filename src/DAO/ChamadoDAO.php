<?php

class ChamadoDAO
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

        $columns = [
            'tecnico_id' => 'INT DEFAULT NULL',
            'tecnico_supervisor' => 'VARCHAR(100) DEFAULT NULL',
            'data_atendimento' => 'DATE DEFAULT NULL',
            'solucao' => 'TEXT NULL',
            'pdf_path' => 'VARCHAR(255) DEFAULT NULL',
            'data_finalizacao' => 'DATETIME DEFAULT NULL'
        ];

        foreach ($columns as $column => $definition) {
            $stmt = $this->conn->prepare(
                'SELECT COUNT(*) FROM information_schema.columns WHERE table_schema = :schema AND table_name = :table AND column_name = :column'
            );
            $stmt->execute([
                ':schema' => $dbName,
                ':table' => 'chamados',
                ':column' => $column
            ]);

            if ((int)$stmt->fetchColumn() === 0) {
                $this->conn->exec("ALTER TABLE chamados ADD COLUMN {$column} {$definition}");
            }
        }

        $stmt = $this->conn->prepare(
            'SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = :schema AND table_name = :table'
        );
        $stmt->execute([
            ':schema' => $dbName,
            ':table' => 'chamado_detalhes'
        ]);

        if ((int) $stmt->fetchColumn() > 0) {
            $this->conn->exec('DROP TABLE chamado_detalhes');
        }
    }

    public function criarChamado(Chamado $chamado)
    {
        $sql = "INSERT INTO chamados (
                equipamento_id,
                criado_por,
                status,
                tipo_chamado,
                prioridade,
                tecnico_id,
                tecnico,
                assunto,
                descricao,
                nome_usuario,
                email_usuario,
                ddd_usuario,
                telefone_usuario,
                tecnico_supervisor,
                data_atendimento
            ) VALUES (
                :equipamento_id,
                :criado_por,
                :status,
                :tipo_chamado,
                :prioridade,
                :tecnico_id,
                :tecnico,
                :assunto,
                :descricao,
                :nome_usuario,
                :email_usuario,
                :ddd_usuario,
                :telefone_usuario,
                :tecnico_supervisor,
                :data_atendimento
            )";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(':equipamento_id', $chamado->getEquipamentoId(), PDO::PARAM_INT);
        $stmt->bindValue(':criado_por', $chamado->getCriadoPor());
        $stmt->bindValue(':status', $chamado->getStatus());
        $stmt->bindValue(':tipo_chamado', $chamado->getTipoChamado());
        $stmt->bindValue(':prioridade', $chamado->getPrioridade());
        $stmt->bindValue(':tecnico_id', $chamado->getTecnicoId(), PDO::PARAM_INT);
        $stmt->bindValue(':tecnico', $chamado->getTecnico());
        $stmt->bindValue(':assunto', $chamado->getAssunto());
        $stmt->bindValue(':descricao', $chamado->getDescricao());
        $stmt->bindValue(':nome_usuario', $chamado->getNomeUsuario());
        $stmt->bindValue(':email_usuario', $chamado->getEmailUsuario());
        $stmt->bindValue(':ddd_usuario', $chamado->getDddUsuario());
        $stmt->bindValue(':telefone_usuario', $chamado->getTelefoneUsuario());
        $stmt->bindValue(':tecnico_supervisor', $chamado->getTecnicoSupervisor());
        $stmt->bindValue(':data_atendimento', $chamado->getDataAtendimento() ?: null);

        $executed = $stmt->execute();

        if ($executed) {
            return (int) $this->conn->lastInsertId();
        }

        return 0;
    }

    public function listarTudo()
    {
        $sql = "SELECT 
                c.*,
                COALESCE(f.solucao, c.solucao) AS solucao,
                COALESCE(f.pdf_path, c.pdf_path) AS pdf_path,
                COALESCE(f.data_finalizacao, c.data_finalizacao) AS data_finalizacao,
                e.numero_serie,
                e.numero_patrimonio,
                e.descricao AS descricao_equipamento,
                e.equipamento,
                e.unidade,
                e.local,
                e.cidade,
                e.uf,
                t.nome AS tecnico_nome
            FROM chamados c
            INNER JOIN equipamentos e ON e.id = c.equipamento_id
            LEFT JOIN tecnicos t ON t.id = c.tecnico_id
            LEFT JOIN chamado_finalizacoes f ON f.chamado_id = c.id
            ORDER BY c.id DESC";

        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
    public function findById($id)
    {
        $sql = "SELECT 
                c.*,
                COALESCE(f.solucao, c.solucao) AS solucao,
                COALESCE(f.pdf_path, c.pdf_path) AS pdf_path,
                COALESCE(f.data_finalizacao, c.data_finalizacao) AS data_finalizacao,
                e.numero_serie,
                e.numero_patrimonio,
                e.descricao AS descricao_equipamento,
                e.equipamento,
                e.unidade,
                e.local,
                e.cidade,
                e.uf,
                t.nome AS tecnico_nome
            FROM chamados c
            INNER JOIN equipamentos e ON e.id = c.equipamento_id
            LEFT JOIN tecnicos t ON t.id = c.tecnico_id
            LEFT JOIN chamado_finalizacoes f ON f.chamado_id = c.id
            WHERE c.id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $status, $solucao = null, $pdfPath = null)
    {
        if ($status === 'FINALIZADO') {
            $sql = "UPDATE chamados
                    SET status = :status
                    WHERE id = :id";
        } else {
            $sql = "UPDATE chamados
                    SET status = :status
                    WHERE id = :id";
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':status', $status);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function updatePdfPath($id, $pdfPath)
    {
        $sql = "UPDATE chamados SET pdf_path = :pdf_path WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':pdf_path', $pdfPath);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function delete($id)
    {
        $sql = "DELETE FROM chamados WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function filtrar($status = null, $numeroSerie = null, $chamadoId = null)
    {
        $sql = "SELECT 
                c.*,
                COALESCE(f.solucao, c.solucao) AS solucao,
                COALESCE(f.pdf_path, c.pdf_path) AS pdf_path,
                COALESCE(f.data_finalizacao, c.data_finalizacao) AS data_finalizacao,
                e.numero_serie,
                e.numero_patrimonio,
                e.descricao AS descricao_equipamento,
                e.equipamento,
                e.unidade,
                e.local,
                e.cidade,
                e.uf
            FROM chamados c
            INNER JOIN equipamentos e ON e.id = c.equipamento_id
            LEFT JOIN chamado_finalizacoes f ON f.chamado_id = c.id
            WHERE 1=1";

        $params = [];

        if (!empty($status)) {
            $sql .= " AND c.status = :status";
            $params[':status'] = $status;
        }

        if (!empty($chamadoId)) {
            $sql .= " AND c.id = :chamado_id";
            $params[':chamado_id'] = $chamadoId;
        }

        if (!empty($numeroSerie)) {
            $sql .= " AND e.numero_serie LIKE :numero_serie";
            $params[':numero_serie'] = "%{$numeroSerie}%";
        }

        $sql .= " ORDER BY c.id DESC";

        $stmt = $this->conn->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function filtrarPorTecnico($tecnicoId)
    {
        $sql = "SELECT 
                c.*,
                COALESCE(f.solucao, c.solucao) AS solucao,
                COALESCE(f.pdf_path, c.pdf_path) AS pdf_path,
                COALESCE(f.data_finalizacao, c.data_finalizacao) AS data_finalizacao,
                e.numero_serie,
                e.numero_patrimonio,
                e.descricao AS descricao_equipamento,
                e.equipamento,
                e.unidade,
                e.local,
                e.cidade,
                e.uf,
                t.nome AS tecnico_nome
            FROM chamados c
            INNER JOIN equipamentos e ON e.id = c.equipamento_id
            LEFT JOIN tecnicos t ON t.id = c.tecnico_id
            LEFT JOIN chamado_finalizacoes f ON f.chamado_id = c.id
            WHERE c.tecnico_id = :tecnico_id
            ORDER BY c.id DESC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':tecnico_id', $tecnicoId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function listarHistorico()
    {
        $sql = "SELECT 
                c.*,
                COALESCE(f.solucao, c.solucao) AS solucao,
                COALESCE(f.pdf_path, c.pdf_path) AS pdf_path,
                COALESCE(f.data_finalizacao, c.data_finalizacao) AS data_finalizacao,
                e.numero_serie,
                e.numero_patrimonio,
                e.descricao AS descricao_equipamento,
                e.equipamento,
                e.unidade,
                e.local,
                e.cidade,
                e.uf,
                t.nome AS tecnico_nome
            FROM chamados c
            INNER JOIN equipamentos e ON e.id = c.equipamento_id
            LEFT JOIN tecnicos t ON t.id = c.tecnico_id
            LEFT JOIN chamado_finalizacoes f ON f.chamado_id = c.id
            ORDER BY f.data_finalizacao DESC, c.id DESC";

        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function filtrarHistorico($tecnicoId = null, $numeroSerie = null, $chamadoId = null, $status = null)
    {
        $sql = "SELECT 
                c.*,
                COALESCE(f.solucao, c.solucao) AS solucao,
                COALESCE(f.pdf_path, c.pdf_path) AS pdf_path,
                COALESCE(f.data_finalizacao, c.data_finalizacao) AS data_finalizacao,
                e.numero_serie,
                e.numero_patrimonio,
                e.descricao AS descricao_equipamento,
                e.equipamento,
                e.unidade,
                e.local,
                e.cidade,
                e.uf,
                t.nome AS tecnico_nome
            FROM chamados c
            INNER JOIN equipamentos e ON e.id = c.equipamento_id
            LEFT JOIN tecnicos t ON t.id = c.tecnico_id
            LEFT JOIN chamado_finalizacoes f ON f.chamado_id = c.id
            WHERE 1=1";

        $params = [];

        if (!empty($tecnicoId)) {
            $sql .= " AND c.tecnico_id = :tecnico_id";
            $params[':tecnico_id'] = $tecnicoId;
        }

        if (!empty($status)) {
            $sql .= " AND c.status = :status";
            $params[':status'] = $status;
        }

        if (!empty($chamadoId)) {
            $sql .= " AND c.id = :chamado_id";
            $params[':chamado_id'] = $chamadoId;
        }

        if (!empty($numeroSerie)) {
            $sql .= " AND e.numero_serie LIKE :numero_serie";
            $params[':numero_serie'] = "%{$numeroSerie}%";
        }

        $sql .= " ORDER BY f.data_finalizacao DESC, c.id DESC";

        $stmt = $this->conn->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
