<?php


enum StatusPatrocinio: string 
    {
        case AGUARDANDO_PAGAMENTO = 'aguardando_pagamento';
        case ATIVO = 'ativo';
        case EXPIRADO = 'expirado';
        case CANCELADO = 'cancelado';
    }

class AnuncioPatrocinado 
{

    private int $id;
    private int $anuncioId;
    private DateTimeImmutable $dataInicio;
    private DateTimeImmutable $dataFim;
    private StatusPatrocinio $status = StatusPatrocinio::AGUARDANDO_PAGAMENTO;

    

    // Getters
    public function getId(): ?int 
    {
        return $this->id;
    }

    public function getAnuncioId(): int 
    {
        return $this->anuncioId;
    }

    public function getDataInicio(): DateTimeImmutable 
    {
        return $this->dataInicio;
    }

    public function getDataFim(): DateTimeImmutable 
    {
        return $this->dataFim;
    }

    public function getStatus(): StatusPatrocinio 
    {
        return $this->status;
    }

    // Setters (Modificadores)
    public function setStatus(StatusPatrocinio $status): void 
    {
        $this->status = $status;
    }

    public function setDataFim(DateTimeImmutable $dataFim): void 
    {
        $this->dataFim = $dataFim;
    }
}