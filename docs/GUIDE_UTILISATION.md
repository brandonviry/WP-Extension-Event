# Guide d'Utilisation - WP Extension Event

Ce guide vous explique comment utiliser l'extension **WP Extension Event** pour gérer et afficher vos événements sur votre site WordPress.

---

## 1. Gestion des Événements

Tout se passe dans votre tableau de bord WordPress (l'administration).

### Ajouter un événement
1.  Dans le menu de gauche, cliquez sur **Événements** > **Ajouter un événement**.
2.  **Titre** : Ajoutez le nom de votre événement en haut (ex: "Soirée Tapas").
3.  **Description** : Écrivez la description complète dans l'éditeur principal.
4.  **Détails de l'événement** (Boîte en bas de l'éditeur) :
    *   **Date** : Sélectionnez la date de l'événement.
    *   **Position (Lieu)** : La ville ou l'adresse (ex: "Saint-Denis, La Réunion").
    *   **Prix** : Le prix (ex: "22 €" ou "Gratuit").
    *   **URL** : Un lien vers une billetterie externe ou un formulaire (facultatif).
    *   **Nombre de places** : Nombre de places disponibles.
    *   **URL Image Externe** : Si vous n'utilisez pas la bibliothèque média (facultatif).
5.  **Image mise en avant** (Colonne de droite) :
    *   C'est très important ! Cliquez sur "Définir l'image mise en avant" et choisissez une belle photo. C'est celle qui apparaîtra sur la carte et en grand sur la page de l'événement.
6.  **Tags (Étiquettes)** (Colonne de droite) :
    *   Ajoutez des tags pour classer vos événements (ex: "rencontre", "musique", "atelier"). Cela servira pour les filtres.
7.  **PUBLIER** : Cliquez sur le bouton bleu **"Publier"** en haut à droite.
    *   *Attention : Si vous laissez en "Brouillon", l'événement ne sera pas visible sur le site.*

### Modifier ou Supprimer
1.  Allez dans **Événements** > **Tous les événements**.
2.  Passez la souris sur un événement :
    *   Cliquez sur **Modifier** pour changer des infos.
    *   Cliquez sur **Corbeille** pour le supprimer.

---

## 2. Afficher les événements (La Grille)

Pour afficher la liste de vos événements sur une page de votre site, on utilise un **Shortcode** (code court).

1.  Ouvrez la page où vous voulez afficher les événements (avec l'éditeur WordPress ou votre Page Builder comme Elementor/Divi).
2.  Insérez un bloc "Code court" ou "Texte".
3.  Collez ce code :
    ```
    [display_events]
    ```
4.  Mettez à jour la page et allez voir le résultat sur le site.

### Options du Shortcode
Vous pouvez personnaliser l'affichage avec des options :

*   **Filtrer par tag automatiquement** :
    *   `[display_events tags="rencontre"]` (Affiche seulement les événements avec le tag "rencontre").
*   **Cacher la barre de recherche et les filtres** :
    *   `[display_events ui_filter="false"]` (Affiche juste la grille sans le formulaire de recherche au-dessus).
*   **Limiter le nombre d'événements** :
    *   `[display_events limit="6"]` (Affiche seulement les 6 derniers événements).

*Exemple combiné :*
`[display_events tags="atelier" ui_filter="false" limit="3"]`

---

## 3. Design et Personnalisation

### Méthode 1 : Personnalisation Rapide (Couleurs)
L'extension utilise des **variables CSS**. Vous pouvez changer les couleurs pour qu'elles collent à votre marque sans toucher au code du plugin.

Allez dans votre éditeur de thème (Apparence > Personnaliser > CSS Additionnel) ou dans les paramètres de votre Page Builder (Elementor > Site Settings > Custom CSS) et ajoutez ceci :

```css
:root {
    --wpee-primary: #e11d48; /* Changer la couleur orange en Rouge Rose */
    --wpee-bg-card: #ffffff;  /* Couleur de fond de la carte */
    --wpee-border-radius: 0px; /* Enlever les coins arrondis */
}
```

### Méthode 2 : Design via Page Builder (No-Code Avancé)
Si vous utilisez **Elementor Pro**, **Divi**, ou **Bricks**, vous n'êtes pas obligé d'utiliser le shortcode `[display_events]`.

1.  Utilisez le widget **"Posts"** (Articles), **"Loop Grid"**, ou **"Portfolio"** de votre constructeur.
2.  Dans les réglages de la source (Query), choisissez **"Événements"** (Events) au lieu de "Articles".
3.  Vous pouvez maintenant utiliser tous les outils de design de votre constructeur pour faire la mise en page que vous voulez (couleurs, tailles, positions).
4.  Pour afficher la Date ou le Prix, utilisez les "Champs personnalisés" (Custom Fields) de votre constructeur avec les clés :
    *   `_event_date`
    *   `_event_price`
    *   `_event_location`

---

## 4. Problèmes Fréquents (Dépannage)

**Je ne vois pas mon événement sur la page ?**
1.  Vérifiez que l'événement est bien **PUBLIÉ** (et non en "Brouillon" ou "Privé").
2.  Vérifiez la date. L'extension affiche tout, mais assurez-vous d'avoir mis une date.

**La page de l'événement unique ressemble à un article de blog moche.**
1.  L'extension transforme automatiquement cette page pour qu'elle soit jolie. Si ça ne marche pas, essayez de désactiver et réactiver l'extension pour recharger les règles.

**Je clique sur "Voir" mais ça mène à une page 404 (Introuvable).**
1.  Allez dans **Réglages** > **Permaliens**.
2.  Cliquez simplement sur **"Enregistrer les modifications"** (sans rien changer). Cela réinitialise les liens de WordPress.
