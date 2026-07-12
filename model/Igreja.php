<?php

class Igreja {

    private int $id;
    private string $nome;
    private string $pastorPresidente;
    private int $bairro_id;

    public function __construct(
        string $nome = '',
        string $pastorPresidente = null,
        int $bairro_id = null
    ) {
        $this->nome = $nome;
        $this->pastorPresidente = $pastorPresidente;
        $this->bairro_id = $bairro_id;
    }

 
    public function getId(): ?int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    
    public function getNome(): string {
        return $this->nome;
    }

    public function setNome(string $nome): void {
        $this->nome = trim($nome);
    }

    
    public function getPastorPresidente(): ?string {
        return $this->pastorPresidente;
    }

    public function setPastorPresidente(?string $pastorPresidente): void {
        $this->pastorPresidente = $pastorPresidente ? trim($pastorPresidente) : null;
    }

    
    public function getBairro_id(): ?int {
        return $this->bairro_id;
    }

    public function setBairro_id(?int $bairro_id): void {
        $this->bairro_id = $bairro_id;
    }
}