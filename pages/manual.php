<?php

/**
 * Prancha Web
 * Plataforma Web de Comunicação Alternativa e Aumentativa (CAA)
 *
 * Copyright (c) 2025 Bruno Silva da Silva
 *
 * Este arquivo faz parte do projeto Prancha Web.
 *
 * Licenciamento duplo:
 * - Apache License 2.0
 * - GNU General Public License v3.0 (GPLv3)
 *
 * Você pode redistribuir e/ou modificar este arquivo sob os termos de
 * qualquer uma das licenças, a seu critério, desde que cumpra integralmente
 * os respectivos requisitos.
 *
 * Você deve ter recebido uma cópia das licenças junto com este programa.
 * Caso contrário, veja:
 * - https://www.apache.org/licenses/LICENSE-2.0
 * - https://www.gnu.org/licenses/gpl-3.0.html
 */

require_once __DIR__ . '/../includes/cabecalho.php';
?>
<a class="skip-link" href="#conteudo-manual">Pular para o conteúdo</a>

<section class="card" id="conteudo-manual" aria-labelledby="titulo-manual">
  <h1 id="titulo-manual">📘 Manual & FAQ - Prancha Web (guia passo a passo para todos)</h1>
  <p>
    Este manual foi escrito em <strong>linguagem simples</strong>, pensando em quem é leigo, idoso ou está começando.
    Você vai entender <strong>o que cada página faz</strong>, <strong>como usar</strong> e <strong>como organizar</strong>
    seu conteúdo para a sua família, escola ou clínica.
  </p>

  <nav aria-label="Sumário" class="campo" style="margin-top:12px;">
    <strong>Sumário rápido</strong>
    <ul class="ml-8" style="margin-top:6px;">
      <li><a href="#visao-geral">Visão geral (o que é e como funciona)</a></li>
      <li><a href="#icones">Legenda dos ícones e do botão “⋮”</a></li>

      <li><a href="#pag-inicio">📍 Página Início (Dashboard)</a></li>

      <li><a href="#pag-grupos-cartoes">🏷️ Grupos de Cartões - por temas (ex.: “Material escolar”, “Alimentação”)</a></li>
      <li><a href="#pag-cartoes">🖼️ Cartões - criar, editar, excluir e importar da ARASAAC</a></li>

      <li><a href="#pag-grupos-pranchas">🗂️ Grupos de Pranchas - por pessoa/filho/paciente ou por atividade</a></li>
      <li><a href="#pag-pranchas">📋 Pranchas - montar sequência, ordenar, compartilhar</a></li>
      <li><a href="#pag-visualizar-prancha">👁️ Visualizar Prancha - usar “Falar” e “Falar Tudo”</a></li>

      <li><a href="#pag-bateria">🔋 Bateria Social - objetivo, níveis (0-5) e exemplos práticos</a></li>
      <li><a href="#pag-config">⚙️ Configurações - Voz (TTS), tema e preferências</a></li>

      <li><a href="#acessibilidade">♿ Acessibilidade - navegação confortável</a></li>
      <li><a href="#faq">❓ FAQ - perguntas e respostas</a></li>
      <li><a href="#privacidade">🔒 Privacidade e dados</a></li>
    </ul>
  </nav>
</section>

<section class="card" id="visao-geral" aria-labelledby="h2-visao">
  <h2 id="h2-visao">🔎 Visão geral (o que é e como funciona)</h2>
  <p>
    O <strong>Prancha Web</strong> ajuda a criar <em>cartões</em> (imagem + título) e organizá-los em
    <em>pranchas</em> (conjuntos ordenados). As pranchas facilitam a <strong>Comunicação Alternativa e Aumentativa (CAA)</strong>,
    rotinas visuais e histórias sociais.
  </p>
  <ul class="ml-8">
    <li><strong>Cartões:</strong> a peça básica (imagem + título; pode ter <em>texto alternativo</em> para leitores de tela).</li>
    <li><strong>Grupos de Cartões:</strong> servem para <em>organizar por tema</em> (ex.: “Material escolar”, “Alimentação”).</li>
    <li><strong>Pranchas:</strong> conjuntos de cartões na ordem que você define (sequência de uso).</li>
    <li><strong>Grupos de Pranchas:</strong> organização por <em>pessoa</em> (filho/paciente) ou por <em>atividade</em> (passeio, higiene, escola).</li>
  </ul>
  <div class="alert" role="note">
    <strong>Sobre áudio/TTS:</strong> o sistema <strong>não envia áudio</strong> e <strong>não fala ao clicar em cartões</strong>.
    A leitura em voz alta (“falar”) funciona somente pelos botões <em>Falar</em>, <em>Falar Tudo</em> e <em>Testar Voz</em>, usando as vozes do seu navegador.
  </div>
