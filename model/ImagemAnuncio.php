<?php

class ImagemAnuncio {
    // Atributos privados para garantir o encapsulamento
    private int $id;
    private int $anuncioId;
    private string $caminhoArquivo;
    private bool $imgPrincipal;

    
    public function __construct(int $anuncioId = 0, string $caminhoArquivo = '', bool $imgPrincipal = false) {
        $this->anuncioId = $anuncioId;
        $this->caminhoArquivo = $caminhoArquivo;
        $this->imgPrincipal = $imgPrincipal;
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

    
    public function getCaminhoArquivo(): string {
        return $this->caminhoArquivo;
    }

    public function setCaminhoArquivo(string $caminhoArquivo): void {
        $this->caminhoArquivo = trim($caminhoArquivo);
    }

    public function isImgPrincipal(): bool {
        return $this->imgPrincipal;
    }

    public function setImgPrincipal(bool $imgPrincipal): void {
        $this->imgPrincipal = $imgPrincipal;
    }
}
