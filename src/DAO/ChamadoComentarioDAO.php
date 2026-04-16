<?php

require_once __DIR__ . '/../Models/ChamadoComentario.php';

class ChamadoComentarioDAO
{
    private $conn;

    public function __construct($conn)
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
            ':table' => 'chamado_comentarios'
        ]);

        if ((int) $stmt->fetchColumn() === 0) {
            $sql = "CREATE TABLE chamado_comentarios (
                id INT AUTO_INCREMENT PRIMARY KEY,
                chamado_id INT NOT NULL,
                usuario_id INT DEFAULT NULL,
                usuario_nome VARCHAR(100) NOT NULL,
                comentario TEXT NOT NULL,
                data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_chamado_id (chamado_id),
                INDEX idx_usuario_id (usuario_id)
            )";
            $this->conn->exec($sql);
        }
    }

    public function criar(ChamadoComentario $comentario)
    {
        $sql = "INSERT INTO chamado_comentarios (
                chamado_id,
                usuario_id,
                usuario_nome,
                comentario,
                data_criacao
            ) VALUES (
                :chamado_id,
                :usuario_id,
                :usuario_nome,
                :comentario,
                :data_criacao
            )";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(':chamado_id', $comentario->getChamadoId(), PDO::PARAM_INT);
        $usuarioId = $comentario->getUsuarioId();
        $stmt->bindValue(':usuario_id', $usuarioId, $usuarioId === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
        $stmt->bindValue(':usuario_nome', $comentario->getUsuarioNome());
        $stmt->bindValue(':comentario', $comentario->getComentario());
        $stmt->bindValue(':data_criacao', $comentario->getDataCriacao());

        $executed = $stmt->execute();

        if ($executed) {
            return (int) $this->conn->lastInsertId();
        }

        return 0;
    }

    public function listarPorChamado($chamadoId)
    {
        $sql = "SELECT * FROM chamado_comentarios
                WHERE chamado_id = :chamado_id
                ORDER BY data_criacao ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':chamado_id', $chamadoId, PDO::PARAM_INT);
        $stmt->execute();

        $comentarios = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $comentario = new ChamadoComentario();
            $comentario->setId($row['id']);
            $comentario->setChamadoId($row['chamado_id']);
            $comentario->setUsuarioId($row['usuario_id']);
            $comentario->setUsuarioNome($row['usuario_nome']);
            $comentario->setComentario($row['comentario']);
            $comentario->setDataCriacao($row['data_criacao']);
            $comentarios[] = $comentario;
        }

        return $comentarios;
    }

    public function deletar($id)
    {
        $sql = "DELETE FROM chamado_comentarios WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
