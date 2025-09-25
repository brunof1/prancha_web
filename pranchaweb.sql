-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: pranchaweb.mysql.dbaas.com.br
-- Generation Time: 25-Set-2025 às 14:26
-- Versão do servidor: 5.7.32-35-log
-- PHP Version: 5.6.40-0+deb8u12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pranchaweb`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `cartoes`
--

CREATE TABLE `cartoes` (
  `id` int(11) NOT NULL,
  `titulo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `imagem` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `som` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `texto_alternativo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_grupo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `cartoes`
--

INSERT INTO `cartoes` (`id`, `titulo`, `imagem`, `som`, `texto_alternativo`, `id_grupo`) VALUES
(17, 'Estou com Fome', 'arasaac_35559_pt_68d4394cdfe1f.png', NULL, 'Estou com Fome', 7),
(18, 'Quero comer massa', 'arasaac_2455_pt_68d4397cc5a24.png', NULL, 'Quero comer massa', 7),
(19, 'Quero comer salada', 'arasaac_2377_pt_68d439bef2823.png', NULL, 'Quero comer salada', 7),
(20, 'Quero fazer exercício', 'arasaac_30606_pt_68d439dcc6f55.png', NULL, 'Quero fazer exercício', 8),
(21, 'Caderno', 'arasaac_2359_pt_68d43a0e45469.png', NULL, 'Caderno', 10),
(22, 'Caneta', 'arasaac_2282_pt_68d43a2b80e10.png', NULL, 'Caneta', 10),
(23, 'The book is', 'arasaac_2450_en_68d43ac88e57d.png', NULL, 'The book is', 10),
(24, 'On the table', 'arasaac_3129_en_68d43b24d8931.png', NULL, 'On the table', 10),
(25, 'Preciso de ajuda', 'arasaac_7171_pt_68d43cd424881.png', NULL, 'Preciso de ajuda', 12),
(26, 'Não preciso de ajuda', 'arasaac_31664_pt_68d43cf576f29.png', NULL, 'Não preciso de ajuda', 12),
(27, 'Posso te ajudar?', 'arasaac_16125_pt_68d43d12b1967.png', NULL, 'Posso te ajudar?', 12),
(28, 'Lápis', 'arasaac_2440_pt_68d43d38a31a2.png', NULL, 'Lápis', 10),
(29, 'Borracha', 'arasaac_2409_pt_68d43d64da46f.png', NULL, 'Borracha', 10),
(30, 'Papel', 'arasaac_8349_pt_68d43db112377.png', NULL, 'Papel', 10),
(31, 'Pedra', 'arasaac_6594_pt_68d43dda6f4b8.png', NULL, 'Pedra', 13),
(32, 'Tesoura', 'arasaac_2591_pt_68d43dfb10070.png', NULL, 'Tesoura', 10),
(33, 'Vamos jogar pedra, papel e tesoura?', 'arasaac_36431_pt_68d43eaa89c7e.png', NULL, 'Vamos jogar pedra, papel e tesoura?', 15);

-- --------------------------------------------------------

--
-- Estrutura da tabela `grupos_cartoes`
--

CREATE TABLE `grupos_cartoes` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `grupos_cartoes`
--

INSERT INTO `grupos_cartoes` (`id`, `nome`) VALUES
(7, 'Fome'),
(8, 'Academia'),
(10, 'Escola'),
(11, 'Tédio'),
(12, 'Ajuda'),
(13, 'Natureza'),
(14, 'Jogos'),
(15, 'Perguntas');

-- --------------------------------------------------------

--
-- Estrutura da tabela `grupos_pranchas`
--

CREATE TABLE `grupos_pranchas` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `grupos_pranchas`
--

INSERT INTO `grupos_pranchas` (`id`, `nome`) VALUES
(24, 'Quando'),
(25, 'Fome'),
(26, 'Zoeira'),
(27, 'Perguntas'),
(28, 'Jogos');

-- --------------------------------------------------------

--
-- Estrutura da tabela `pranchas`
--

CREATE TABLE `pranchas` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `descricao` text COLLATE utf8mb4_unicode_ci,
  `id_grupo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `pranchas`
