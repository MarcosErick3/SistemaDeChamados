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

-- Copiando dados para a tabela servicedesk.chamados: ~2 rows (aproximadamente)
INSERT INTO `chamados` (`id`, `equipamento_id`, `criado_por`, `status`, `tipo_chamado`, `prioridade`, `tecnico`, `assunto`, `descricao`, `nome_usuario`, `email_usuario`, `ddd_usuario`, `telefone_usuario`, `tecnico_supervisor`, `diagnostico`, `data_atendimento`, `data_abertura`, `data_finalizacao`, `tecnico_id`, `solucao`, `pdf_path`) VALUES
	(1, 3, 'Marcos Erick', 'ABERTO', 'Hardware', 'Alta', 'Ana Paula Ramos', 'Impressora nao reconhece papel A4', 'A impressora do faturamento puxa duas folhas e gera atolamento logo no inicio da impressao.', 'Paulo Henrique', 'paulo.henrique@empresa.com', '11', '998877661', 'Rafael Gomes', 'Possivel desgaste no conjunto de tracao', '2026-04-16', '2026-04-16 08:15:00', NULL, 2, NULL, NULL),
	(2, 2, 'Marcos Erick', 'EM ANDAMENTO', 'Software', 'Media', 'Bruno Costa', 'Outlook fecha ao anexar arquivos', 'Usuario relata fechamento inesperado do Outlook sempre que tenta anexar PDFs acima de 5 MB.', 'Fernanda Lima', 'fernanda.lima@empresa.com', '11', '997766551', 'Rafael Gomes', 'Add-in legado em investigacao', '2026-04-16', '2026-04-15 10:20:00', NULL, 3, NULL, NULL),
	(3, 1, 'Marcos Erick', 'FINALIZADO', 'Hardware', 'Alta', 'Carla Mendes', 'Desktop nao liga apos queda de energia', 'Equipamento permaneceu sem video e sem resposta apos oscilacao eletrica no setor administrativo.', 'Juliana Prado', 'juliana.prado@empresa.com', '11', '996655441', 'Renato Alves', 'Fonte com tensao irregular', '2026-04-15', '2026-04-14 09:00:00', '2026-04-15 15:40:00', 4, 'Fonte substituida e BIOS reconfigurada. Equipamento testado e liberado.', NULL),
	(4, 4, 'Marcos Erick', 'ABERTO', 'Rede', 'Alta', 'Ana Paula Ramos', 'Oscilacao no link do setor comercial', 'Usuarios do setor comercial perdem conexao com o ERP por alguns minutos ao longo do dia.', 'Ricardo Nunes', 'ricardo.nunes@empresa.com', '11', '995544331', 'Rafael Gomes', 'Verificar estabilidade do switch e uplink principal', '2026-04-17', '2026-04-16 11:05:00', NULL, 2, NULL, NULL),
	(5, 5, 'Marcos Erick', 'FINALIZADO', 'Hardware', 'Baixa', 'Marcos Erick', 'Monitor com brilho intermitente', 'Monitor pisca em intervalos curtos e dificulta leitura de planilhas no RH.', 'Camila Rocha', 'camila.rocha@empresa.com', '11', '994433221', 'Renato Alves', 'Cabo de energia com mau contato', '2026-04-13', '2026-04-13 08:40:00', '2026-04-13 12:10:00', 1, 'Cabo de energia substituido e brilho recalibrado. Monitor validado com a usuaria.', NULL);

-- Copiando dados para a tabela servicedesk.chamado_comentarios: ~0 rows (aproximadamente)
INSERT INTO `chamado_comentarios` (`id`, `chamado_id`, `usuario_id`, `usuario_nome`, `comentario`, `data_criacao`) VALUES
	(1, 2, 3, 'Bruno Costa', 'Atualizei o perfil do Outlook e estou validando com a usuaria.', '2026-04-16 12:30:00'),
	(2, 3, 4, 'Carla Mendes', 'Equipamento entregue ao solicitante e funcionamento confirmado.', '2026-04-15 18:55:00'),
	(3, 4, 2, 'Ana Paula Ramos', 'Coleta de logs do switch agendada para o inicio da tarde.', '2026-04-16 14:40:00');

-- Copiando dados para a tabela servicedesk.chamado_finalizacoes: ~0 rows (aproximadamente)
INSERT INTO `chamado_finalizacoes` (`id`, `chamado_id`, `solucao`, `pdf_path`, `data_finalizacao`) VALUES
	(1, 3, 'Fonte substituida e BIOS reconfigurada. Equipamento testado e liberado.', NULL, '2026-04-15 15:40:00'),
	(2, 5, 'Cabo de energia substituido e brilho recalibrado. Monitor validado com a usuaria.', NULL, '2026-04-13 12:10:00');

