<?php
// pages/manual.php
require_once __DIR__ . '/../includes/cabecalho.php';

?>
<section class="card" aria-labelledby="titulo-manual">
  <h1 id="titulo-manual">📘 Manual & FAQ — Prancha Web</h1>
  <p>Bem-vindo! Este guia explica <strong>o que é</strong> o sistema, <strong>como usar</strong> cada parte e o que fazer quando algo não der certo. Foi escrito para quem não tem familiaridade com tecnologia.</p>

  <nav aria-label="Sumário" class="campo" style="margin-top:12px;">
    <strong>Sumário rápido</strong>
    <ul class="ml-8" style="margin-top:6px;">
      <li><a href="#o-que-e">O que é o Prancha Web</a></li>
      <li><a href="#como-entrar">Como entrar e navegar</a></li>
      <li><a href="#cartoes">Cartões (imagens com título)</a></li>
      <li><a href="#pranchas">Pranchas (conjunto de cartões)</a></li>
      <li><a href="#bateria-social">Bateria Social (bem-estar para interações)</a></li>
      <li><a href="#configuracoes">Configurações (voz, tema, acessibilidade)</a></li>
      <li><a href="#faq">Perguntas frequentes (FAQ)</a></li>
    </ul>
  </nav>
</section>

<section class="card" id="o-que-e">
  <h2>💡 O que é o Prancha Web</h2>
  <p>É um sistema simples para criar <strong>cartões</strong> (imagens com título) e organizá-los em <strong>pranchas</strong> (conjuntos). Essas pranchas podem ser usadas para comunicação alternativa, rotinas, agendas visuais, histórias sociais e muito mais.</p>
  <ul class="ml-8">
    <li><strong>Cartões</strong>: cada cartão tem um título, uma imagem e um texto alternativo.</li>
    <li><strong>Pranchas</strong>: reúnem vários cartões, na ordem que você escolher.</li>
    <li><strong>Grupos</strong>: servem apenas para <em>organizar</em>. Existem “grupos de cartões” e “grupos de pranchas”.</li>
  </ul>
</section>

<section class="card" id="como-entrar">
  <h2>🚪 Como entrar e navegar</h2>
  <ol class="ml-8">
    <li>Acesse a página de <strong>Login</strong> e entre com seu e-mail e senha.</li>
    <li>Você chegará à tela <strong>Início</strong> (Dashboard).</li>
    <li>Use o botão <strong>📖 Menu</strong> (no topo) para ir para:
      <ul>
        <li><strong>Cartões</strong>: criar/editar/excluir cartões e grupos de cartões.</li>
        <li><strong>Pranchas</strong>: criar/editar/excluir pranchas e grupos de pranchas.</li>
        <?php /* Mostramos as entradas que o dropdown já oferece */ ?>
        <li><strong>Bateria</strong>: registrar seu nível atual (0 a 5).</li>
        <li><strong>Configurações</strong>: voz, velocidade, tema e preferências.</li>
      </ul>
    </li>
    <li>À direita, há:
      <ul>
        <li><strong>🌓 Alternar tema</strong> (claro/escuro).</li>
        <li><strong>❓ Manual/FAQ</strong> (esta página).</li>
        <li><strong>🚪 Sair</strong> para encerrar a sessão.</li>
      </ul>
    </li>
  </ol>

  <div class="alert" style="margin-top:10px;">
    Dica: o site tem um link “<em>Pular para o conteúdo</em>” para quem usa teclado/leitor de tela.
  </div>
</section>

