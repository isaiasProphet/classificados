<?php


enum StatusAnuncio: string {
    case ATIVO = 'ativo';
    case PAUSADO = 'pausado';
    case VENDIDO = 'vendido';
    case PENDENTE_APROVACAO = 'pendente_aprovacao';
}

class Anuncio {

    private int $id;
    private string $titulo;
    private string $descricao;
    private int $subCategoriaId;
    private int $usuarioId;
    private float $preco; 
    private StatusAnuncio $status;
    private int $visualizacoes;

    
    private DateTime $dataCriacao;
    private DateTime $dataAtualizacao;

    private ?string $capaPath = null;
  
    public function __construct(
        string $titulo = '',
        string $descricao = '',
        int $subCategoriaId = 0,
        int $usuarioId = 0,
        float $preco = 0.0,
        StatusAnuncio $status = StatusAnuncio::PENDENTE_APROVACAO,
        int $visualizacoes = 0,
        ?DateTime $dataCriacao = null,
        ?DateTime $dataAtualizacao = null
    ) {
        $this->titulo = $titulo;
        $this->descricao = $descricao;
        $this->subCategoriaId = $subCategoriaId;
        $this->usuarioId = $usuarioId;
        $this->preco = $preco;
        $this->status = $status;
        $this->visualizacoes = $visualizacoes;
        $this->dataCriacao = $dataCriacao ?? new DateTime(); // Assume a data/hora atual se nulo
        $this->dataAtualizacao = $dataAtualizacao ?? new DateTime();
    }

  
    public function getId(): ?int {
        return $this->id;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }


    public function getTitulo(): string {
        return $this->titulo;
    }

    public function setTitulo(string $titulo): void {
        $this->titulo = trim($titulo);
    }

    
    public function getDescricao(): string {
        return $this->descricao;
    }

    public function setDescricao(string $descricao): void {
        $this->descricao = trim($descricao);
    }


    public function getSubCategoriaId(): int {
        return $this->subCategoriaId;
    }

    public function setSubCategoriaId(int $subCategoriaId): void {
        $this->subCategoriaId = $subCategoriaId;
    }

    public function getUsuarioId(): int {
        return $this->usuarioId;
    }

    public function setUsuarioId(int $usuarioId): void {
        $this->usuarioId = $usuarioId;
    }

   
    public function getPreco(): float {
        return $this->preco;
    }

    public function setPreco(float $preco): void {
        $this->preco = $preco;
    }

 
    public function getStatus(): StatusAnuncio {
        return $this->status;
    }

    public function setStatus(StatusAnuncio $status): void {
        $this->status = $status;
    }

    public function getCapaPath(): ?string {
        return $this->capaPath;
    }

    public function setCapaPath(string $capaPath): void {
        $this->capaPath = $capaPath;
    }

 
    public function getVisualizacoes(): int {
        return $this->visualizacoes;
    }

    public function setVisualizacoes(int $visualizacoes): void {
        $this->visualizacoes = $visualizacoes;
    }

    /**
     * Incrementa o contador de visualizações do anúncio em 1.
     */
    public function incrementarVisualizacoes(): void {
        $this->visualizacoes++;
    }

  
    public function getDataCriacao(): DateTime {
        return $this->dataCriacao;
    }

    public function setDataCriacao(DateTime $dataCriacao): void {
        $this->dataCriacao = $dataCriacao;
    }

    
    public function getDataAtualizacao(): DateTime {
        return $this->dataAtualizacao;
    }

    public function setDataAtualizacao(DateTime $dataAtualizacao): void {
        $this->dataAtualizacao = $dataAtualizacao;
    }
}
