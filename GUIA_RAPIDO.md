# 🚀 Guia Rápido - Sistema de Chamados

## 📋 Credenciais de Acesso

**Usuário Administrador Criado:**
- **Email:** `admin@sistema.com`
- **Senha:** `admin123`
- **Nome:** Marcos Erick

## 🌐 Como Acessar

1. **URL:** `http://localhost/SistemaDeChamados/public/`
2. **Login:** Use as credenciais acima
3. **Primeiro Acesso:** Você será redirecionado para a página de login

## 🎯 Funcionalidades Disponíveis

### **1. Criar Chamado**
- Acesse "Pesquisa" no menu
- Preencha o formulário com os dados do chamado
- Selecione técnico responsável
- Clique em "Salvar"

### **2. Gerenciar Chamados**
- **Meus Chamados:** Lista apenas os chamados do técnico logado
- **Histórico:** Visualizar todos os chamados com filtros
- **Detalhes:** Ver informações completas de um chamado

### **3. Atualizar Status**
- Abra os detalhes de um chamado
- Selecione novo status (ABERTO → EM ANDAMENTO → FINALIZADO)
- Para finalizar: adicione solução e PDF opcional

### **4. Gerar PDFs**
- PDFs são gerados automaticamente na criação
- PDFs adicionais podem ser anexados na finalização

## 🏗️ Padrões de Projeto Demonstrados

### **Command Pattern**
Cada ação é encapsulada em um comando:
- `CriarChamadoCommand`
- `FinalizarChamadoCommand`
- `AtualizarStatusCommand`

### **Builder Pattern**
Construção fluida de objetos complexos:
```php
$chamado = (new ChamadoBuilder())
    ->numeroSerie('ABC123')
    ->equipamento('Notebook')
    ->build();
```

### **Factory Method Pattern**
Criação centralizada de comandos:
```php
$command = CommandFactory::createCriarChamadoCommand($service, $chamado, $pdfService);
```

## 🔧 Estrutura do Banco

O sistema cria automaticamente as tabelas necessárias:
- `tecnicos` - Usuários do sistema
- `chamados` - Chamados técnicos
- `chamado_finalizacoes` - Histórico de finalizações

## 📞 Suporte

**Desenvolvido por:** Marcos Erick
**Para apresentação:** Sistema demonstra aplicação prática de padrões de projeto

---

**Status:** ✅ Sistema totalmente funcional e pronto para uso!