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
            'solucao' => 'TEXT NULL'
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
    }

    public function create(Chamado $chamado)
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
                data_atendimento,
                solucao
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
                :data_atendimento,
                :solucao
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
        $stmt->bindValue(':solucao', $chamado->getSolucao());

        $executed = $stmt->execute();

        if ($executed) {
            return (int) $this->conn->lastInsertId();
        }

        return 0;
    }

    public function findAll()
    {
        $sql = "SELECT 
                c.*,
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
            ORDER BY c.id DESC";

        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
    public function findById($id)
    {
        $sql = "SELECT * FROM chamados WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $status, $solucao = null)
    {
        if ($status === 'FINALIZADO') {
            $sql = "UPDATE chamados
                    SET status = :status,
                        solucao = :solucao,
                        data_finalizacao = NOW()
                    WHERE id = :id";
        } else {
            $sql = "UPDATE chamados
                    SET status = :status
                    WHERE id = :id";
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':status', $status);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        if ($status === 'FINALIZADO') {
            $stmt->bindValue(':solucao', $solucao);
        }

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
}
