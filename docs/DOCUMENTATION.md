# Documentation Complète - WP Extension Event

Bienvenue dans la documentation officielle de WP Extension Event. Ce plugin vous permet de gérer des événements et de les afficher avec un design entièrement personnalisable via votre constructeur de page favori (Breakdance, Elementor, Divi, Gutenberg).

## Sommaire
1. [Installation & Configuration](#1-installation--configuration)
2. [Créer des événements](#2-créer-des-événements)
3. [Afficher la grille (Bases)](#3-afficher-la-grille-bases)
4. [Designer la Carte (Template)](#4-designer-la-carte-template)
5. [Designer les Filtres (Template)](#5-designer-les-filtres-template)
6. [Shortcodes Disponibles](#6-shortcodes-disponibles)

---

## 1. Installation & Configuration
Activez simplement le plugin. Il n'y a pas de page de configuration complexe. Tout se passe dans les menus **"Événements"**.

**Réglage important :**
Après l'activation, allez dans **Réglages > Permaliens** et cliquez sur "Enregistrer les modifications" pour que les liens fonctionnent.

---

## 2. Créer des événements
Allez dans **Événements > Ajouter un événement**.

Remplissez les champs :
*   **Titre** : Nom de l'événement.
*   **Description** : Détails complets.
*   **Extrait (Résumé)** : Un court texte qui s'affichera sur la carte. (Activez l'option "Extrait" dans les options de l'écran si invisible).
*   **Image mise en avant** : L'image principale de la carte.
*   **Tags** : Catégories (ex: Concert, Théâtre).
*   **Détails (en bas)** :
    *   Date, Lieu, Prix, Nombre de places.
    *   URL (Lien externe si besoin).

---

## 3. Afficher la grille (Bases)
Pour afficher une grille standard sur n'importe quelle page, utilisez le shortcode :
```
[display_events]
```

**Options :**
*   `[display_events limit="3"]` : Affiche les 3 derniers.
*   `[display_events tags="sport"]` : Affiche seulement la catégorie "sport".
*   `[display_events ui_filter="false"]` : Cache la barre de recherche par défaut.

---

## 4. Designer la Carte (Template)
C'est la force du plugin. Vous pouvez créer le design de LA carte avec votre builder.

1.  Allez dans **Événements > Templates de Carte**.
2.  Créez un nouveau template (ex: "Ma Carte Design").
3.  Utilisez votre builder (Breakdance, Elementor...) pour dessiner **UNE** carte.
4.  Pour afficher les infos (Titre, Prix...), insérez des **Shortcodes** dans vos widgets Texte (voir liste plus bas).
5.  Sauvegardez.
6.  Dans la liste des templates, copiez le shortcode généré (ex: `[display_events template="123"]`).
7.  Collez ce code sur votre page finale.

---

## 5. Designer les Filtres (Template)
Vous pouvez aussi créer votre propre barre de recherche.

1.  Créez un nouveau **Template de Carte** (ex: "Mon Filtre").
2.  Dessinez votre barre (colonnes, couleurs...).
3.  Insérez les shortcodes de formulaire :
    *   `[wpee_input_search]` : Champ texte.
    *   `[wpee_filter_submit]` : Bouton valider.
4.  Sur votre page finale :
    *   Affichez la grille SANS filtre : `[display_events template="123" ui_filter="false"]`
    *   Affichez votre filtre où vous voulez : `[wpee_filter template="456"]`

**Astuce pour les boutons personnalisés :**
Si vous voulez utiliser un JOLI bouton de votre builder pour valider le formulaire :
*   Mettez l'ID `wpee-submit-trigger` sur votre bouton (dans Avancé > ID).
*   Laissez le lien vide.

---

## 6. Shortcodes Disponibles

### Pour la Carte (Données de l'événement)
| Shortcode | Description |
|-----------|-------------|
| `[wpee_title]` | Titre de l'événement |
| `[wpee_date]` | Date formattée |
| `[wpee_price]` | Prix |
| `[wpee_seats]` | Nombre de places |
| `[wpee_location]` | Lieu |
| `[wpee_tag]` | Premier Tag (Catégorie) |
| `[wpee_excerpt]` | Résumé court |
| `[wpee_description]` | Description complète |
| `[wpee_image]` | Balise image complète |
| `[wpee_image_url]` | URL de l'image (pour background) |
| `[wpee_event_url]` | URL de la page événement (pour liens) |
| `[wpee_read_more text="Voir"]` | Bouton simple vers l'événement |

### Pour le Filtre (Formulaire)
| Shortcode | Description |
|-----------|-------------|
| `[wpee_input_search]` | Champ de recherche texte |
| `[wpee_input_tags]` | Liste déroulante des tags |
| `[wpee_filter_tag slug="rap" text="Rap"]` | Pastille cliquable pour filtrer par tag |
| `[wpee_filter_submit]` | Bouton de validation standard |
| `[wpee_filter_reset]` | Lien pour réinitialiser |
