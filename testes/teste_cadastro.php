<?php
use PHPUnit\Framework\TestCase;

class Teste_cadastro extends TestCase
{
    private $conn;

    protected function setUp(): void
    {
        $this->conn = new mysqli("localhost", "root", "28132813", "agenda_teste");
        $this->conn->query("DELETE FROM paciente");
        $this->conn->query("DELETE FROM medico");
    }

    public function testCadastroPaciente()
    {
        $nome = "João Teste";
        $cpf = "12345678901";
        $telefone = "88999999999";
        $senha = password_hash("1234", PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare("INSERT INTO paciente (nome, cpf, telefone, senha) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nome, $cpf, $telefone, $senha);
        $this->assertTrue($stmt->execute());

        // Verifica inserção
        $result = $this->conn->query("SELECT * FROM paciente WHERE cpf = '$cpf'");
        $this->assertEquals(1, $result->num_rows);
    }

    public function testCadastroMedico()
    {
        $nome = "Dr. Pedro";
        $crm = "CRM1234";
        $especialidade = "Cardiologia";
        $senha = password_hash("4321", PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare("INSERT INTO medico (nome, crm, especialidade, senha) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $nome, $crm, $especialidade, $senha);
        $this->assertTrue($stmt->execute());

        // Verifica inserção
        $result = $this->conn->query("SELECT * FROM medico WHERE crm = '$crm'");
        $this->assertEquals(1, $result->num_rows);
    }
}