</section>

<section class="card" id="icones" aria-labelledby="h2-icones">
  <h2 id="h2-icones">🧭 Legenda dos ícones e do botão “⋮” (menu de ações)</h2>
  <ul class="ml-8">
    <li><strong>⋮</strong> (três pontinhos): abre o menu de ações do item (prancha, grupo, cartão).</li>
    <li><strong>📝 Editar:</strong> altera nome, imagem, descrição ou seleção de cartões.</li>
    <li><strong>🗑️ Excluir:</strong> apaga o item (com confirmação). <strong>Atenção:</strong> é permanente.</li>
    <li><strong>👁️ Visualizar:</strong> abre a prancha para uso (ler um cartão ou todos).</li>
    <li><strong>🗣️ Falar Tudo:</strong> lê em voz alta todos os cartões da prancha em sequência.</li>
    <li><strong>🗣️ Falar:</strong> lê apenas aquele cartão (quando disponível).</li>
  </ul>
</section>

<section class="card" id="pag-inicio" aria-labelledby="h2-inicio">
  <h2 id="h2-inicio">📍 Página Início (Dashboard)</h2>
  <p><strong>Objetivo:</strong> ser o ponto de partida, com atalhos principais.</p>
  <p><strong>Quando usar:</strong> ao entrar no sistema; para ir rapidamente a Cartões, Pranchas, Bateria e Configurações.</p>
  <div class="campo">
    <strong>Como usar:</strong>
    <ol class="ml-8">
      <li>No topo, o <strong>📖 Menu</strong> leva às áreas principais.</li>
      <li>À direita:
        <ul>
          <li><strong>🌓</strong> alterna o tema (claro/escuro).</li>
          <li><strong>❓</strong> abre este Manual.</li>
          <li><strong>🚪</strong> encerra a sessão.</li>
        </ul>
      </li>
    </ol>
  </div>
</section>

<section class="card" id="pag-grupos-cartoes" aria-labelledby="h2-grupos-cartoes">
  <h2 id="h2-grupos-cartoes">🏷️ Grupos de Cartões - organize por temas</h2>
  <p><strong>Objetivo:</strong> facilitar encontrar e manter cartões relacionados.</p>
  <p><strong>Ideias de organização:</strong></p>
  <ul class="ml-8">
    <li><strong>Escola:</strong> “Material escolar”, “Rotina de aula”, “Recreio”.</li>
    <li><strong>Casa:</strong> “Alimentação”, “Higiene”, “Tarefas do dia”.</li>
    <li><strong>Clínica:</strong> “Solicitações”, “Emoções”, “Atividades terapêuticas”.</li>
  </ul>
  <div class="alert"><strong>Dica:</strong> sem grupo, não é possível salvar cartões. Crie um grupo primeiro.</div>

  <h3>Como criar um grupo de cartões</h3>
  <ol class="ml-8">
    <li>Abra <strong>📖 Menu → Cartões</strong>.</li>
    <li>Clique em <strong>Criar grupo de cartões</strong>, dê um nome simples e <strong>Salvar</strong>.</li>
  </ol>

  <h3>Editar/Excluir um grupo (menu “⋮”) - apenas administradores</h3>
  <ol class="ml-8">
    <li>Na lista de grupos, clique no botão <strong>“⋮”</strong> do grupo desejado.</li>
    <li>Escolha <strong>📝 Editar</strong> ou <strong>🗑️ Excluir</strong> e confirme quando solicitado.</li>
  </ol>
</section>