<section class="card" id="cartoes">
  <h2>🖼️ Cartões (imagens com título)</h2>

  <h3>Antes de criar cartões, crie um <em>grupo de cartões</em></h3>
  <div class="alert">
    <strong>Importante:</strong> se você ainda não tem nenhum <em>grupo de cartões</em>, <strong>crie um</strong> primeiro. Sem grupo, não é possível salvar um cartão.
  </div>
  <ol class="ml-8">
    <li>Abra <strong>📖 Menu → Cartões</strong> (página <code>gerenciar_cartoes.php</code>).</li>
    <li>Clique em <strong>Novo grupo</strong> ou <strong>Gerenciar grupos</strong> (o nome pode variar). Dê um nome simples, ex.: <em>Rotina</em>, <em>Casa</em>, <em>Escola</em>.</li>
  </ol>

  <h3>Criar um cartão</h3>
  <ol class="ml-8">
    <li>Na página de Cartões, clique em <strong>Novo cartão</strong>.</li>
    <li>Preencha:
      <ul>
        <li><strong>Título</strong> (ex.: “Beber água”).</li>
        <li><strong>Imagem</strong>: você pode
          <ul>
            <li>enviar uma imagem do seu computador (PNG/JPG/WEBP/SVG até 4MB), ou</li>
            <li>usar <strong>ARASAAC</strong>: pesquise um termo, clique em <em>Importar</em> e a imagem será baixada automaticamente.</li>
          </ul>
        </li>
        <li><strong>Grupo</strong> (escolha aquele que você criou).</li>
        <li><strong>Texto alternativo</strong> (opcional, ajuda na acessibilidade).</li>
      </ul>
    </li>
    <li>Clique em <strong>Salvar</strong>.</li>
  </ol>

  <h3>Editar ou excluir um cartão</h3>
  <ul class="ml-8">
    <li>Use os botões de <strong>Editar</strong> ou <strong>Excluir</strong> ao lado do cartão desejado.</li>
    <li>Para trocar a imagem, basta enviar outra ou importar da ARASAAC novamente.</li>
  </ul>

  <div class="help">Se a importação ARASAAC não carregar, verifique sua conexão com a internet e tente novamente.</div>
</section>

<section class="card" id="pranchas">
  <h2>📋 Pranchas (conjunto de cartões)</h2>

  <h3>Antes de criar pranchas, crie um <em>grupo de pranchas</em></h3>
  <div class="alert">
    <strong>Importante:</strong> se não existir nenhum <em>grupo de pranchas</em>, <strong>crie um</strong> primeiro. Sem grupo, não é possível salvar a prancha.
  </div>
  <ol class="ml-8">
    <li>Abra <strong>📖 Menu → Pranchas</strong> (página <code>gerenciar_pranchas.php</code>).</li>
    <li>Clique em <strong>Novo grupo</strong> (ex.: <em>Rotinas da manhã</em>, <em>Comunicação</em>).</li>
  </ol>

  <h3>Criar uma prancha</h3>
  <ol class="ml-8">
    <li>Clique em <strong>Nova prancha</strong>.</li>
    <li>Preencha:
      <ul>
        <li><strong>Nome</strong> e (se quiser) <strong>descrição</strong>.</li>
        <li><strong>Grupo de pranchas</strong> (aquele que você criou).</li>
        <li><strong>Selecione os cartões</strong> que farão parte da prancha:
          <ul>
            <li>clique nos cartões para marcar/desmarcar;</li>
            <li>a seleção mostra um número indicando a <strong>ordem</strong>;</li>
            <li>você pode mudar a ordem simplesmente desmarcando e marcando de novo, ou usando os controles disponíveis.</li>
          </ul>
        </li>
        <li><strong>Compartilhar com usuários</strong> (opcional, para quem é administrador):
          <ul><li>marque os usuários que poderão ver/usar a prancha.</li></ul>
        </li>
      </ul>
    </li>
    <li>Clique em <strong>Salvar</strong>.</li>
  </ol>

  <h3>Usar a prancha</h3>
  <ul class="ml-8">
    <li>A prancha mostra os cartões na ordem escolhida.</li>
    <li>Se você ativar a opção <strong>“Falar ao clicar”</strong> nas <a href="#configuracoes">Configurações</a>, tocar no cartão faz o sistema falar o título.</li>
    <li>Algumas telas oferecem o botão <strong>“Falar tudo”</strong> para ler a sequência inteira.</li>
  </ul>
</section>

<section class="card" id="bateria-social">
  <h2>🔋 Bateria Social — o que é e como usar</h2>
  <p>A <strong>Bateria Social</strong> é um medidor simples (0 a 5) de como a pessoa está se sentindo para interagir com outras pessoas naquele momento.</p>
  <ul class="ml-8">
    <li><strong>0 – Esgotado:</strong> preciso de silêncio/pausa.</li>
    <li><strong>1 – Baixíssimo</strong> / <strong>2 – Baixo</strong>:</li>
    <li><strong>3 – Neutro:</strong> nem muito disposto, nem indisposto.</li>
    <li><strong>4 – Bom</strong> / <strong>5 – Cheio:</strong> animado para conversar e participar.</li>
  </ul>

  <h3>Como registrar o nível</h3>
  <ol class="ml-8">
    <li>Abra <strong>📖 Menu → Bateria</strong>.</li>
    <li>Escolha um nível (0 a 5). O sistema salva automaticamente.</li>
  </ol>

  <div class="help">Na visão do administrador existe um painel com a bateria de cada usuário, para acompanhamento.</div>
