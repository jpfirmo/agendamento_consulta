# 🧾 Documento de Requisitos do Sistema

## 🏷️ Nome do Projeto

**Sistema de Agendamento de Consultas**

## UC

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

| Termo | Definição                                          |
| ----- | -------------------------------------------------- |
| CRUD  | Operações básicas de Create, Read, Update e Delete |
| CPF   | Cadastro de Pessoa Física                          |
| UI    | Interface do Usuário                               |
| DB    | Banco de Dados                                     |

---

## ⚙️ 2. Descrição Geral

### 2.1 Perspectiva do Produto

O sistema será uma aplicação web desenvolvida utilizando **PHP** para o backend e **heidiSQL** como banco de dados.
Na camada de interface, serão aplicadas as tecnologias **HTML**, **CSS** e **Bootstrap**, garantindo um design responsivo, moderno e de fácil navegação.

O foco do produto está em oferecer uma plataforma simples e eficiente para o agendamento de consultas médicas, proporcionando uma melhor experiência tanto para o paciente quanto para o administrador do sistema.
Além disso, o sistema será estruturado de forma modular, facilitando futuras manutenções e expansões.

### 2.2 Funcionalidades Principais

* Cadastro de pacientes e médicos
* Login com CPF ou CRM e senha
* Agendamento e cancelamento de consultas
* Visualização de consultas agendadas
* Painel administrativo para gerenciamento

### 2.3 Restrições

* O sistema deve rodar em ambiente local ou web com PHP ≥ 8.0
* O banco de dados deve ser compatível com MySQL
* Cada CPF deve ser único no sistema

### 2.4 Suposições e Dependências

* Usuário possui conexão com a internet
* O servidor PHP está corretamente configurado
* O sistema será acessado via navegador compatível (Chrome, Edge ou Firefox)

---

## 🧩 3. Requisitos Funcionais

| Código | Requisito                 | Descrição                                                                        | Prioridade |
| ------ | ------------------------- | -------------------------------------------------------------------------------- | ---------- |
| RF001  | Cadastro de Pacientes     | O sistema deve permitir o cadastro de pacientes com nome, CPF, telefone e senha. | Alta       |
| RF002  | Login                     | O paciente deve conseguir acessar o sistema utilizando CPF e senha.              | Alta       |
| RF003  | Agendamento de Consulta   | O paciente deve poder selecionar data e horário para agendar uma consulta.       | Alta       |
| RF004  | Listagem de Consultas     | O sistema deve exibir as consultas agendadas pelo paciente.                      | Média      |
| RF005  | Cancelamento de Consulta  | O paciente poderá cancelar consultas agendadas.                                  | Média      |
| RF006  | Cadastro de Médicos       | O administrador pode cadastrar, editar e remover médicos.                        | Alta       |


---

## 🧱 4. Requisitos Não Funcionais

| Código | Requisito        | Descrição                                                        | Prioridade |
| ------ | ---------------- | ---------------------------------------------------------------- | ---------- |
| RNF001 | Usabilidade      | O sistema deve ter interface simples e intuitiva.                | Alta       |
| RNF002 | Segurança        | As senhas devem ser armazenadas de forma criptografada.          | Alta       |
| RNF003 | Desempenho       | As páginas devem carregar em até 3 segundos.                     | Média      |
| RNF004 | Compatibilidade  | Deve funcionar em navegadores modernos.                          | Alta       |
| RNF005 | Manutenibilidade | O código deve seguir boas práticas de organização e comentários. | Média      |

---

## 🗄️ 5. Modelo de Dados (Visão Geral)

### Principais Tabelas:

**pacientes**

* id (PK, autoincrement, unsigned)
* nome (varchar)
* cpf (varchar, unique)
* telefone (varchar)
* senha (varchar)

**consultas**

* id (PK)
* paciente_id (FK)
* medico_id (FK)
* status (Enum)
* data_hora (datetime)
* observacao (text)
* created_at (timeStamp)
* updateed_at (timeStamp)


**medicos**

* id (PK)
* nome (varchar)
* crm (varchar, unique)
* especialidade (varchar)
* senha (vachar)

---

## 🎯 5.1 Diagrama de Caso de Uso

A seguir, o diagrama de caso de uso do sistema:

![Diagrama de Caso de Uso](/img/agendamento_consulta.png)

---

## 🎯 5.2 histórias de Usuário

* como **paciente**, quero poder marcar consultas, para que eu possa me consultar com médico.