-- Copiando dados para a tabela servicedesk.equipamentos: ~5 rows (aproximadamente)
INSERT INTO `equipamentos` (`id`, `numero_serie`, `numero_patrimonio`, `descricao`, `equipamento`, `unidade`, `local`, `cidade`, `uf`) VALUES
	(1, 'SN-2026-101', 'PAT-9001', 'Desktop Dell OptiPlex i5 16GB', 'Desktop', 'Matriz', 'Administrativo', 'Sao Paulo', 'SP'),
	(2, 'SN-2026-102', 'PAT-9002', 'Notebook Lenovo ThinkPad E14', 'Notebook', 'Matriz', 'Financeiro', 'Sao Paulo', 'SP'),
	(3, 'SN-2026-103', 'PAT-9003', 'Impressora HP LaserJet Pro', 'Impressora', 'Filial Norte', 'Faturamento', 'Guarulhos', 'SP'),
	(4, 'SN-2026-104', 'PAT-9004', 'Roteador Mikrotik RB4011', 'Roteador', 'Matriz', 'CPD', 'Sao Paulo', 'SP'),
	(5, 'SN-2026-105', 'PAT-9005', 'Monitor LG 24 polegadas', 'Monitor', 'Filial Sul', 'RH', 'Santo Andre', 'SP');

-- Copiando dados para a tabela servicedesk.tecnicos: ~4 rows (aproximadamente)
INSERT INTO `tecnicos` (`id`, `nome`, `email`, `telefone`, `senha`) VALUES
	(1, 'Marcos Erick', 'admin@sistema.com', '11999990001', '$2y$10$RxfulX0yiHEYNxfq4jKkX.HMy5huHD9obSCMAaAB.49ajLquabNz.'),
	(2, 'Ana Paula Ramos', 'ana.ramos@empresa.com', '11999990002', '$2y$10$RxfulX0yiHEYNxfq4jKkX.HMy5huHD9obSCMAaAB.49ajLquabNz.'),
	(3, 'Bruno Costa', 'bruno.costa@empresa.com', '11999990003', '$2y$10$RxfulX0yiHEYNxfq4jKkX.HMy5huHD9obSCMAaAB.49ajLquabNz.'),
	(4, 'Carla Mendes', 'carla.mendes@empresa.com', '11999990004', '$2y$10$RxfulX0yiHEYNxfq4jKkX.HMy5huHD9obSCMAaAB.49ajLquabNz.');


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
  `tecnico_supervisor` varchar(100) DEFAULT NULL,
  `diagnostico` varchar(255) DEFAULT NULL,
  `data_atendimento` date DEFAULT NULL,
  `data_abertura` datetime DEFAULT CURRENT_TIMESTAMP,
  `data_finalizacao` datetime DEFAULT NULL,
  `tecnico_id` int DEFAULT NULL,
  `solucao` text,
  `pdf_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `equipamento_id` (`equipamento_id`),
  KEY `chamados_tecnico_fk` (`tecnico_id`),
  CONSTRAINT `chamados_ibfk_1` FOREIGN KEY (`equipamento_id`) REFERENCES `equipamentos` (`id`),
  CONSTRAINT `chamados_tecnico_fk` FOREIGN KEY (`tecnico_id`) REFERENCES `tecnicos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela servicedesk.chamado_comentarios
CREATE TABLE IF NOT EXISTS `chamado_comentarios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `chamado_id` int NOT NULL,
  `usuario_id` int NOT NULL,
  `usuario_nome` varchar(255) NOT NULL,
  `comentario` text NOT NULL,
  `data_criacao` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `chamado_id` (`chamado_id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `chamado_comentarios_ibfk_1` FOREIGN KEY (`chamado_id`) REFERENCES `chamados` (`id`) ON DELETE CASCADE,
  CONSTRAINT `chamado_comentarios_ibfk_2` FOREIGN KEY (`usuario_id`) REFERENCES `tecnicos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Exportação de dados foi desmarcado.

-- Copiando estrutura para tabela servicedesk.chamado_finalizacoes
CREATE TABLE IF NOT EXISTS `chamado_finalizacoes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `chamado_id` int NOT NULL,
  `solucao` text,
  `pdf_path` varchar(255) DEFAULT NULL,
  `data_finalizacao` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `chamado_id` (`chamado_id`),
  KEY `idx_chamado_id` (`chamado_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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

-- Copiando estrutura para tabela servicedesk.tecnicos
CREATE TABLE IF NOT EXISTS `tecnicos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `telefone` varchar(30) DEFAULT NULL,
  `senha` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