<section class="card" id="pag-cartoes" aria-labelledby="h2-cartoes">
  <h2 id="h2-cartoes">🖼️ Cartões - criar, editar, excluir e importar imagens</h2>
  <p><strong>Objetivo:</strong> criar os itens básicos (imagem + título) que entram nas pranchas.</p>

  <h3>Como criar um cartão</h3>
  <ol class="ml-8">
    <li>Em <strong>📖 Menu → Cartões</strong>, clique em <strong>Criar cartão</strong>.</li>
    <li>Preencha:
      <ul>
        <li><strong>Título</strong> (ex.: “Beber água”).</li>
        <li><strong>Imagem</strong>:
          <ul>
            <li><em>Enviar</em> do computador (PNG/JPG/WEBP), ou</li>
            <li><em>Buscar no ARASAAC</em> e clicar em <strong>Importar</strong> para trazer a imagem automaticamente.</li>
          </ul>
        </li>
        <li><strong>Grupo</strong> (obrigatório).</li>
        <li><strong>Texto alternativo</strong> (opcional; ajuda leitores de tela e descreve a imagem).</li>
      </ul>
    </li>
    <li>Clique em <strong>Salvar</strong>.</li>
  </ol>

  <h3>Como importar do ARASAAC (passo a passo)</h3>
  <ol class="ml-8">
    <li>Clique em <strong>Buscar no ARASAAC</strong>.</li>
    <li>Digite uma palavra simples (ex.: “água”, “banho”, “comer”) e pressione <strong>Enter</strong>.</li>
    <li>Nos resultados, clique em <strong>Importar</strong> na imagem escolhida (o sistema preenche o campo de imagem).</li>
    <li>Finalize clicando em <strong>Salvar</strong>.</li>
  </ol>
  <p class="help">Se a busca falhar, verifique a internet, tente outra palavra ou envie uma imagem do computador.</p>

  <h3>Editar um cartão (menu “⋮”) - apenas administradores</h3>
  <ol class="ml-8">
    <li>Na lista, clique no <strong>“⋮”</strong> do cartão desejado.</li>
    <li>Escolha <strong>📝 Editar</strong>, altere os campos e <strong>Salvar</strong>.</li>
  </ol>

  <h3>Excluir um cartão (menu “⋮”) - apenas administradores</h3>
  <ol class="ml-8">
    <li>No cartão desejado, clique no <strong>“⋮”</strong>.</li>
    <li>Escolha <strong>🗑️ Excluir</strong> e confirme. <em>Lembre-se:</em> é permanente.</li>
  </ol>
</section>

<section class="card" id="pag-grupos-pranchas" aria-labelledby="h2-grupos-pranchas">
  <h2 id="h2-grupos-pranchas">🗂️ Grupos de Pranchas - por pessoa (filho/paciente) ou por atividade</h2>
  <p><strong>Objetivo:</strong> manter as pranchas organizadas por <em>quem</em> vai usar ou por <em>situação</em>.</p>
  <p><strong>Ideias de organização (exemplos reais):</strong></p>
  <ul class="ml-8">
    <li><strong>Família com vários filhos:</strong> um grupo por filho (ex.: “João”, “Ana”). Dentro de cada grupo, pranchas específicas para cada um.</li>
    <li><strong>Pais com um único filho:</strong> grupos por contexto (ex.: “Passeios”, “Higiene”, “Rotina de estudos”).</li>
    <li><strong>Clínica/Escola:</strong> um grupo por paciente/aluno. Assim, cada pessoa tem pranchas personalizadas.</li>
  </ul>

  <h3>Como criar um grupo de pranchas</h3>
  <ol class="ml-8">
    <li>Abra <strong>📖 Menu → Pranchas</strong>.</li>
    <li>Clique em <strong>Criar grupo de pranchas</strong>, dê um nome e <strong>Salvar</strong>.</li>
  </ol>

  <h3>Editar/Excluir um grupo de pranchas (menu “⋮”) - apenas administradores</h3>
  <ol class="ml-8">
    <li>Na lista de grupos, clique no <strong>“⋮”</strong> do grupo desejado.</li>
    <li>Escolha <strong>📝 Editar grupo</strong> ou <strong>🗑️ Excluir grupo</strong> e confirme quando solicitado.</li>
  </ol>
</section>

<section class="card" id="pag-pranchas" aria-labelledby="h2-pranchas">
  <h2 id="h2-pranchas">📋 Pranchas - montar, ordenar, compartilhar</h2>
  <p><strong>Objetivo:</strong> juntar os cartões necessários em uma sequência clara (ordem) para uso diário.</p>

  <h3>Como criar uma prancha</h3>
  <ol class="ml-8">
    <li>Em <strong>📖 Menu → Pranchas</strong>, clique em <strong>Criar prancha</strong>.</li>
    <li>Preencha:
      <ul>
        <li><strong>Nome</strong> e, se quiser, <strong>descrição</strong> (ex.: “Rotina da manhã”).</li>
        <li><strong>Grupo de pranchas</strong> (obrigatório).</li>
        <li><strong>Selecione os cartões</strong> que a prancha terá:
          <ul>
            <li>Cada clique <strong>marca</strong> o cartão e mostra um número de <strong>ordem</strong>.</li>
            <li>Para mudar a ordem, desmarque e marque de novo, ou use os controles disponíveis de ordenação.</li>
          </ul>
        </li>
        <li><strong>Compartilhar com usuários</strong> (opcional; para administradores):
          <ul><li>Marque quem poderá ver/usar a prancha.</li></ul>
        </li>
      </ul>
    </li>
    <li>Clique em <strong>Salvar</strong>.</li>
  </ol>

  <h3>Ações da prancha (na lista)</h3>
  <ul class="ml-8">
    <li><strong>🔎 Abrir:</strong> entra na tela de uso da prancha.</li>
    <li><strong>🗣️ Falar Tudo:</strong> lê todos os cartões da prancha em sequência.</li>
    <li><strong>⋮</strong> (apenas administradores):
      <ul>
        <li><strong>📝 Editar</strong> - alterar nome/descrição/cartões/usuários.</li>
        <li><strong>🗑️ Excluir</strong> - apaga a prancha (confirmação antes).</li>
      </ul>
    </li>
  </ul>
