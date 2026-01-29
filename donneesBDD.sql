--
-- Déchargement des données de la table `reservation`
--

INSERT INTO `reservation` (`id`, `dateHeure_debut`, `dateHeure_fin`, `nom`, `salle_id`) VALUES
(1, '2026-02-28 10:30:00', '2026-02-28 15:00:00', 'Shepard', 1),
(2, '2026-02-08 09:30:00', '2026-02-08 12:30:00', 'Robin', 2),
(3, '2026-03-08 14:30:00', '2026-03-08 16:30:00', 'Moka', 3),
(4, '2026-02-02 09:30:00', '2026-02-02 16:30:00', 'Austin', 4);

-- --------------------------------------------------------


--
-- Déchargement des données de la table `salle`
--

INSERT INTO `salle` (`id`, `libelle`, `type`, `capacite`) VALUES
(2, 'Violette', 'salle de réunion', 12),
(3, 'Rouge', 'salle de réunion', 100),
(4, 'Verte', 'open-space', 50),
(5, 'Bleue', 'bureau', 40),
(6, 'Orange', 'open-space', 41),
(7, 'Rose', 'salle de réunion', 36),
(8, 'Noire', 'open-space', 36);
COMMIT;

