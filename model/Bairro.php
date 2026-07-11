<?php

class Bairro {
    // Atributos privados para garantir o encapsulamento
    private ?int $id = null;
    private string $nome;
    private int $cidadeId;

    // Construtor para facilitar a inicialização dos dados
    public function __construct(string $nome = '', int $cidadeId = 0) {
        $this->nome = $nome;
        $this->cidadeId = $cidadeId;
    }

    // --- GETTERS E SETTERS ---

    // ID (Geralmente auto-incrementado pelo banco de dados)
    public function getId(): ?int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    // NOME
    public function getNome(): string {
        return $this->nome;
    }

    public function setNome(string $nome): void {
        $this->nome = trim($nome);
    }

    // CIDADE ID (Chave estrangeira relacionando o bairro a uma cidade)
    public function getCidadeId(): int {
        return $this->cidadeId;
    }

    public function setCidadeId(int $cidadeId): void {
        $this->cidadeId = $cidadeId;
    }
}