</section>

<section class="card" id="pag-visualizar-prancha" aria-labelledby="h2-visualizar">
  <h2 id="h2-visualizar">👁️ Visualizar Prancha - usar “Falar” e “Falar Tudo”</h2>
  <p><strong>Objetivo:</strong> usar a prancha pronta no dia a dia, lendo em voz alta conforme necessário.</p>
  <ul class="ml-8">
    <li><strong>Falar Tudo:</strong> lê a sequência completa de cartões.</li>
    <li><strong>Falar (no cartão):</strong> lê apenas o texto daquele cartão.</li>
    <li><strong>Importante:</strong> <em>clicar na imagem do cartão não fala</em>. Use sempre os botões de fala.</li>
  </ul>
  <p class="help">Dica: se a voz não sair, confira em <strong>Configurações</strong> se há voz escolhida e ajuste velocidade/tom/volume.</p>
</section>

<section class="card" id="pag-bateria" aria-labelledby="h2-bateria">
  <h2 id="h2-bateria">🔋 Bateria Social - objetivo, níveis (0-5) e exemplos práticos</h2>
  <p>
    A <strong>Bateria Social</strong> é um jeito simples de <em>comunicar</em> quanta energia a pessoa tem para
    interações naquele momento. Não é teste psicológico; é um <strong>termômetro</strong> para ajudar a planejar o dia.
  </p>

  <h3>Como registrar</h3>
  <ol class="ml-8">
    <li>Abra <strong>📖 Menu → Bateria</strong>.</li>
    <li>Escolha um número de <strong>0 a 5</strong>. O sistema salva e mostra uma barra colorida.</li>
  </ol>

  <h3>O que cada nível representa (com exemplos)</h3>
  <ul class="ml-8">
    <li><strong>0 - Esgotado:</strong> preciso de silêncio/pausa; evitar interações. <em>Ex.:</em> momento para descanso total.</li>
    <li><strong>1 - Muito baixo:</strong> tolero o mínimo (sim/não). <em>Ex.:</em> respostas curtas, sem conversas longas.</li>
    <li><strong>2 - Baixo:</strong> tarefas simples com pausas. <em>Ex.:</em> usar prancha para pedir algo específico.</li>
    <li><strong>3 - Neutro:</strong> rotinas sem muita exigência social. <em>Ex.:</em> atividades da casa/escola planejadas.</li>
    <li><strong>4 - Bom:</strong> disposição para conversar/participar. <em>Ex.:</em> hora boa para atividades em conjunto.</li>
    <li><strong>5 - Cheio:</strong> muita energia social. <em>Ex.:</em> melhor momento para passeios/encontros.</li>
  </ul>

  <h3>Boas práticas</h3>
  <ul class="ml-8">
    <li><strong>Respeite o nível:</strong> ele comunica limites e necessidades.</li>
    <li><strong>Reavalie durante o dia:</strong> a bateria muda com cansaço, barulho, atividades.</li>
    <li><strong>Admin:</strong> no painel administrativo, dá para ver o nível de usuários e planejar melhor as demandas.</li>
  </ul>
</section>