* como **paciente**, quero poder remarcar consultas, para caso eu não possa ir no dia eu posso remarcar para outro dia.

* como **médico**, quero poder ver minha agenda, para que possa saber quantas consultas eu tenho no dia.

---

## 💻 6. Requisitos de Interface

### 6.1 Interface do Usuário

* Tela de login simples com campos de CPF e senha
* Menu principal com opções: **Agendar**, **Minhas Consultas** e **Sair**
* Tela administrativa com listagem de pacientes e médicos

### 6.2 Interface com o Sistema

O sistema realiza comunicação interna através de páginas `PHP` que interagem diretamente com o banco de dados `heidiSQL` utilizando a extensão `PDO`.  
As operações de **cadastro, login, agendamento, cancelamento e remarcação** são processadas pelo backend, que recebe dados enviados por formulários HTML via método **POST**.

Cada funcionalidade possui um arquivo PHP específico responsável por:

- **Receber** os dados enviados do formulário  
- **Validar** os campos necessários  
- **Executar** comandos SQL (`INSERT`, `UPDATE`, `SELECT`, `DELETE`)  
- **Retornar** mensagens ao usuário ou redirecionar para outras páginas  

O sistema utiliza **sessões PHP (`$_SESSION`)** para manter o usuário autenticado e definir permissões diferentes entre **médico** e **paciente**.

A interface visual (`HTML + CSS + BOOTSTRAP`) funciona como a camada de interação do usuário, enquanto o **PHP** desempenha o papel de lógica de processamento e comunicação com o banco, garantindo que cada ação seja executada corretamente no sistema.

---

## 🧪 7. Requisitos de Teste

| Código | Tipo de Teste        | Descrição                               | Resultado Esperado   |
| ------ | -------------------- | --------------------------------------- | -------------------- |
| T001   | Teste Unitário       | Executar suite de teste_login.php | Login autenticado/negado conforme cenário    |
| T002   | Teste Unitário      | Executar suite de teste_cadastro.php       | Cadastros criados e verificados no BD     |
| T003   | Teste de conexão com o banco | estabelecer conexão com o banco de dados    |conexão bem sucedida. |

---

### 🧪 7.1 Testes Unitários Implementados

---

#### ✅ Teste de Login (`teste_login.php`)

O arquivo valida o processo de autenticação de pacientes e médicos.

**Cenários testados:**

- Login correto do paciente  
- Login correto do médico  
- Tentativa de login com senha incorreta  
- Tentativa de login com usuário inexistente  

---

#### ✅ Teste de Cadastro (`teste_cadastro.php`)

Valida a criação de novos pacientes e médicos.

**Cenários testados:**

- Cadastro correto de paciente  
- Verificação se o paciente foi realmente salvo  
- Cadastro correto de médico  
- Verificação do registro recém-cria
---

## 🔐 8. Requisitos de Segurança

* As senhas dos usuários devem ser criptografadas com `password_hash()`.
* Deve haver validação no backend contra SQL Injection.
* Sessões devem expirar após período de inatividade.

---

## 📅 9. Cronograma (Exemplo)

| Etapa                 | Descrição                               | Período     |
| --------------------- | --------------------------------------- | ----------- |
| Análise de Requisitos | Levantamento e validação dos requisitos | Semana 1    |
| Modelagem             | Diagrama de classes, caso de uso e BD   | Semana 2    |
| Implementação         | Desenvolvimento das funcionalidades     | Semanas 3–5 |
| Testes                | Testes unitários e de aceitação         | Semana 6    |
| Entrega Final         | A apresentação do projeto               | Semana 7    |

---

## 👨‍💻 10. Autores

Estudantes de Ciência da Computação – 6º período: <br>
**João Pedro Firmo RA: 1362316447** <br>
**Tiago Anderson Fernandes RA: 1362314424** <br>
**Wlisses Gabriel RA: 1362317904** <br>
**Iarley José da Silva RA: 1362312346** <br>
📧 Email: [joaopedrofirmolira35@gmail.com](mailto:joaopedrofirmolira35@gmail.com) <br>
🐙 GitHub: [github.com/jpfirmo](https://github.com/jpfirmo)

## 🎓 11. Professores

**Professor:** Pablo Ramon <br>
**Professor:** Juan Apolinário

---

📘 *Documento elaborado para fins acadêmicos, com base em práticas de Engenharia de Requisitos.*
