# 🧾 Documento de Requisitos do Sistema

## 🏷️ Nome do Projeto
**Sistema de Agendamento de Consultas**

## UC ##
**metodos, modelos e técnicas para engenharia de software**

---

## 📘 1. Introdução

### 1.1 Propósito
Este documento tem como objetivo descrever de forma clara e detalhada os **requisitos funcionais e não funcionais** do sistema de agendamento de consultas.  
O documento serve como base para o desenvolvimento, validação e manutenção do sistema.

### 1.2 Escopo
O sistema permitirá o **cadastro de pacientes e médicos**, **agendamento de consultas** e **gerenciamento de horários**.  
Seu principal objetivo é automatizar o processo de marcação de consultas, reduzindo erros manuais e otimizando o atendimento.

### 1.3 Definições, Acrônimos e Abreviações
| Termo | Definição |
|-------|------------|
| CRUD | Operações básicas de Create, Read, Update e Delete |
| CPF | Cadastro de Pessoa Física |
| UI | Interface do Usuário |
| DB | Banco de Dados |

---

## ⚙️ 2. Descrição Geral

### 2.1 Perspectiva do Produto
O sistema será uma aplicação web desenvolvida utilizando **PHP** para o backend e **MySQL** como banco de dados.  
Na camada de interface, serão aplicadas as tecnologias **HTML**, **CSS** e **Bootstrap**, garantindo um design responsivo, moderno e de fácil navegação.  

O foco do produto está em oferecer uma plataforma simples e eficiente para o agendamento de consultas médicas, proporcionando uma melhor experiência tanto para o paciente quanto para o administrador do sistema.  
Além disso, o sistema será estruturado de forma modular, facilitando futuras manutenções e expansões.


### 2.2 Funcionalidades Principais
- Cadastro de pacientes e médicos  
- Login com CPF ou CRM e senha  
- Agendamento e cancelamento de consultas  
- Visualização de consultas agendadas  
- Painel administrativo para gerenciamento

### 2.3 Restrições
- O sistema deve rodar em ambiente local ou web com PHP ≥ 8.0  
- O banco de dados deve ser compatível com MySQL  
- Cada CPF deve ser único no sistema  

### 2.4 Suposições e Dependências
- Usuário possui conexão com a internet  
- O servidor PHP está corretamente configurado  
- O sistema será acessado via navegador compatível (Chrome, Edge ou Firefox)

---

## 🧩 3. Requisitos Funcionais

| Código | Requisito | Descrição | Prioridade |
|--------|------------|------------|-------------|
| RF001 | Cadastro de Pacientes | O sistema deve permitir o cadastro de pacientes com nome, CPF, telefone e senha. | Alta |
| RF002 | Login | O paciente deve conseguir acessar o sistema utilizando CPF e senha. | Alta |
| RF003 | Agendamento de Consulta | O paciente deve poder selecionar data e horário para agendar uma consulta. | Alta |
| RF004 | Listagem de Consultas | O sistema deve exibir as consultas agendadas pelo paciente. | Média |
| RF005 | Cancelamento de Consulta | O paciente poderá cancelar consultas agendadas. | Média |
| RF006 | Cadastro de Médicos | O administrador pode cadastrar, editar e remover médicos. | Alta |
| RF007 | Gerenciamento de Usuários | O administrador pode visualizar e gerenciar pacientes. | Média |

---

## 🧱 4. Requisitos Não Funcionais

| Código | Requisito | Descrição | Prioridade |
|--------|------------|------------|-------------|
| RNF001 | Usabilidade | O sistema deve ter interface simples e intuitiva. | Alta |
| RNF002 | Segurança | As senhas devem ser armazenadas de forma criptografada. | Alta |
| RNF003 | Desempenho | As páginas devem carregar em até 3 segundos. | Média |
| RNF004 | Compatibilidade | Deve funcionar em navegadores modernos. | Alta |
| RNF005 | Manutenibilidade | O código deve seguir boas práticas de organização e comentários. | Média |

---

## 🗄️ 5. Modelo de Dados (Visão Geral)
### Principais Tabelas:
**pacientes**
- id (PK, autoincrement, unsigned)
- nome (varchar)
- cpf (varchar, unique)
- telefone (varchar)
- senha (varchar)

**consultas**
- id (PK)
- paciente_id (FK)
- medico_id (FK)
- status (Enum)
- data_consulta (date)
- hora_consulta (time)
- observacao (text)

**medicos**
- id (PK)
- nome (varchar)
- crm (varchar, unique)
- especialidade (varchar)
- senha (vachar)

---

## 💻 6. Requisitos de Interface

### 6.1 Interface do Usuário
- Tela de login simples com campos de CPF e senha  
- Menu principal com opções: **Agendar**, **Minhas Consultas** e **Sair**  
- Tela administrativa com listagem de pacientes e médicos  

### 6.2 Interface com o Sistema
- Conexão com o banco via `mysqli` ou `PDO`  
- Estrutura MVC (Model-View-Controller) para organização do código  

---

## 🧪 7. Requisitos de Teste

| Código | Tipo de Teste | Descrição | Resultado Esperado |
|--------|----------------|-----------|--------------------|
| T001 | Teste de Login | Validar login com CPF e senha corretos. | Acesso permitido. |
| T002 | Teste de Login | Tentar logar com CPF inexistente. | Acesso negado. |
| T003 | Teste de Agendamento | Agendar consulta com horário válido. | Consulta registrada. |
| T004 | Teste de Duplicidade | Tentar cadastrar CPF já existente. | Erro exibido. |

---

## 🔐 8. Requisitos de Segurança
- As senhas dos usuários devem ser criptografadas com `password_hash()`.  
- Deve haver validação no backend contra SQL Injection.  
- Sessões devem expirar após período de inatividade.  

---

## 📅 9. Cronograma (Exemplo)
| Etapa | Descrição | Período |
|-------|------------|----------|
| Análise de Requisitos | Levantamento e validação dos requisitos | Semana 1 |
| Modelagem | Diagrama de classes, caso de uso e BD | Semana 2 |
| Implementação | Desenvolvimento das funcionalidades | Semanas 3–5 |
| Testes | Testes unitários e de aceitação | Semana 6 |
| Entrega Final | Apresentação do projeto | Semana 7 |

---

## 👨‍💻 10. Autores
**João Pedro Firmo RA: 1362316447** <br>
**Tiago Anderson Fernandes RA: 1362314424** <br>
**Wlisses Gabriel RA: 1362317904**  
Estudantes de Ciência da Computação – 6º período  
📧 Email: joaopedrofirmolira35@gmail.com <br>
🐙 GitHub: [github.com/jpfirmo](https://github.com/jpfirmo)

## 🎓 11. Professores  
**Professor:** Pablo Ramon <br>
**Professor:** Juan Apolinário  

---

📘 *Documento elaborado para fins acadêmicos, com base em práticas de Engenharia de Requisitos.*
