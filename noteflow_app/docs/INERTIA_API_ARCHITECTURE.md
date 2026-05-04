# Fonctionnement Inertia + API

## Objectif

Ce document explique :

- le fonctionnement global de l'application ;
- quelles pages Inertia existent ;
- quelles APIs Laravel elles consomment ;
- comment le mode offline-first et la synchronisation fonctionnent.

## Vue d'ensemble

L'application suit une separation simple :

- Laravel sert la page Inertia initiale ;
- Vue gere l'interface et l'etat local ;
- les donnees sont lues et ecrites via des endpoints REST ;
- IndexedDB garde un snapshot local et une file d'actions offline ;
- le backend Laravel applique les changements et recalcule tags et liens entre notes.

## Point d'entree web

Les pages web sont definies dans [routes/web.php](/home/rins/Projects/NoteFlow/noteflow_app/routes/web.php).

Routes principales :

- `/` -> page Inertia `Dashboard`
- `/dashboard` -> page Inertia `Dashboard`

Cette page est rendue par :

- [resources/js/Pages/Dashboard.vue](/home/rins/Projects/NoteFlow/noteflow_app/resources/js/Pages/Dashboard.vue)

Le template racine Inertia est :

- [resources/views/app.blade.php](/home/rins/Projects/NoteFlow/noteflow_app/resources/views/app.blade.php)

## Pages Inertia utiles

### 1. `Dashboard`

Fichier :

- [resources/js/Pages/Dashboard.vue](/home/rins/Projects/NoteFlow/noteflow_app/resources/js/Pages/Dashboard.vue)

Role :

- page principale du second cerveau ;
- affiche sidebar, liste des notes, tags, editeur, preview Markdown et panneau des notes liees ;
- gere quick capture, recherche, edition et synchronisation.

Composants UX dans cette page :

- sidebar gauche : notes recentes, tags, recherche ;
- centre : editeur + preview ;
- droite : notes liees + aide Markdown ;
- modal : quick capture.

## APIs consommees par la page Inertia

Les endpoints sont definis dans [routes/api.php](/home/rins/Projects/NoteFlow/noteflow_app/routes/api.php).

### `GET /api/workspace`

Controleur :

- [app/Http/Controllers/Api/WorkspaceController.php](/home/rins/Projects/NoteFlow/noteflow_app/app/Http/Controllers/Api/WorkspaceController.php)

Usage frontend :

- charge l'etat initial du workspace ;
- recharge les notes apres synchronisation ;
- alimente la liste des notes et le panneau des tags.

Consomme par :

- `refreshWorkspace()` dans [resources/js/Pages/Dashboard.vue](/home/rins/Projects/NoteFlow/noteflow_app/resources/js/Pages/Dashboard.vue)
- `flushSyncQueue()` dans [resources/js/Pages/Dashboard.vue](/home/rins/Projects/NoteFlow/noteflow_app/resources/js/Pages/Dashboard.vue)

Retour attendu :

- `notes`
- `tags`
- `meta.generated_at`

### `POST /api/sync`

Controleur :

- [app/Http/Controllers/Api/SyncController.php](/home/rins/Projects/NoteFlow/noteflow_app/app/Http/Controllers/Api/SyncController.php)

Usage frontend :

- envoie la file d'actions offline ou locales ;
- applique `create`, `update`, `delete` ;
- sert de point central de synchronisation.

Consomme par :

- `flushSyncQueue()` dans [resources/js/Pages/Dashboard.vue](/home/rins/Projects/NoteFlow/noteflow_app/resources/js/Pages/Dashboard.vue)

Payload :

```json
{
  "actions": [
    {
      "type": "create",
      "payload": {
        "id": "uuid",
        "title": "Ma note",
        "content": "Contenu"
      }
    }
  ]
}
```

### `POST /api/notes`

Controleur :

- [app/Http/Controllers/Api/NoteController.php](/home/rins/Projects/NoteFlow/noteflow_app/app/Http/Controllers/Api/NoteController.php)

Usage prevu :

- creation unitaire cote serveur ;
- actuellement le flux principal du frontend passe plutot par `/api/sync`.

### `GET /api/notes/{id}`

Usage prevu :

- lecture unitaire d'une note ;
- utile si l'application evolue vers une page detaillee ou une ouverture lazy note par note.

### `PUT /api/notes/{id}`

Usage prevu :

- mise a jour unitaire cote serveur ;
- aujourd'hui, l'edition principale passe par la queue de sync.

### `DELETE /api/notes/{id}`

Usage prevu :

- suppression logique d'une note cote serveur ;
- aujourd'hui, le flux principal passe aussi par `/api/sync`.

## Flux complet de la page `Dashboard`

### 1. Chargement initial

Au montage :

1. la page essaie de lire un snapshot local depuis IndexedDB ;
2. si un snapshot existe, il est affiche immediatement ;
3. ensuite la page appelle `GET /api/workspace` ;
4. la reponse serveur remplace l'etat local ;
5. la page persiste ce nouvel etat dans IndexedDB.

Code implique :

