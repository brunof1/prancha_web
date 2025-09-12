-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: pranchaweb.mysql.dbaas.com.br
-- Generation Time: 11-Set-2025 às 21:53
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
  `titulo` varchar(255) NOT NULL,
  `imagem` varchar(255) DEFAULT NULL,
  `som` varchar(255) DEFAULT NULL,
  `texto_alternativo` varchar(255) DEFAULT NULL,
  `id_grupo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `cartoes`
--

INSERT INTO `cartoes` (`id`, `titulo`, `imagem`, `som`, `texto_alternativo`, `id_grupo`) VALUES
(3, 'Estou com Fome', 'ter_fome.png', NULL, 'Estou com Fome', 6),
(4, 'Estou Satisfeito', 'satisfeito.png', NULL, 'Estou Satisfeito', 6);

-- --------------------------------------------------------

--
-- Estrutura da tabela `grupos_cartoes`
--

CREATE TABLE `grupos_cartoes` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `grupos_cartoes`
--

INSERT INTO `grupos_cartoes` (`id`, `nome`) VALUES
(6, 'Fome');

-- --------------------------------------------------------

--
-- Estrutura da tabela `grupos_pranchas`
--

CREATE TABLE `grupos_pranchas` (
  `id` int(11) NOT NULL,
  `nome` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `grupos_pranchas`
--

INSERT INTO `grupos_pranchas` (`id`, `nome`) VALUES
(11, 'Fome');

-- --------------------------------------------------------

--
-- Estrutura da tabela `pranchas`
--

CREATE TABLE `pranchas` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `descricao` text,
  `id_grupo` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `pranchas`
--

INSERT INTO `pranchas` (`id`, `nome`, `descricao`, `id_grupo`) VALUES
(5, 'Quando estÃ¡ com Fome', 'Quando estÃ¡ com Fome', 11);

-- --------------------------------------------------------

--
-- Estrutura da tabela `pranchas_cartoes`
--

CREATE TABLE `pranchas_cartoes` (
  `id` int(11) NOT NULL,
  `id_prancha` int(11) NOT NULL,
  `id_cartao` int(11) NOT NULL,
  `ordem` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `pranchas_cartoes`
--

INSERT INTO `pranchas_cartoes` (`id`, `id_prancha`, `id_cartao`, `ordem`) VALUES
(21, 5, 4, 1),
(22, 5, 3, 2);

-- --------------------------------------------------------

--
-- Estrutura da tabela `pranchas_usuarios`
--

CREATE TABLE `pranchas_usuarios` (
  `id` int(11) NOT NULL,
  `id_prancha` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `tema_preferido` enum('light','dark') NOT NULL DEFAULT 'light'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `tipo`, `tema_preferido`) VALUES
(1, 'Bruno Silva', 'bhpdownloads@gmail.com', '$2y$10$3VDOBTm0E/rmcUlXB.sAW.L7UWEoUZ/6NAFFjJWh5IBwNT9jvu1AO', 'admin', 'light'),
(2, 'Tester', 'teste@pranchaweb.online', '$2y$10$3VDOBTm0E/rmcUlXB.sAW.L7UWEoUZ/6NAFFjJWh5IBwNT9jvu1AO', 'admin', 'dark'),
(3, 'Bárbara', 'babi.crespa@hotmail.com', '$2y$10$3VDOBTm0E/rmcUlXB.sAW.L7UWEoUZ/6NAFFjJWh5IBwNT9jvu1AO', 'user', 'light');

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
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cartoes`
--
ALTER TABLE `cartoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `grupos_cartoes`
--
ALTER TABLE `grupos_cartoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `grupos_pranchas`
--
ALTER TABLE `grupos_pranchas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `pranchas`
--
ALTER TABLE `pranchas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pranchas_cartoes`
--
ALTER TABLE `pranchas_cartoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `pranchas_usuarios`
--
ALTER TABLE `pranchas_usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
