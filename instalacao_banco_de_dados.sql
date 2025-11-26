-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: pranchaweb.mysql.dbaas.com.br
-- Generation Time: 26-Nov-2025 às 16:57
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

-- --------------------------------------------------------

--
-- Estrutura da tabela `grupos_cartoes`
--

CREATE TABLE `grupos_cartoes` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Estrutura da tabela `grupos_pranchas`
--

CREATE TABLE `grupos_pranchas` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- --------------------------------------------------------

--
-- Estrutura da tabela `pranchas_usuarios`
--

CREATE TABLE `pranchas_usuarios` (
  `id` int(11) NOT NULL,
  `id_prancha` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grupos_cartoes`
--
ALTER TABLE `grupos_cartoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `grupos_pranchas`
--
ALTER TABLE `grupos_pranchas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pranchas`
--
ALTER TABLE `pranchas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pranchas_cartoes`
--
ALTER TABLE `pranchas_cartoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pranchas_usuarios`
--
ALTER TABLE `pranchas_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `preferencias_usuarios`
--
ALTER TABLE `preferencias_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