</section>

<section class="card" id="configuracoes">
  <h2>⚙️ Configurações (voz, tema, acessibilidade)</h2>

  <h3>Voz e leitura em voz alta</h3>
  <ol class="ml-8">
    <li>Abra <strong>📖 Menu → Configurações</strong>.</li>
    <li>Em <strong>Preferências de voz</strong>:
      <ul>
        <li>Escolha a <strong>voz</strong> do seu dispositivo (se disponível).</li>
        <li>Ajuste <strong>velocidade</strong>, <strong>tom</strong> e <strong>volume</strong>.</li>
        <li>Ative <strong>“Falar ao clicar”</strong> para ler o título dos cartões quando você tocar neles.</li>
        <li>Use o botão <strong>“Testar voz”</strong> para ouvir antes de salvar.</li>
      </ul>
    </li>
  </ol>

  <h3>Tema claro/escuro</h3>
  <p>Use o botão <strong>🌓</strong> no topo para alternar. A escolha fica salva para a próxima vez.</p>

  <h3>Acessibilidade</h3>
  <ul class="ml-8">
    <li>Todos os botões têm tamanho confortável e foco visível.</li>
    <li>As tabelas rolam horizontalmente no celular sem esconder colunas.</li>
    <li>Os textos e cores foram pensados para bom contraste.</li>
  </ul>
</section>

<section class="card" id="faq">
  <h2>❓ Perguntas frequentes (FAQ)</h2>

  <details class="campo">
    <summary class="botao-acao">Não consigo criar um cartão</summary>
    <div class="alert" style="margin-top:8px;">
      Provavelmente você <strong>ainda não criou um grupo de cartões</strong>. Vá em <em>Cartões</em> → <strong>Novo grupo</strong>, crie um grupo e tente novamente.
    </div>
  </details>

  <details class="campo">
    <summary class="botao-acao">Não consigo criar uma prancha</summary>
    <div class="alert" style="margin-top:8px;">
      Primeiro crie um <strong>grupo de pranchas</strong>. Depois, ao criar a prancha, selecione os <strong>cartões</strong> que ela terá.
    </div>
  </details>

  <details class="campo">
    <summary class="botao-acao">A imagem não sobe ou dá erro</summary>
    <div class="alert" style="margin-top:8px;">
      Use PNG/JPG/WEBP/SVG até <strong>4MB</strong>. Se preferir, pesquise pela <strong>ARASAAC</strong> e clique em <em>Importar</em>.
    </div>
  </details>

  <details class="campo">
    <summary class="botao-acao">Importei da ARASAAC, mas não aparece</summary>
    <div class="alert" style="margin-top:8px;">
      Verifique sua internet e tente de novo. Ao importar com sucesso, o sistema marca o item como <em>Selecionado</em> e preenche o campo oculto da imagem.
    </div>
  </details>

  <details class="campo">
    <summary class="botao-acao">Os cartões estão fora de ordem na prancha</summary>
    <div class="alert" style="margin-top:8px;">
      Na tela de edição da prancha, <strong>clique nos cartões</strong> para remarcar e definir a ordem. Os números nos cantos mostram a sequência.
    </div>
  </details>

  <details class="campo">
    <summary class="botao-acao">Quero que a prancha leia os cartões em voz alta</summary>
    <div class="alert" style="margin-top:8px;">
      Em <strong>Configurações</strong>, ative <strong>“Falar ao clicar”</strong> e ajuste a voz. Depois, clique nos cartões para ouvir.
    </div>
  </details>

  <details class="campo">
    <summary class="botao-acao">Esqueci a senha</summary>
    <div class="alert" style="margin-top:8px;">
      Entre em contato com o administrador para redefinir sua senha.
    </div>
  </details>
</section>

<p class="help" style="text-align:center;margin:20px 0;">Se algo continuar difícil, fale com a pessoa administradora do sistema. Estamos aqui para facilitar seu dia a dia. 💙</p>

<?php
require_once __DIR__ . '/../includes/rodape.php';
