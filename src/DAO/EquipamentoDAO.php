<?php

class EquipamentoDAO
{
    private $conn;

    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function buscarPorNumeroSerie($numeroSerie)
    {
        $sql = "SELECT * FROM equipamentos WHERE numero_serie = :numero_serie LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':numero_serie', $numeroSerie);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function listarTodos()
    {
        $sql = "SELECT * FROM equipamentos ORDER BY id ASC";
        $stmt = $this->conn->query($sql);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}