<?php

class Localizacao {
    // Atributos privados para garantir o encapsulamento
    private int $id;
    private int $anuncioId;
    private int $bairroId;
    private string $rua;
    private string $numero;
    private string $linkMaps;

    // Construtor para facilitar a inicialização dos dados
    public function __construct(
        int $anuncioId = 0, 
        int $bairroId = 0, 
        string $rua = '', 
        string $numero = '', 
        string $linkMaps = ''
    ) {
        $this->anuncioId = $anuncioId;
        $this->bairroId = $bairroId;
        $this->rua = $rua;
        $this->numero = $numero;
        $this->linkMaps = $linkMaps;
    }

   
    public function getId(): ?int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

  
    public function getAnuncioId(): int {
        return $this->anuncioId;
    }

    public function setAnuncioId(int $anuncioId): void {
        $this->anuncioId = $anuncioId;
    }

   
    public function getBairroId(): int {
        return $this->bairroId;
    }

    public function setBairroId(int $bairroId): void {
        $this->bairroId = $bairroId;
    }

    // RUA
    public function getRua(): string {
        return $this->rua;
    }

    public function setRua(string $rua): void {
        $this->rua = trim($rua);
    }

    
    public function getNumero(): string {
        return $this->numero;
    }

    public function setNumero(string $numero): void {
        $this->numero = trim($numero);
    }

    // LINK MAPS (URL de compartilhamento gerada pelo Google Maps)
    public function getLinkMaps(): string {
        return $this->linkMaps;
    }

    public function setLinkMaps(string $linkMaps): void {
        $this->linkMaps = trim($linkMaps);
    }
}
