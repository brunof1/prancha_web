-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: pranchaweb.mysql.dbaas.com.br
-- Generation Time: 18-Set-2025 às 21:02
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
(6, 'Estou com fome', 'ter_fome.png', NULL, 'Estou com fome', 7),
(7, 'Estou Satisfeito', 'arasaac_38801_pt_68cb761f87319.png', NULL, 'Estou Satisfeito', 7),
(8, 'Quero fazer exercício', '68cb62f5af10a_fazer_exerc__cio.png', NULL, 'Quero fazer exercicio', 8),
(10, 'Caderno', 'arasaac_2359_pt_68cb76647ac67.png', NULL, 'Caderno', 10),
(11, 'Caneta', 'arasaac_2282_pt_68cc680920f27.png', NULL, 'Caneta', 10);

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
(10, 'Escola');

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
(20, 'Quando está com ...'),
(21, 'Teste'),
(22, 'Revolução Farroupilha');

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
(16, 'Quando está com Fome ou Satisfeito', 'Ser rápido no pedido', 20),
(17, 'Tédio', 'Tédio', 20),
(18, 'Teste', 'Teste', 21),
(19, 'Bah Tchê', 'Bah!', 22);

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
(70, 18, 10, 1),
(79, 17, 10, 1),
(80, 17, 6, 2),
(81, 17, 7, 3),
(82, 17, 8, 4),
(83, 16, 6, 1),
(84, 16, 7, 2),
(85, 19, 10, 1),
(86, 19, 6, 2),
(87, 19, 7, 3),
(88, 19, 8, 4);

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
(45, 16, 3),
(46, 16, 4),
(43, 17, 3),
(44, 17, 4),
(37, 18, 3),
(38, 18, 4),
(47, 19, 3),
(48, 19, 4);

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
(5, 1, NULL, 1.00, 1.00, 1.00, 16, 0, '2025-09-18 19:21:17'),
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
  `tema_preferido` enum('light','dark') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'light'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `tipo`, `tema_preferido`) VALUES
(1, 'Bruno', 'bhpdownloads@gmail.com', '$2y$10$3VDOBTm0E/rmcUlXB.sAW.L7UWEoUZ/6NAFFjJWh5IBwNT9jvu1AO', 'admin', 'light'),
(2, 'Tester', 'teste@pranchaweb.online', '$2y$10$3VDOBTm0E/rmcUlXB.sAW.L7UWEoUZ/6NAFFjJWh5IBwNT9jvu1AO', 'admin', 'light'),
(3, 'Bárbara', 'babi.crespa@hotmail.com', '$2y$10$3VDOBTm0E/rmcUlXB.sAW.L7UWEoUZ/6NAFFjJWh5IBwNT9jvu1AO', 'user', 'light'),
(4, 'Tester User', 'testeuser@pranchaweb.online', '$2y$10$Nr9rkbj/PTCoPrkx4LklUe1Q9gk7p8mmVBWA3zyfQi0NwRCvzQzVq', 'user', 'light');

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
  ADD UNIQUE KEY `email` (`email`(191));

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cartoes`
--
ALTER TABLE `cartoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `grupos_cartoes`
--
ALTER TABLE `grupos_cartoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `grupos_pranchas`
--
ALTER TABLE `grupos_pranchas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `pranchas`
--
ALTER TABLE `pranchas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `pranchas_cartoes`
--
ALTER TABLE `pranchas_cartoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=89;

--
-- AUTO_INCREMENT for table `pranchas_usuarios`
--
ALTER TABLE `pranchas_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `preferencias_usuarios`
--
ALTER TABLE `preferencias_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

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