<section class="card" id="pag-config" aria-labelledby="h2-config">
  <h2 id="h2-config">⚙️ Configurações - Voz (TTS), tema e preferências</h2>
  <p><strong>Objetivo:</strong> escolher a voz do dispositivo e ajustar a leitura (TTS), além de tema e fonte.</p>

  <h3>Voz e leitura em voz alta (TTS)</h3>
  <ol class="ml-8">
    <li>Abra <strong>📖 Menu → Configurações</strong>.</li>
    <li>Em <strong>Preferências de voz</strong>:
      <ul>
        <li>Escolha a <strong>voz</strong> disponível no seu navegador/dispositivo.</li>
        <li>Ajuste <strong>velocidade</strong>, <strong>tom</strong> e <strong>volume</strong>.</li>
        <li>Use <strong>“Testar Voz”</strong> para ouvir antes de salvar.</li>
      </ul>
    </li>
  </ol>
  <div class="alert">
    <strong>Importante:</strong> o sistema <em>não</em> fala ao clicar no cartão. Use os botões <em>Falar</em>/<em>Falar Tudo</em>.
  </div>

  <h3>Tema claro/escuro e tamanho da fonte</h3>
  <ul class="ml-8">
    <li>O botão <strong>🌓</strong> (topo) alterna o tema e sua escolha fica gravada.</li>
    <li>Ajuste o tamanho base da fonte para conforto na leitura.</li>
  </ul>
</section>

<section class="card" id="acessibilidade" aria-labelledby="h2-acess">
  <h2 id="h2-acess">♿ Acessibilidade - navegação confortável</h2>
  <ul class="ml-8">
    <li>Todos os botões têm <strong>foco visível</strong> (contorno quando selecionados).</li>
    <li>As <strong>tabelas e listas</strong> comportam-se bem no celular (rolagem horizontal quando necessário).</li>
    <li>As <strong>cores</strong> e o <strong>contraste</strong> foram pensados para leitura clara.</li>
  </ul>
</section>

<section class="card" id="faq" aria-labelledby="h2-faq">
  <h2 id="h2-faq">❓ FAQ - perguntas frequentes</h2>

  <details class="campo">
    <summary class="botao-acao">Não consigo criar um cartão</summary>
    <div class="alert" style="margin-top:8px;">
      Provavelmente você <strong>ainda não criou um grupo de cartões</strong>. Vá em <em>Cartões</em> → <strong>Criar grupo de cartões</strong>.
    </div>
  </details>

  <details class="campo">
    <summary class="botao-acao">Não consigo criar uma prancha</summary>
    <div class="alert" style="margin-top:8px;">
      Primeiro crie um <strong>grupo de pranchas</strong>. Na criação da prancha, selecione os <strong>cartões</strong> e defina a <strong>ordem</strong>.
    </div>
  </details>

  <details class="campo">
    <summary class="botao-acao">A imagem não sobe ou dá erro</summary>
    <div class="alert" style="margin-top:8px;">
      Envie PNG/JPG/WEBP ou use a <strong>ARASAAC</strong> (buscar e <em>Importar</em>). Se falhar, verifique a internet.
    </div>
  </details>

  <details class="campo">
    <summary class="botao-acao">Importei da ARASAAC, mas não aparece</summary>
    <div class="alert" style="margin-top:8px;">
      Tente novamente com outra palavra ou envie uma imagem do computador.
    </div>
  </details>

  <details class="campo">
    <summary class="botao-acao">Os cartões ficaram fora de ordem</summary>
    <div class="alert" style="margin-top:8px;">
      Na <strong>edição da prancha</strong>, clique para remarcar os cartões e ajustar a <strong>ordem</strong>.
    </div>
  </details>

  <details class="campo">
    <summary class="botao-acao">Onde ficam Editar/Excluir?</summary>
    <div class="alert" style="margin-top:8px;">
      Nas listas (grupos/pranchas/cartões), clique no botão <strong>“⋮”</strong> do item para abrir o menu de <strong>Editar</strong> e <strong>Excluir</strong>.
    </div>
  </details>

  <details class="campo">
    <summary class="botao-acao">Esqueci a senha</summary>
    <div class="alert" style="margin-top:8px;">
      Procure o <strong>administrador</strong> do sistema para redefinir sua senha.
    </div>
  </details>

  <details class="campo">
    <summary class="botao-acao">A voz não funciona no meu celular</summary>
    <div class="alert" style="margin-top:8px;">
      Verifique se o navegador do seu celular tem <strong>vozes TTS</strong> em português e teste novamente em <em>Configurações → Testar Voz</em>.
    </div>
  </details>
</section>

<section class="card" id="privacidade" aria-labelledby="h2-priv">
  <h2 id="h2-priv">🔒 Privacidade e dados</h2>
  <ul class="ml-8">
    <li>As pranchas e cartões são armazenados no sistema para seu uso.</li>
    <li>O sistema não grava áudios e não envia arquivos de som.</li>
    <li>Somente administradores podem excluir pranchas e gerenciar compartilhamentos com usuários.</li>
  </ul>
</section>

<?php
require_once __DIR__ . '/../includes/rodape.php';
