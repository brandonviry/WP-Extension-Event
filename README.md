# WP Extension Event

**Une solution professionnelle et flexible pour la gestion d'événements sur WordPress.**

![Version](https://img.shields.io/badge/version-1.0.0-blue.svg) ![License](https://img.shields.io/badge/license-Creative%20Commons%20BY--NC-green.svg) ![Author](https://img.shields.io/badge/author-Viry%20Brandon-orange.svg)

---

## 📋 Présentation

**WP Extension Event** est un plugin WordPress conçu pour offrir une liberté totale aux créateurs de sites. Contrairement aux extensions classiques rigides, ce plugin sépare la logique (gestion des données) du design (affichage), vous permettant de construire des interfaces uniques sans toucher au code PHP.

### Fonctionnalités Clés
*   📅 **Gestion complète** : Date, Lieu, Prix, Places, Billetterie externe.
*   🎨 **Design 100% Personnalisable** : Utilisez votre constructeur de page préféré (Elementor, Breakdance, Divi, Gutenberg) pour dessiner vos cartes et vos filtres.
*   🔍 **Filtrage Avancé** : Recherche par mots-clés et filtrage par tags (catégories).
*   🚀 **Performance** : Code léger et optimisé, sans bloatware.

---

## 🛠 Installation

1.  Téléchargez le dossier du plugin `wpextensionevent`.
2.  Déposez-le dans le répertoire `wp-content/plugins/` de votre installation WordPress.
3.  Activez l'extension via le menu **Extensions** de WordPress.
4.  **Important** : Allez dans *Réglages > Permaliens* et cliquez sur "Enregistrer" pour initialiser les routes.

---

## 📖 Documentation

La documentation complète est disponible dans le dossier `docs/` :

*   [📘 Guide d'Utilisation](docs/GUIDE_UTILISATION.md) : Comment créer et gérer vos événements.
*   [🎨 Guide Design & Templating](docs/GUIDE_DESIGN.md) : Comment créer vos cartes sur mesure avec Breakdance/Elementor.
*   [⚙️ Documentation Technique](docs/DOCUMENTATION.md) : Liste exhaustive des shortcodes et hooks.

### Démarrage Rapide
Pour afficher une grille d'événements par défaut :
```shortcode
[display_events]
```

Pour utiliser un template personnalisé (ID 123) :
```shortcode
[display_events template="123"]
```

---

## 📄 Licence & Crédits

Ce projet est distribué sous **licence MIT**.

```text
Copyright (c) 2025 Viry Brandon

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.
```

> **En résumé :** Vous êtes libre d'utiliser, de modifier et de distribuer ce projet, même commercialement, **À CONDITION DE CONSERVER LA MENTION DE COPYRIGHT (Viry Brandon)** dans les fichiers.

---

<p align="center">
  Développé par <strong>Viry Brandon</strong>
</p>
