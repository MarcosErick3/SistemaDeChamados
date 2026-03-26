-- --------------------------------------------------------
-- Servidor:                     127.0.0.1
-- Versão do servidor:           8.4.3 - MySQL Community Server - GPL
-- OS do Servidor:               Win64
-- HeidiSQL Versão:              12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Copiando estrutura do banco de dados para servicedesk
CREATE DATABASE IF NOT EXISTS `servicedesk` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `servicedesk`;

-- Copiando estrutura para tabela servicedesk.chamados
CREATE TABLE IF NOT EXISTS `chamados` (
  `id` int NOT NULL AUTO_INCREMENT,
  `equipamento_id` int NOT NULL,
  `criado_por` varchar(100) DEFAULT NULL,
  `status` enum('ABERTO','EM ANDAMENTO','FINALIZADO') NOT NULL DEFAULT 'ABERTO',
  `tipo_chamado` varchar(100) DEFAULT NULL,
  `prioridade` varchar(50) DEFAULT NULL,
  `tecnico` varchar(100) DEFAULT NULL,
  `assunto` varchar(255) NOT NULL,
  `descricao` text NOT NULL,
  `nome_usuario` varchar(100) DEFAULT NULL,
  `email_usuario` varchar(150) DEFAULT NULL,
  `ddd_usuario` varchar(5) DEFAULT NULL,
  `telefone_usuario` varchar(20) DEFAULT NULL,
  `grupo_atendimento` varchar(100) DEFAULT NULL,
  `tecnico_supervisor` varchar(100) DEFAULT NULL,
  `diagnostico` varchar(255) DEFAULT NULL,
  `data_atendimento` date DEFAULT NULL,
  `solucao` text,
  `data_abertura` datetime DEFAULT CURRENT_TIMESTAMP,
  `data_finalizacao` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `equipamento_id` (`equipamento_id`),
  CONSTRAINT `chamados_ibfk_1` FOREIGN KEY (`equipamento_id`) REFERENCES `equipamentos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela servicedesk.equipamentos
CREATE TABLE IF NOT EXISTS `equipamentos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `numero_serie` varchar(100) NOT NULL,
  `numero_patrimonio` varchar(100) NOT NULL,
  `descricao` varchar(255) NOT NULL,
  `equipamento` varchar(100) NOT NULL,
  `unidade` varchar(100) NOT NULL,
  `local` varchar(100) NOT NULL,
  `cidade` varchar(100) NOT NULL,
  `uf` varchar(2) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `numero_serie` (`numero_serie`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Exportação de dados foi desmarcado.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
