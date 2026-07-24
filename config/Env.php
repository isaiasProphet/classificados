<?php



class Env 
{
    /**
     * Carrega as variáveis do arquivo .env para o ambiente
     */
    public static function load(string $path): bool 
    {
        if (!file_exists($path)) {
            return false;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            $line = trim($line);

            // Ignora linhas de comentários (# ou //)
            if ($line === '' || str_starts_with($line, '#') || str_starts_with($line, '//')) {
                continue;
            }

            // Garante que existe o separador '='
            if (str_contains($line, '=')) {
                list($key, $value) = explode('=', $line, 2);

                $key = trim($key);
                $value = trim($value);

                // Remove aspas simples ou duplas ao redor do valor
                $value = trim($value, '"\'');

                // Define no ambiente global e superglobais
                putenv("{$key}={$value}");
                $_ENV[$key] = $value;
                $_SERVER[$key] = $value;
            }
        }

        return true;
    }

    /**
     * Busca uma variável de ambiente com opção de valor padrão (default)
     */
    public static function get(string $key, mixed $default = null): mixed 
    {
        $value = $_ENV[$key] ?? getenv($key);

        if ($value === false || $value === null) {
            return $default;
        }

        // Converte strings booleanas ("true"/"false") para tipos booleans reais
        return match (strtolower($value)) {
            'true', '(true)'   => true,
            'false', '(false)' => false,
            'null', '(null)'   => null,
            default            => $value,
        };
    }
}