# NoteFlow

Application Laravel avec front Inertia/Vue.

## Stack retenue

- Laravel pour l'application.
- Supabase utilisé uniquement comme base PostgreSQL distante.
- Aucun SDK Supabase n'est requis côté application si l'usage reste strictement SQL.

## Configuration rapide

1. Copier `.env.example` vers `.env`.
2. Renseigner les variables PostgreSQL fournies par Supabase.
3. Générer la clé et lancer les migrations.

```bash
cp .env.example .env
php artisan key:generate
php artisan migrate
```

## Documentation

- Procédure complète : [docs/SUPABASE_POSTGRES_SETUP.md](/home/rins/Projects/NoteFlow/noteflow_app/docs/SUPABASE_POSTGRES_SETUP.md)
- Architecture Inertia + API : [docs/INERTIA_API_ARCHITECTURE.md](/home/rins/Projects/NoteFlow/noteflow_app/docs/INERTIA_API_ARCHITECTURE.md)
- Documentation API Scribe : `/docs`
- Spécification OpenAPI : `/docs.openapi`