--

INSERT INTO `pranchas` (`id`, `nome`, `descricao`, `id_grupo`) VALUES
(25, 'The book is on the table', 'The book is on the table', 26),
(26, 'Quando está com fome', 'Quando está com fome', 25),
(27, 'Quando quer comer massa', 'Quando quer comer massa', 25),
(28, 'Quando quer fazer exercício físico', 'Quando quer fazer exercício físico', 24),
(29, 'Vamos jogar pedra, papel e tesoura?', 'Vamos jogar pedra, papel e tesoura?', 27),
(30, 'Pedra, papel e tesoura', 'Pedra, papel e tesoura', 28);

-- --------------------------------------------------------

--
-- Estrutura da tabela `pranchas_cartoes`
--

CREATE TABLE `pranchas_cartoes` (
  `id` int(11) NOT NULL,
  `id_prancha` int(11) NOT NULL,
  `id_cartao` int(11) NOT NULL,
  `ordem` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `pranchas_cartoes`
--

INSERT INTO `pranchas_cartoes` (`id`, `id_prancha`, `id_cartao`, `ordem`) VALUES
(147, 25, 23, 1),
(148, 25, 24, 2),
(149, 26, 17, 1),
(150, 27, 17, 1),
(151, 27, 18, 2),
(152, 28, 20, 1),
(154, 29, 33, 1),
(155, 30, 31, 1),
(156, 30, 30, 2),
(157, 30, 32, 3);

-- --------------------------------------------------------

--
-- Estrutura da tabela `pranchas_usuarios`
--

CREATE TABLE `pranchas_usuarios` (
  `id` int(11) NOT NULL,
  `id_prancha` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `pranchas_usuarios`
--

INSERT INTO `pranchas_usuarios` (`id`, `id_prancha`, `id_usuario`) VALUES
(83, 25, 4),
(84, 26, 4),
(85, 27, 4),
(86, 28, 4),
(88, 29, 4),
(89, 30, 4);

-- --------------------------------------------------------

--
-- Estrutura da tabela `preferencias_usuarios`
--

CREATE TABLE `preferencias_usuarios` (
  `id` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `voz_uri` varchar(255) DEFAULT NULL,
  `tts_rate` decimal(3,2) NOT NULL DEFAULT '1.00',
  `tts_pitch` decimal(3,2) NOT NULL DEFAULT '1.00',
  `tts_volume` decimal(3,2) NOT NULL DEFAULT '1.00',
  `font_base_px` tinyint(3) UNSIGNED NOT NULL DEFAULT '16',
  `falar_ao_clicar` tinyint(1) NOT NULL DEFAULT '0',
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Extraindo dados da tabela `preferencias_usuarios`
--

INSERT INTO `preferencias_usuarios` (`id`, `id_usuario`, `voz_uri`, `tts_rate`, `tts_pitch`, `tts_volume`, `font_base_px`, `falar_ao_clicar`, `updated_at`) VALUES
(1, 3, NULL, 1.00, 1.00, 1.00, 16, 0, '2025-09-13 03:01:28'),
(5, 1, NULL, 1.00, 1.00, 1.00, 20, 0, '2025-09-25 13:26:16'),
(8, 2, NULL, 1.00, 1.00, 1.00, 16, 0, '2025-09-17 16:02:00'),
(9, 4, NULL, 1.00, 1.00, 1.00, 16, 0, '2025-09-18 14:39:51');

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `senha` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tipo` enum('admin','user') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user' COMMENT 'admin=acesso total; user=restrito',
  `tema_preferido` enum('light','dark') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'light',
  `bateria_social` tinyint(3) UNSIGNED NOT NULL DEFAULT '3',
  `bateria_atualizado_em` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `tipo`, `tema_preferido`, `bateria_social`, `bateria_atualizado_em`) VALUES
(1, 'Bruno', 'bhpdownloads@gmail.com', '$2y$10$3VDOBTm0E/rmcUlXB.sAW.L7UWEoUZ/6NAFFjJWh5IBwNT9jvu1AO', 'admin', 'light', 4, '2025-09-24 14:25:52'),
(2, 'Tester', 'teste@pranchaweb.online', '$2y$10$3VDOBTm0E/rmcUlXB.sAW.L7UWEoUZ/6NAFFjJWh5IBwNT9jvu1AO', 'admin', 'dark', 1, '2025-09-24 14:24:59'),
(3, 'Bárbara', 'babi.crespa@hotmail.com', '$2y$10$3VDOBTm0E/rmcUlXB.sAW.L7UWEoUZ/6NAFFjJWh5IBwNT9jvu1AO', 'admin', 'light', 2, '2025-09-24 18:14:37'),
(4, 'Tester User', 'testeuser@pranchaweb.online', '$2y$10$Nr9rkbj/PTCoPrkx4LklUe1Q9gk7p8mmVBWA3zyfQi0NwRCvzQzVq', 'user', 'light', 0, '2025-09-24 14:25:09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cartoes`
--
ALTER TABLE `cartoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_grupo` (`id_grupo`);

--
-- Indexes for table `grupos_cartoes`
--
ALTER TABLE `grupos_cartoes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `grupos_pranchas`
--
ALTER TABLE `grupos_pranchas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pranchas`
--
ALTER TABLE `pranchas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_grupo_prancha` (`id_grupo`);

--
-- Indexes for table `pranchas_cartoes`
--
ALTER TABLE `pranchas_cartoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_cartao` (`id_cartao`),
  ADD KEY `idx_ordem` (`id_prancha`,`ordem`);

--
-- Indexes for table `pranchas_usuarios`
--
ALTER TABLE `pranchas_usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_prancha_usuario` (`id_prancha`,`id_usuario`),
  ADD KEY `idx_usuario` (`id_usuario`);

--
-- Indexes for table `preferencias_usuarios`
--
ALTER TABLE `preferencias_usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_usuario` (`id_usuario`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`(191)),
  ADD KEY `idx_bateria_atualizado_em` (`bateria_atualizado_em`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cartoes`
--
ALTER TABLE `cartoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `grupos_cartoes`
--
ALTER TABLE `grupos_cartoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `grupos_pranchas`
--
ALTER TABLE `grupos_pranchas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `pranchas`
--
ALTER TABLE `pranchas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `pranchas_cartoes`
--
ALTER TABLE `pranchas_cartoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=158;

--
-- AUTO_INCREMENT for table `pranchas_usuarios`
--
ALTER TABLE `pranchas_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `preferencias_usuarios`
--
ALTER TABLE `preferencias_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `cartoes`
--
ALTER TABLE `cartoes`
  ADD CONSTRAINT `fk_grupo` FOREIGN KEY (`id_grupo`) REFERENCES `grupos_cartoes` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `pranchas`
--
ALTER TABLE `pranchas`
  ADD CONSTRAINT `fk_grupo_prancha` FOREIGN KEY (`id_grupo`) REFERENCES `grupos_pranchas` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `pranchas_cartoes`
--
ALTER TABLE `pranchas_cartoes`
  ADD CONSTRAINT `pranchas_cartoes_ibfk_1` FOREIGN KEY (`id_prancha`) REFERENCES `pranchas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pranchas_cartoes_ibfk_2` FOREIGN KEY (`id_cartao`) REFERENCES `cartoes` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `pranchas_usuarios`
--
ALTER TABLE `pranchas_usuarios`
  ADD CONSTRAINT `fk_pu_prancha` FOREIGN KEY (`id_prancha`) REFERENCES `pranchas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_pu_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;

--
-- Limitadores para a tabela `preferencias_usuarios`
--
ALTER TABLE `preferencias_usuarios`
  ADD CONSTRAINT `fk_pref_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
