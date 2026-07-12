<?php

enum PermissaoUsuario: string {
    case ADMIN = 'admin';
    case MODERADOR = 'moderador';
    case ANUNCIANTE = 'anunciante';
    case CLIENTE = 'cliente';
}

class Usuario {

    private int $id;
    private string $nome;
    private string $email;
    private string $senha; 
    private string $telefone;
    private DateTime $dataCadastro;
    private PermissaoUsuario $permissoes; // Atributo utilizando o Enum estruturado

    // Novos atributos específicos para a comunidade
    private int $igrejaId;
    private string $cargoIgreja;
    private string $sobreMim; 
    private string $fotoPerfilPath;
    private bool $ativo;
    private DateTime $dataDesativacao;

    public function __construct(
        string $nome = '',
        string $email = '',
        string $senha = '',
        string $telefone = '',
        ?DateTime $dataCadastro = null,
        PermissaoUsuario $permissoes = PermissaoUsuario::ANUNCIANTE,
        int $igrejaId = 0,
        string $cargoIgreja = '',
        string $sobreMim = '',
        string $fotoPerfilPath = '',
        bool $ativo = true
    ) {
        $this->nome = $nome;
        $this->email = $email;
        // Se uma senha bruta for informada no construtor, aplica o hash seguro automaticamente
        $this->senha = !empty($senha) ? password_hash($senha, PASSWORD_DEFAULT) : '';
        $this->telefone = $telefone;
        $this->dataCadastro = $dataCadastro ?? new DateTime(); // Se nulo, assume a data/hora atual do servidor
        $this->permissoes = $permissoes;
        $this->igrejaId = $igrejaId;
        $this->cargoIgreja = $cargoIgreja;
        $this->sobreMim = $sobreMim;
        $this->fotoPerfilPath = $fotoPerfilPath;
        $this->ativo = $ativo;
        $this->dataDesativacao = new DateTime(); 
    }

    // --- GETTERS E SETTERS ---

    // ID
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

    // EMAIL
    public function getEmail(): string {
        return $this->email;
    }

    public function setEmail(string $email): void {
        $this->email = strtolower(trim($email));
    }

    // SENHA
    public function getSenha(): string {
        return $this->senha;
    }

    /**
     * Altera a senha do usuário, aplicando o hash seguro automaticamente.
     */
    public function setSenha(string $senha): void {
        $this->senha = password_hash($senha, PASSWORD_DEFAULT);
    }

    /**
     * Método auxiliar para validar o login (verifica se a senha digitada bate com o hash salvo).
     */
    public function verificarSenha(string $senhaDigitada): bool {
        return password_verify($senhaDigitada, $this->senha);
    }

    
    public function getTelefone(): string {
        return $this->telefone;
    }

    public function setTelefone(string $telefone): void {
        $this->telefone = trim($telefone);
    }


   
    public function getDataCadastro(): DateTime {
        return $this->dataCadastro;
    }

    public function setDataCadastro(DateTime $dataCadastro): void {
        $this->dataCadastro = $dataCadastro;
    }

  
    public function getPermissoes(): PermissaoUsuario {
        return $this->permissoes;
    }

    public function setPermissoes(PermissaoUsuario $permissoes): void {
        $this->permissoes = $permissoes;
    }

    // --- Novos métodos para perfil comunitário ---

    public function getIgrejaId(): int {
        return $this->igrejaId;
    }

    public function setIgrejaId(int $igrejaId): void {
        $this->igrejaId = $igrejaId;
    }

    public function getCargoIgreja(): string {
        return $this->cargoIgreja;
    }

    public function setCargoIgreja(string $cargoIgreja): void {
        $this->cargoIgreja = trim($cargoIgreja);
    }

    public function getSobreMim(): string {
        return $this->sobreMim;
    }

    public function setSobreMim(string $sobreMim): void {
        $this->sobreMim = trim($sobreMim);
    }

    public function getFotoPerfilPath(): string {
        return $this->fotoPerfilPath;
    }

    public function setFotoPerfilPath(string $fotoPerfilPath): void {
        $this->fotoPerfilPath = $fotoPerfilPath;
    }

    public function getAtivo(): bool {
        return $this->ativo;
    }

    public function setAtivo(bool $ativo): void {
        $this->ativo = $ativo;
    }

    public function getDataDesativacao(): DateTime {
        return $this->dataDesativacao;
    }

    public function setDataDesativacao(DateTime $dataDesativacao): void {
        $this->dataDesativacao = $dataDesativacao;
    }

}
