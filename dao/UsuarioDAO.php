<?php
require_once __DIR__ . '/../config/Database.php';
require_once __DIR__ . '/../model/Usuario.php';

class UsuarioDAO {
    private $conn;

    public function __construct() {
        $this->conn = Database::getConnection();
    }

    public function readById($id) {
        $query = "SELECT * FROM Usuario WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->execute();

        if ($row = $stmt->fetch()) {
            return $this->hydrateUsuario($row);
        }
        return null;
    }

    public function readAll() {
        $query = "SELECT * FROM Usuario ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        
        $usuarios = [];
        while ($row = $stmt->fetch()) {
            $usuarios[] = $this->hydrateUsuario($row);
        }
        return $usuarios;
    }

    public function readByEmail($email) {
        $query = "SELECT * FROM Usuario WHERE email = :email LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":email", $email);
        $stmt->execute();

        if ($row = $stmt->fetch()) {
            return $this->hydrateUsuario($row);
        }
        return null;
    }

    public function create(Usuario $usuario) {
        $query = "INSERT INTO Usuario (nome, email, senha, telefone, permissoes) VALUES (:nome, :email, :senha, :telefone, :permissoes)";
        $stmt = $this->conn->prepare($query);

        $stmt->bindValue(':nome', $usuario->getNome());
        $stmt->bindValue(':email', $usuario->getEmail());
        $stmt->bindValue(':senha', $usuario->getSenha()); // já hasheado pelo model
        $stmt->bindValue(':telefone', $usuario->getTelefone());
        $stmt->bindValue(':permissoes', $usuario->getPermissoes()->value);

        if ($stmt->execute()) {
            $usuario->setId($this->conn->lastInsertId());
            return true;
        }
        return false;
    }

    public function update(Usuario $usuario) {
        $query = "UPDATE Usuario SET nome = :nome, email = :email, telefone = :telefone, senha = :senha, igreja_id = :igreja_id, cargo_igreja = :cargo_igreja, sobre_mim = :sobre_mim, permissoes = :permissoes WHERE id = :id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindValue(':nome', $usuario->getNome());
        $stmt->bindValue(':email', $usuario->getEmail());
        $stmt->bindValue(':senha', $usuario->getSenha());
        $stmt->bindValue(':telefone', $usuario->getTelefone());
        $stmt->bindValue(':igreja_id', $usuario->getIgrejaId() ?: null);
        $stmt->bindValue(':cargo_igreja', $usuario->getCargoIgreja());
        $stmt->bindValue(':sobre_mim', $usuario->getSobreMim());
        $stmt->bindValue(':permissoes', $usuario->getPermissoes()->value);
        $stmt->bindValue(':id', $usuario->getId());

        return $stmt->execute();
    }

    public function deactivate($id) {
        $query = "UPDATE Usuario SET ativo = 0 WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        return $stmt->execute();
    }

    private function hydrateUsuario($row) {
        $dataCadastro = new DateTime($row['dataCadastro']);
        $permissao = PermissaoUsuario::from($row['permissoes']);
        
        $usuario = new Usuario(
            $row['nome'],
            $row['email'],
            '', // Pass empty string to avoid rehashing
            $row['telefone'] ?? '',
            $dataCadastro,
            $permissao,
            $row['igreja_id'] ?? 0,
            $row['cargo_igreja'] ?? '',
            $row['sobre_mim'] ?? '',
            $row['foto_perfil'] ?? '',
            isset($row['ativo']) ? (bool)$row['ativo'] : true
        );
        $usuario->setId($row['id']);
        
        // Use reflection to set the password hash without triggering the setter
        $reflection = new ReflectionClass($usuario);
        $property = $reflection->getProperty('senha');
        $property->setAccessible(true);
        $property->setValue($usuario, $row['senha']);

        return $usuario;
    }
}
