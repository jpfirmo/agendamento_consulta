<?php
use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase
{
    private $conn;

    protected function setUp(): void
    {
        $this->conn = new mysqli("localhost", "root", "", "agenda_teste");
        $this->conn->query("DELETE FROM paciente");
        $this->conn->query("DELETE FROM medico");

        // Criar paciente teste
        $senhaPaciente = password_hash("1234", PASSWORD_DEFAULT);
        $this->conn->query("
            INSERT INTO paciente (nome, cpf, telefone, senha)
            VALUES ('Paciente Teste', '11122233344', '88999990000', '$senhaPaciente')
        ");

        // Criar médico teste
        $senhaMedico = password_hash("5678", PASSWORD_DEFAULT);
        $this->conn->query("
            INSERT INTO medico (nome, crm, especialidade, senha)
            VALUES ('Dr Teste', 'CRM999', 'Ortopedia', '$senhaMedico')
        ");
    }

    public function testLoginPacienteCorreto()
    {
        $cpf = "11122233344";
        $senha = "1234";

        $result = $this->conn->query("SELECT senha FROM paciente WHERE cpf = '$cpf'");
        $row = $result->fetch_assoc();

        $this->assertTrue(password_verify($senha, $row['senha']));
    }

    public function testLoginMedicoCorreto()
    {
        $crm = "CRM999";
        $senha = "5678";

        $result = $this->conn->query("SELECT senha FROM medico WHERE crm = '$crm'");
        $row = $result->fetch_assoc();

        $this->assertTrue(password_verify($senha, $row['senha']));
    }

    public function testLoginSenhaErrada()
    {
        $cpf = "11122233344";
        $senhaErrada = "senhaErrada";

        $result = $this->conn->query("SELECT senha FROM paciente WHERE cpf = '$cpf'");
        $row = $result->fetch_assoc();

        $this->assertFalse(password_verify($senhaErrada, $row['senha']));
    }

    public function testLoginUsuarioInexistente()
    {
        $cpf = "00000000000";

        $result = $this->conn->query("SELECT * FROM paciente WHERE cpf = '$cpf'");
        $this->assertEquals(0, $result->num_rows);
    }
}
