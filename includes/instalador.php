<?php
// Inclui o arquivo com a função de limpeza de entrada de dados
require_once 'funcoes.php';

// Inicializa a variável que armazenará mensagens de erro ou sucesso
$mensagem = "";

// Verifica se o formulário foi enviado (método POST)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Captura e limpa os dados enviados pelo formulário
    $nome_administrador = limparEntrada($_POST['nome']);
    $email_administrador = limparEntrada($_POST['email']);
    $senha_administrador = limparEntrada($_POST['senha']);
    $host_banco = limparEntrada($_POST['host_banco']);
    $nome_banco = limparEntrada($_POST['nome_banco']);
    $usuario_banco = limparEntrada($_POST['usuario_banco']);
    $senha_banco = limparEntrada($_POST['senha_banco']);

    // Verifica se algum campo obrigatório está vazio
    if (empty($nome_administrador) || empty($email_administrador) || empty($senha_administrador) ||
        empty($host_banco) || empty($nome_banco) || empty($usuario_banco)) {
        $mensagem = "Todos os campos são obrigatórios.";
    }
    // Valida o formato do e-mail
    elseif (!filter_var($email_administrador, FILTER_VALIDATE_EMAIL)) {
        $mensagem = "E-mail inválido.";
    }
    else {
        // Tenta conectar ao banco de dados usando os dados fornecidos
        $conexao_banco = new mysqli($host_banco, $usuario_banco, $senha_banco, $nome_banco);

        // Verifica se houve erro na conexão
        if ($conexao_banco->connect_error) {
            $mensagem = "Erro ao conectar ao banco: " . $conexao_banco->connect_error;
        }
        else {
            // Caminho correto do config.php dentro de /config
            $config_path = __DIR__ . '/../config/config.php';
            @is_dir(dirname($config_path)) || @mkdir(dirname($config_path), 0775, true);

            // Gera o conteúdo do arquivo de configuração config.php
            $config = "<?php\n";
            $config .= "define('DB_HOST', '" . addslashes($host_banco) . "');\n";
            $config .= "define('DB_NOME', '" . addslashes($nome_banco) . "');\n";
            $config .= "define('DB_USUARIO', '" . addslashes($usuario_banco) . "');\n";
            $config .= "define('DB_SENHA', '" . addslashes($senha_banco) . "');\n";

            // Salva o config.php dentro da pasta /config (caminho corrigido)
            if (@file_put_contents($config_path, $config) === false) {
                $mensagem = "Não foi possível escrever o arquivo de configuração em: " . htmlspecialchars($config_path);
            } else {
                // Gera o hash seguro da senha do administrador
                $senha_hash = password_hash($senha_administrador, PASSWORD_DEFAULT);

                // Prepara a query SQL para inserir o usuário administrador
                $sql_inserir_usuario = "INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, 'admin')";

                // Prepara o comando para evitar SQL Injection
                $comando_preparado = $conexao_banco->prepare($sql_inserir_usuario);

                // Faz o bind dos parâmetros (nome, email, senha_hash) para os "?" da query
                $comando_preparado->bind_param("sss", $nome_administrador, $email_administrador, $senha_hash);

                // Executa a query
                if ($comando_preparado->execute()) {
                    // Se deu certo, cria um arquivo de flag para indicar que o sistema já foi instalado
                    @file_put_contents(__DIR__ . '/instalado.flag', 'Instalado');

                    // Redireciona para a página de login
                    header('Location: ../pages/login.php');
                    exit;
                } else {
                    // Caso dê erro na criação do usuário
                    $mensagem = "Erro ao criar o administrador: " . $conexao_banco->error;
                }

                // Fecha o comando preparado e a conexão com o banco
                $comando_preparado->close();
            }

            $conexao_banco->close();
        }
    }
}
?>
