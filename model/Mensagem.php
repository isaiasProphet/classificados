<?php

class Mensagem
{
    private int $id;
    private int $remetenteUsuarioId;
    private int $destinatarioUsuarioId;
    private int $anuncioId;
    private string $texto;
    private DateTime $dataEnvio;
    private bool $lida;
    private DateTime $dataLeitura;

    public function __construct(
        int $id,
        int $remetenteUsuarioId,
        int $destinatarioUsuarioId,
        int $anuncioId,
        string $texto,
        ?DateTime $dataEnvio
    ) {
        $this->id = $id;
        $this->remetenteUsuarioId = $remetenteUsuarioId;
        $this->destinatarioUsuarioId = $destinatarioUsuarioId;
        $this->anuncioId = $anuncioId;
        $this->texto = $texto;
        $this->dataEnvio = $dataEnvio;
    }

    public function getTexto(): string
    {
        return $this->texto;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getRemetenteUsuarioId(): int
    {
        return $this->remetenteUsuarioId;
    }

    public function setRemetenteUsuarioId(int $remetenteUsuarioId): void
    {
        $this->remetenteUsuarioId = $remetenteUsuarioId;
    }

    public function getDestinatarioUsuarioId(): int
    {
        return $this->destinatarioUsuarioId;
    }

    public function setDestinatarioUsuarioId(int $destinatarioUsuarioId): void
    {
        $this->destinatarioUsuarioId = $destinatarioUsuarioId;
    }

    public function getAnuncioId(): int
    {
        return $this->anuncioId;
    }

    public function setAnuncioId(int $anuncioId): void
    {
        $this->anuncioId = $anuncioId;
    }

    public function setTexto(string $texto): void
    {
        $this->texto = $texto;
    }

    public function getDataEnvio(): DateTime
    {
        return $this->dataEnvio;
    }

    public function setDataEnvio(DateTime $dataEnvio): void
    {
        $this->dataEnvio = $dataEnvio;
    }

    public function isLida(): bool
    {
        return $this->lida;
    }

    public function setLida(bool $lida): void
    {
        $this->lida = $lida;
    }

    public function getDataLeitura(): DateTime
    {
        return $this->dataLeitura;
    }

    public function setDataLeitura(DateTime $dataLeitura): void
    {
        $this->dataLeitura = $dataLeitura;
    }
}