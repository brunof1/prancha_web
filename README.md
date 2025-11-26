# Prancha Web

Prancha Web é uma plataforma web gratuita e personalizável de Comunicação Alternativa e Aumentativa (CAA) voltada principalmente a pessoas com Transtorno do Espectro Autista (TEA) não verbais. Permite criar pranchas de comunicação com cartões (imagens + texto), organizar por grupos temáticos e utilizar leitura em voz alta (síntese de fala) diretamente no navegador.

## Funcionalidades principais

- Cadastro de **grupos de cartões** (ex.: Rotina, Alimentação, Escola).
- Cadastro de **cartões** com imagem, rótulo e texto alternativo.
- Organização de cartões em **pranchas** personalizáveis, com ordem definida.
- **Leitura individual** de cartões e **leitura sequencial** (“Falar tudo”) via Web Speech API.
- Suporte a **múltiplos usuários** com perfis:
  - **Administrador**: gerencia grupos, cartões, pranchas, usuários e Bateria Social.
  - **Usuário** (não admin): utiliza pranchas em interface simplificada.
- **Bateria Social** para registrar o nível de energia/disponibilidade (0–5) de cada usuário.
- Integração com o banco público de pictogramas do **ARASAAC**.
- Interface responsiva (desktop, tablet, celular), com foco em acessibilidade e conforto sensorial.
- Tema **claro/escuro** e ajustes de fonte e voz configuráveis por usuário.

## Tecnologias utilizadas

- **PHP** (lógica de negócio / back-end)
- **MySQL** (banco de dados relacional)
- **HTML, CSS e JavaScript** (camada de apresentação)
- **Web Speech API** (leitura em voz alta no navegador)
- **Git** (controle de versão)

## Requisitos mínimos

- PHP 7.4+ (ou versão compatível com o código do repositório)
- Servidor web (Apache, Nginx ou similar)
- MySQL/MariaDB com suporte a utf8mb4
- Extensão mysqli habilitada no PHP
- Navegador com suporte à Web Speech API (para recursos de fala)

## Instalação rápida

1. Clone este repositório ou faça o download dos arquivos:

   Com Git:
   git clone https://github.com/seu-usuario/seu-repositorio.git

   Ou baixe o .zip diretamente pelo GitHub e extraia em uma pasta acessível pelo servidor web.

2. Crie um banco de dados MySQL (por exemplo, pranchaweb) e importe o script SQL do projeto.

3. Configure os dados de conexão ao banco no arquivo de configuração do sistema  
   (por exemplo, config/config.php), informando:
   - servidor do banco (host),
   - nome do banco,
   - usuário,
   - senha.

4. Acesse a URL do projeto no navegador. No primeiro acesso, utilize o **assistente de instalação inicial** para:
   - validar a conexão com o banco;
   - criar o primeiro usuário **administrador**.

5. Após o login, utilize o menu do sistema para:
   - cadastrar grupos de cartões e cartões;
   - cadastrar grupos de pranchas e pranchas;
   - cadastrar usuários adicionais e ajustar a Bateria Social.

## Estrutura básica do projeto

prancha_web/
|   index.php
|   instalacao_banco_de_dados.sql
|   LICENSE				(texto completo da GNU GPL v3)
|   LICENSE.txt				(texto completo da Apache License 2.0)
|   README.md
|       
+---assets/
|   +---css/
|   |       bateria.css
|   |       configuracoes.css
|   |       form-usuarios.css
|   |       pranchas.css
|   |       style.css
|   |       tabela_responsiva.css
|   |       usuarios.css
|   |       wcag.css
|   |       
|   \---js/
|           arasaac.js
|           bateria.js
|           bateria_admin.js
|           configuracoes.js
|           drop.js
|           falar.js
|           grupos.js
|           menu.js
|           ordem_cartoes.js
|           pranchas.js
|           tema.js
|           usuarios_admin.js
|       
+---imagens/
|   |   
|   \---cartoes/
|       
+---includes/
|       acl.php
|       arasaac.php
|       arasaac_buscar.php
|       arasaac_importar.php
|       atualizar_tema.php
|       bateria_api.php
|       cabecalho.php
|       conexao.php
|       controle_cartoes.php
|       controle_configuracoes.php
|       controle_criar_cartao.php
|       controle_criar_grupo.php
|       controle_editar_cartao.php
|       controle_editar_grupo.php
|       controle_editar_grupo_prancha.php
|       controle_editar_prancha.php
|       controle_excluir_cartao.php
|       controle_excluir_grupo.php
|       controle_excluir_grupo_prancha.php
|       controle_excluir_prancha.php
|       controle_grupos_cartoes.php
|       controle_grupos_pranchas.php
|       controle_login.php
|       controle_nova_prancha.php
|       controle_novo_cartao.php
|       controle_pranchas.php
|       controle_salvar_cartao.php
|       controle_usuarios_admin.php
|       funcoes.php
|       instalador.php
|       logout.php
|       modelo_cartoes.php
|       modelo_grupos.php
|       modelo_grupos_pranchas.php
|       modelo_pranchas.php
|       modelo_preferencias.php
|       modelo_usuarios.php
|       preferencias_api.php
|       rodape.php
|       widget_bateria.php
|       
\---pages/
        bateria_social.php
        bateria_social_admin.php
        configuracoes.php
        criar_cartao.php
        criar_grupo_cartao.php
        criar_grupo_prancha.php
        criar_prancha.php
        dashboard.php
        editar_cartao.php
        editar_grupo_cartao.php
        editar_grupo_prancha.php
        editar_prancha.php
        editar_usuario.php
        gerenciar_cartoes.php
        gerenciar_grupos_pranchas.php
        gerenciar_pranchas.php
        gerenciar_usuarios.php
        listar_cartoes_grupo.php
        login.php
        manual.php
        ver_prancha.php

## Licenciamento

Este projeto é disponibilizado sob **licenciamento duplo** (dual license):

- **Apache License 2.0**
- **GNU General Public License v3.0 (GPLv3)**

Quem reutilizar o código pode optar por **qualquer uma das duas licenças**, desde que cumpra integralmente os termos da licença escolhida.

- O texto completo da **GNU GPL v3** encontra-se no arquivo `LICENSE`.
- O texto completo da **Apache License 2.0** encontra-se no arquivo `license.txt` (ou no site oficial: https://www.apache.org/licenses/LICENSE-2.0).

Ao contribuir com o projeto, você concorda que suas contribuições sejam disponibilizadas sob os mesmos termos de licenciamento duplo (Apache 2.0 ou GPLv3).

## Créditos

- Autor: **Bruno Silva da Silva**
- Projeto desenvolvido como parte de Trabalho de Conclusão de Curso (TCC) em Ciência da Computação – UNISINOS.
- Pictogramas: **ARASAAC – Centro Aragonês de Comunicação Aumentativa e Alternativa**.
- Ambiente de demonstração (temporário): https://www.pranchaweb.online (se ainda estiver ativo).
