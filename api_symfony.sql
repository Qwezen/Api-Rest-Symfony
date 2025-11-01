-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : dim. 26 oct. 2025 à 19:28
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `api_symfony`
--

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `category`
--

INSERT INTO `category` (`id`, `name`) VALUES
(41, 'Strategie'),
(42, 'Strategie'),
(43, 'Point&Click'),
(44, 'Point&Click'),
(45, 'Mmorpg'),
(46, 'Puzzle'),
(47, 'Puzzle'),
(48, 'Action'),
(49, 'Strategie'),
(50, 'Strategie');

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20251010132554', '2025-10-10 15:26:12', 51),
('DoctrineMigrations\\Version20251019103523', '2025-10-19 12:36:17', 49),
('DoctrineMigrations\\Version20251020205252', '2025-10-20 22:53:21', 80),
('DoctrineMigrations\\Version20251020210036', '2025-10-20 23:01:05', 21);

-- --------------------------------------------------------

--
-- Structure de la table `editor`
--

CREATE TABLE `editor` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `editor`
--

INSERT INTO `editor` (`id`, `name`, `country`) VALUES
(41, 'Ledoux', 'France'),
(42, 'Morvan Besson SARL', 'Italie'),
(43, 'Barbe', 'Royaume-Uni'),
(44, 'Normand', 'France'),
(45, 'Pruvost', 'Royaume-Uni'),
(46, 'Jean', 'Canada'),
(47, 'Breton', 'Japon'),
(48, 'Voisin SARL', 'Japon'),
(49, 'Da Silva Colin S.A.R.L.', 'Canada'),
(50, 'Foucher', 'Royaume-Uni');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) NOT NULL,
  `roles` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT '(DC2Type:json)' CHECK (json_valid(`roles`)),
  `password` varchar(255) NOT NULL,
  `subcription_to_newsletter` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `subcription_to_newsletter`) VALUES
(1, 'tintin@gmail.com', '[\"ROLE_ADMIN\"]\r\n', '$2y$13$g724RRq5U4CDGUJycJ2/n.t6qsvp2mXN2bMg6MThtShJbISBSNnHi', 1);

-- --------------------------------------------------------

--
-- Structure de la table `video_game`
--

CREATE TABLE `video_game` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `release_date` datetime DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL,
  `video_game_editor_id` int(11) DEFAULT NULL,
  `cover_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `video_game`
--

INSERT INTO `video_game` (`id`, `title`, `release_date`, `description`, `video_game_editor_id`, `cover_image`) VALUES
(21, 'Nisi eligendi dolor sint.', '2025-11-08 10:32:35', 'Molestias qui nesciunt ut autem quia autem tempora totam perferendis repellat.', 41, '/uploads/covers/assassins_creed.jpg'),
(22, 'Fuga eum iure.', '2025-11-19 21:20:34', 'Iusto dolorem et et praesentium voluptatem voluptas itaque rerum quaerat asperiores rem est.', 48, '/uploads/covers/cyberpunk2077.jpg'),
(23, 'Odio ex tempore.', '2025-11-26 02:01:50', 'Ullam qui et voluptate praesentium quas nobis modi facere fugit velit.', 50, '/uploads/covers/call_of_duty.jpg'),
(24, 'Dolorum nesciunt laboriosam provident.', '2025-10-29 09:50:45', 'Esse architecto quis sit deserunt aut praesentium velit sit.', 49, '/uploads/covers/mario_odyssey.jpg'),
(25, 'Cumque ut quo.', '2025-11-02 10:59:57', 'Ipsum inventore sint officiis eius perspiciatis harum qui quia assumenda.', 49, '/uploads/covers/assassins_creed.jpg'),
(26, 'Sit quo fugiat fugit ullam.', '2025-11-21 13:10:44', 'Placeat assumenda provident vel voluptatibus ipsum porro sint omnis maiores quisquam quia debitis.', 48, '/uploads/covers/cyberpunk2077.jpg'),
(27, 'Sit et dolores cumque.', '2025-11-11 23:50:49', 'Distinctio minus est laboriosam eveniet placeat perspiciatis quos itaque qui et voluptatem architecto.', 49, '/uploads/covers/cyberpunk2077.jpg'),
(28, 'Id rerum temporibus.', '2025-11-26 07:45:17', 'Explicabo et atque dolorem quo dolorem cum eius veritatis facilis asperiores.', 42, '/uploads/covers/assassins_creed.jpg'),
(29, 'Odit sint alias incidunt.', '2025-11-04 00:22:17', 'Nam voluptatem rem cum exercitationem ducimus excepturi reiciendis.', 42, '/uploads/covers/assassins_creed.jpg'),
(30, 'Quidem non et.', '2025-11-16 12:37:56', 'Rerum fuga ea vero libero est assumenda libero reiciendis quo magnam repellat est.', 42, '/uploads/covers/call_of_duty.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `video_game_category`
--

CREATE TABLE `video_game_category` (
  `video_game_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `video_game_category`
--

INSERT INTO `video_game_category` (`video_game_id`, `category_id`) VALUES
(21, 45),
(22, 42),
(23, 43),
(24, 43),
(25, 48),
(26, 47),
(27, 46),
(28, 48),
(29, 47),
(30, 47);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Index pour la table `editor`
--
ALTER TABLE `editor`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_IDENTIFIER_EMAIL` (`email`);

--
-- Index pour la table `video_game`
--
ALTER TABLE `video_game`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_24BC6C509DE127CE` (`video_game_editor_id`);

--
-- Index pour la table `video_game_category`
--
ALTER TABLE `video_game_category`
  ADD PRIMARY KEY (`video_game_id`,`category_id`),
  ADD KEY `IDX_A672CAD716230A8` (`video_game_id`),
  ADD KEY `IDX_A672CAD712469DE2` (`category_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT pour la table `editor`
--
ALTER TABLE `editor`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `video_game`
--
ALTER TABLE `video_game`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `video_game`
--
ALTER TABLE `video_game`
  ADD CONSTRAINT `FK_24BC6C509DE127CE` FOREIGN KEY (`video_game_editor_id`) REFERENCES `editor` (`id`);

--
-- Contraintes pour la table `video_game_category`
--
ALTER TABLE `video_game_category`
  ADD CONSTRAINT `FK_A672CAD712469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_A672CAD716230A8` FOREIGN KEY (`video_game_id`) REFERENCES `video_game` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
