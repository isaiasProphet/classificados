<?php

class SubCategoria {
    
    private int $id;
    private string $nome;
    private int $categoria_id;
    private string $slug; 
   
    public function __construct(string $nome = '', int $categoria_id=null, string $slug = '') {
        $this->nome = $nome;
        $this->categoria_id = $categoria_id;
        $this->slug  = $slug;
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
    
    public function getcategoria_id(): string{
    	return $this->categoria_id;
    }
    
    public function setCategoria_id(string $categoria_id):void {
    	$this->categoria_id = trim($categoria_id);
    }
    
    public function getSlug(): string {
        return $this->slug;
    }

    public function setSlug(string $slug): void {
        $this->slug = strtolower(trim($slug));
    }

	
   
}