- [resources/js/lib/indexedDb.js](/home/rins/Projects/NoteFlow/noteflow_app/resources/js/lib/indexedDb.js)
- [resources/js/Pages/Dashboard.vue](/home/rins/Projects/NoteFlow/noteflow_app/resources/js/Pages/Dashboard.vue)

### 2. Edition d'une note

Quand l'utilisateur modifie le titre ou le contenu :

1. un `watch()` detecte le changement ;
2. un debounce de `500 ms` attend la fin de saisie ;
3. la note est mise a jour localement ;
4. une action `update` est ajoutee a la queue offline ;
5. une synchronisation est planifiee.

### 3. Quick capture

Quand l'utilisateur fait `Ctrl+K` ou `Cmd+K` :

1. une modal s'ouvre ;
2. le contenu saisi cree une note locale immediate ;
3. une action `create` est ajoutee a la queue ;
4. la sync enverra ensuite cette creation au backend.

### 4. Suppression

Quand l'utilisateur supprime une note :

1. la note passe localement en `is_deleted = true` ;
2. une action `delete` est enfilee ;
3. la sync transmet la suppression logique au backend.

### 5. Retour online

Quand le navigateur repasse online :

1. l'evenement `online` declenche `flushSyncQueue()` ;
2. toutes les actions locales sont envoyees a `/api/sync` ;
3. le frontend recharge ensuite `GET /api/workspace` ;
4. l'interface se resynchronise avec l'etat serveur.

## Role du backend dans la logique metier

Le coeur metier est centralise dans :

- [app/Services/NoteGraphService.php](/home/rins/Projects/NoteFlow/noteflow_app/app/Services/NoteGraphService.php)

Ce service :

- cree ou met a jour une note ;
- extrait les tags depuis le contenu avec la syntaxe `#tag` ;
- extrait les liens avec la syntaxe `[[Nom de note]]` ;
- cree les notes cibles manquantes si necessaire ;
- reconstruit les relations dans `note_links`.

## Modele de donnees implique

Tables principales :

- `notes`
- `tags`
- `note_tag`
- `note_links`

Migration :

- [database/migrations/2026_05_04_000000_create_notes_tables.php](/home/rins/Projects/NoteFlow/noteflow_app/database/migrations/2026_05_04_000000_create_notes_tables.php)

## Fichiers frontend importants

### Page

- [resources/js/Pages/Dashboard.vue](/home/rins/Projects/NoteFlow/noteflow_app/resources/js/Pages/Dashboard.vue)

### Stockage local

- [resources/js/lib/indexedDb.js](/home/rins/Projects/NoteFlow/noteflow_app/resources/js/lib/indexedDb.js)

Responsabilites :

- stocker un snapshot du workspace ;
- stocker la queue des actions a synchroniser ;
- relire ces donnees au rechargement.

### Rendu Markdown

- [resources/js/lib/markdown.js](/home/rins/Projects/NoteFlow/noteflow_app/resources/js/lib/markdown.js)

Responsabilites :

- afficher titres, listes et blocs de code ;
- mettre en evidence `[[liens]]` et `#tags`.

### Style

- [resources/css/app.css](/home/rins/Projects/NoteFlow/noteflow_app/resources/css/app.css)

Responsabilites :

- layout principal ;
- theme sombre ;
- composants visuels du workspace.

## Pages Inertia et relation avec les APIs

Etat actuel :

- `Dashboard` est la page Inertia metier qui consomme les APIs notes/sync/workspace.

Pages secondaires deja presentes dans le projet :

- pages d'auth Breeze ;
- page profil.

Ces pages d'auth ne consomment pas les APIs notes du workspace. Elles servent seulement a l'authentification Laravel standard si tu decides de l'utiliser.

## Evolution recommandee

Si tu veux separer davantage le produit en plusieurs pages Inertia, voici la decomposition logique recommande :

### 1. `Pages/Workspace/Index.vue`

But :

- remplacer le `Dashboard` actuel par la page principale de travail ;
- continuer a consommer `GET /api/workspace` et `POST /api/sync`.

### 2. `Pages/Workspace/NoteShow.vue`

But :

- afficher une note seule dans une route dediee ;
- consommer `GET /api/notes/{id}` et `PUT /api/notes/{id}` si tu veux un mode detail.

### 3. `Pages/Workspace/Search.vue`

But :

- faire une page dediee aux resultats de recherche ;
- consommer `GET /api/workspace?query=...`.

### 4. `Pages/Workspace/Tags.vue`

But :

- afficher la navigation par tags ;
- consommer `GET /api/workspace?tag=...`.

### 5. `Pages/Workspace/Graph.vue`

But :

- visualiser le graphe des notes ;
- reposer d'abord sur `GET /api/workspace`, puis plus tard sur un endpoint dedie si necessaire.

## Resume

La logique actuelle est simple :

- Inertia sert la page `Dashboard` ;
- Vue gere l'experience utilisateur ;
- IndexedDB assure l'offline-first ;
- Laravel expose les endpoints REST ;
- `NoteGraphService` maintient tags et relations ;
- `/api/sync` est le point central de synchronisation du workspace.
