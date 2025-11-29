# Guide d'Utilisation - WP Extension Event

## 1. Gestion des Événements
*(Voir ci-dessus pour ajouter des événements)*

---

## 2. Affichage Classique
Utilisez `[display_events]` pour afficher la grille par défaut.

---

## 3. CRÉER VOTRE PROPRE CARTE (Mode Design)

Vous voulez désigner la carte vous-même avec Elementor, Divi, ou Gutenberg ? Voici comment faire :

### Étape 1 : Créer le Template
1.  Allez dans **Événements** > **Templates de Carte** (Nouveau menu !).
2.  Cliquez sur **Ajouter**.
3.  Donnez un titre (ex: "Ma Carte Design").
4.  **Designez votre carte** comme vous le feriez pour n'importe quelle page.
    *   Vous pouvez utiliser des colonnes, des couleurs, des bordures, des images de fond...
    *   *Astuce : Imaginez que vous designez UNE SEULE carte.*

### Étape 2 : Insérer les données dynamiques
Pour que la carte affiche le Titre ou le Prix de l'événement (et non du texte fixe), utilisez ces **Shortcodes** à l'intérieur de vos blocs Textes ou Titres :

*   `[wpee_title]` : Affiche le Titre de l'événement.
*   `[wpee_date]` : Affiche la Date.
*   `[wpee_price]` : Affiche le Prix.
*   `[wpee_seats]` : Affiche le Nombre de places.
*   `[wpee_location]` : Affiche le Lieu.
*   `[wpee_image]` : Affiche l'image de l'événement.
*   `[wpee_image_url]` : Affiche juste l'URL de l'image (pour les fonds).
*   `[wpee_tag]` : Affiche le nom du Tag (Catégorie).
*   `[wpee_event_url]` : Affiche l'URL de l'événement (pour les liens de boutons).
*   `[wpee_excerpt]` : Affiche le résumé (l'extrait).
*   `[wpee_description]` : Affiche la description complète.
*   `[wpee_read_more text="Réserver"]` : Affiche un bouton vers l'événement.
*   `[wpee_link_start]` et `[wpee_link_end]` : Si vous voulez que toute une zone soit cliquable (ex: `[wpee_link_start] CLIQUEZ MOI [wpee_link_end]`).

**Exemple avec Elementor :**
1.  Ajoutez un "Conteneur" avec une bordure et une ombre.
2.  Dedans, ajoutez un widget "Image" -> Dans le lien, mettez rien (ou utilisez le shortcode Image dans un widget HTML).
3.  Ajoutez un widget "Titre" -> Dans le texte, écrivez `[wpee_title]`.
4.  Ajoutez un widget "Texte" -> Écrivez `Prix : [wpee_price]`.

### Étape 3 : Utiliser votre Template
1.  Une fois votre Template enregistré, regardez son **ID** dans l'URL (ex: `post=125`).
2.  Allez sur la page où vous affichez la grille.
3.  Modifiez le shortcode pour dire :
    ```
    [display_events template="125"]
    ```

Le plugin va maintenant utiliser **VOTRE design** pour afficher chaque événement de la grille !

---

## 4. Filtres Avancés
Si vous souhaitez placer la barre de recherche et de filtres à un endroit différent de la grille, vous pouvez :
1.  Utiliser le shortcode de la grille sans filtre : `[display_events template="125" ui_filter="false"]`
2.  Placer le shortcode de filtre où vous voulez sur la page : `[wpee_filter]`

### Designer sa propre barre de filtres
Comme pour les cartes, vous pouvez designer votre formulaire de filtre avec Breakdance/Elementor !

1.  Créez un nouveau **Template de Carte** (appelez-le "Mon Filtre").
2.  Dessinez vos colonnes (ex: 3 colonnes).
3.  Insérez les shortcodes suivants dans des blocs Texte ou HTML :
    *   `[wpee_input_search placeholder="Rechercher..."]` : La barre de texte.
    *   `[wpee_input_tags label="Choisir une catégorie"]` : Le menu déroulant des tags.
    *   `[wpee_filter_tag slug="musique" text="Musique"]` : Une pastille cliquable pour filtrer par tag.
    *   `[wpee_filter_submit text="Go !"]` : Le bouton pour valider.
    *   `[wpee_filter_reset text="Effacer"]` : Le lien pour tout remettre à zéro.
4.  Sur votre page, utilisez : `[wpee_filter template="ID_DU_TEMPLATE"]`.

---

## 5. Dépannage
*   **Si le design est cassé :** Vérifiez que vous n'avez pas mis de marges énormes dans votre template.
*   **Elementor :** Si vous utilisez Elementor pour le template, assurez-vous qu'il est bien sauvegardé.
