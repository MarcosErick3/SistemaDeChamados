# Sistema de Chamados - Marcos Erick

Sistema web para gerenciamento de chamados técnicos, desenvolvido em PHP com arquitetura MVC e implementação dos padrões de projeto Command, Builder e Factory Method.

## 🎯 Funcionalidades

- ✅ **CRUD Completo de Chamados**: Criar, listar, atualizar e excluir chamados
- ✅ **Sistema de Autenticação**: Login de técnicos
- ✅ **Controle de Status**: ABERTO → EM ANDAMENTO → FINALIZADO
- ✅ **Geração de PDFs**: Relatórios automáticos dos chamados
- ✅ **Interface Responsiva**: Design moderno e intuitivo

## 🏗️ Arquitetura e Padrões de Projeto

### **Command Pattern**
- **Interface**: `src/Commands/Command.php`
- **Implementações**: `CriarChamadoCommand`, `FinalizarChamadoCommand`, `AtualizarStatusCommand`, `DeletarChamadoCommand`
- **Factory**: `CommandFactory` para criação centralizada
- **Benefício**: Separação entre solicitação e execução, facilitando testes e manutenção

### **Builder Pattern**
- **Classe**: `src/Builders/ChamadoBuilder.php`
- **Uso**: Construção fluida de objetos `Chamado` com múltiplos campos
- **Benefício**: Evita construtores com muitos parâmetros, melhora legibilidade

### **Factory Method Pattern**
- **Classe**: `src/Commands/CommandFactory.php`
- **Métodos**: `createCriarChamadoCommand()`, `createFinalizarChamadoCommand()`, etc.
- **Benefício**: Centraliza criação de comandos, facilita extensibilidade

## 📁 Estrutura do Projeto

```
SistemaDeChamados/
├── config/
│   └── Database.php          # Configuração da conexão MySQL
├── src/
│   ├── Builders/
│   │   └── ChamadoBuilder.php    # Builder Pattern
│   ├── Commands/
│   │   ├── Command.php           # Interface Command
│   │   ├── CommandFactory.php    # Factory Pattern
│   │   └── [Commands...].php     # Implementações Command
│   ├── Controllers/
│   │   └── ChamadoController.php # Controla fluxo da aplicação
│   ├── DAO/
│   │   └── ChamadoDAO.php        # Acesso a dados
│   ├── Models/
│   │   └── Chamado.php           # Modelo de dados
│   ├── Services/
│   │   └── ChamadoService.php    # Regras de negócio
│   └── views/
│       └── chamados/
│           ├── index.php
│           ├── listar.php
│           └── detalhes.php
├── public/
│   ├── index.php             # Ponto de entrada da aplicação
│   ├── css/
│   │   └── index.css         # Estilos da interface
│   └── pdfs/                 # PDFs gerados
└── vendor/                   # Dependências (Composer)
```

## 🚀 Fluxo Completo do Sistema

### 1. **Criação de Chamado**
```
Usuário → Formulário → ChamadoController::salvar()
    ↓
ChamadoBuilder (constrói objeto Chamado)
    ↓
CommandFactory::createCriarChamadoCommand()
    ↓
CriarChamadoCommand::execute()
    ↓
ChamadoService::criar() → ChamadoDAO::criarChamado()
    ↓
PDF gerado automaticamente
```

### 2. **Atualização de Status**
```
Técnico → Selecionar Status → ChamadoController::atualizar()
    ↓
CommandFactory::createAtualizarStatusCommand()
    ↓
AtualizarStatusCommand::execute()
    ↓
ChamadoService::atualizarStatus() → ChamadoDAO::updateStatus()
```

### 3. **Finalização de Chamado**
```
Técnico → Formulário Finalização → ChamadoController::atualizar()
    ↓
CommandFactory::createFinalizarChamadoCommand()
    ↓
FinalizarChamadoCommand::execute()
    ↓
ChamadoService::finalizar() + PDF upload opcional
```

## 🔒 Segurança Implementada

- **SQL Injection Protection**: Prepared statements em todos os DAOs
- **XSS Protection**: `htmlspecialchars()` em todas as views
- **Password Security**: `password_hash()` e `password_verify()`
- **Session Management**: Controle adequado de sessões
- **Input Validation**: Validação de entrada nos controllers

## 🛠️ Tecnologias Utilizadas

- **Backend**: PHP 7.4+
- **Banco**: MySQL/MariaDB
- **Frontend**: HTML5, CSS3, JavaScript
- **Arquitetura**: MVC com Padrões de Projeto
- **PDF Generation**: Biblioteca nativa (sem dependências externas)

## 📋 Pré-requisitos

- PHP 7.4 ou superior
- MySQL/MariaDB
- Servidor web (Apache/Nginx) ou Laragon/XAMPP

## 🚀 Instalação e Execução

1. **Clonar repositório**:
   ```bash
   git clone https://github.com/MarcosErick3/SistemaDeChamados.git
   cd SistemaDeChamados
   ```

2. **Configurar banco de dados**:
   - Criar banco `servicedesk`
   - Importar tabelas (o sistema cria automaticamente via migrations)

3. **Configurar ambiente**:
   - Ajustar credenciais em `config/Database.php`
   - Garantir permissões de escrita em `public/pdfs/` e `public/uploads/`

4. **Executar aplicação**:
   - Acessar `http://localhost/SistemaDeChamados/public/`
   - **Credenciais de acesso**:
     - Email: `admin@sistema.com`
     - Senha: `admin123`
   - Primeiro acesso: login com as credenciais acima

## 🎯 Destaques para Apresentação

### **Padrões de Projeto em Ação**
- **Command**: Cada ação do sistema é um comando independente
- **Builder**: Construção fluida de objetos complexos
- **Factory**: Criação centralizada de comandos

### **Benefícios dos Padrões**
- ✅ **Manutenibilidade**: Código organizado e fácil de modificar
- ✅ **Testabilidade**: Separação clara de responsabilidades
- ✅ **Extensibilidade**: Novos comandos/funcionalidades sem alterar código existente
- ✅ **Legibilidade**: Código expressivo e autodocumentado

### **Fluxo Demonstrável**
1. Criar chamado → Builder constrói objeto
2. Atualizar status → Command executa ação
3. Finalizar chamado → Factory cria comando apropriado
4. Gerar PDF → Serviço independente

## 📞 Contato

**Desenvolvido por**: Marcos Erick
**Propósito**: Projeto acadêmico demonstrando aplicação prática de padrões de projeto em PHP

---

*Sistema desenvolvido com foco em boas práticas de desenvolvimento e arquitetura de software.*

