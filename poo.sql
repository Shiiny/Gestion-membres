-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Client :  127.0.0.1
-- Généré le :  Dim 28 Janvier 2018 à 22:29
-- Version du serveur :  5.7.14
-- Version de PHP :  5.6.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `poo`
--

-- --------------------------------------------------------

--
-- Structure de la table `personnages`
--

CREATE TABLE `personnages` (
  `id` int(10) UNSIGNED NOT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `degats` int(10) UNSIGNED NOT NULL DEFAULT '0',
  `level` int(10) UNSIGNED NOT NULL DEFAULT '1',
  `exp` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Contenu de la table `personnages`
--

INSERT INTO `personnages` (`id`, `nom`, `degats`, `level`, `exp`) VALUES
(1, 'Shiny', 25, 3, 0),
(2, 'Naelys', 25, 6, 0);

-- --------------------------------------------------------

--
-- Structure de la table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `level` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `roles`
--

INSERT INTO `roles` (`id`, `name`, `slug`, `level`) VALUES
(1, 'Administrateur', 'admin', 2),
(2, 'Membre', 'member', 1);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `confirmation_token` varchar(60) DEFAULT NULL,
  `confirmed_at` datetime DEFAULT NULL,
  `reset_token` varchar(60) DEFAULT NULL,
  `reset_at` datetime DEFAULT NULL,
  `remember` varchar(250) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `username`, `role_id`, `email`, `password`, `confirmation_token`, `confirmed_at`, `reset_token`, `reset_at`, `remember`) VALUES
(3, 'Shiny', 1, 'contact@web-shiny.fr', '$2y$10$HE3p4fjSotJ7MqpHgOeJlOi8Xu691Aawhw7nfSMUVmUeXPSBEP2ZK', NULL, '2018-01-26 23:52:48', NULL, NULL, 'DF2A9pGtmtk80v1v2K8qHnCVzPeK0KqEC5I1VKztduubR0Bk8gDeOBJpt6W3eQTPI1DHQLXWnxdv7FvLXTpuugK7AqbaXempnZB972Y9fMb9bCmhQecQ9Ghg2a1rRWaEoWECT79M4pUaWIiGZm9rFcCIeWqJr4or24aoTz3038S6DTFFZuisC9OoAK9LRvphXidpHYAqzvcGIdmafCfHX8j8jHK1UJZ7lTGxlMtCurHguQwXu1F8abcdr1'),
(2, 'Naelys', 2, 'anthony.oury94@gmail.com', '$2y$10$CoTEB79R776WOzT5/75pnOBb01R1dj.gqHe4vjeii6uOs/TRqm4NG', NULL, '2018-01-26 22:51:31', NULL, NULL, '2wrnmoOqYkq8a4RsjMFpGN2zpwb6pFrSKUsNyGoNuiDN1PZmv5okjVZRo7eCgEV1ysMQ11xeAsr3fz5EPuo2tmNjR2kZZj16lNxzBpLulA7VEcdRyeiz0KmzreXRKemQqkeLnqPgOMIXkAnUzBKo6dVSg7rJ3KL52THsWoUJVKjT3RkvHkZXtaKFjKDfz29WmlxhWDKB9MW5P8AFjQF5jXa81ZcQveqB8zKuXRVgIAGyWafKUfR88TxlVS');

--
-- Index pour les tables exportées
--

--
-- Index pour la table `personnages`
--
ALTER TABLE `personnages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nom` (`nom`);

--
-- Index pour la table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `personnages`
--
ALTER TABLE `personnages`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT pour la table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
