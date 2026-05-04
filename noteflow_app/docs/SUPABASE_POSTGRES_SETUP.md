# Laravel + Supabase PostgreSQL

## Objectif

Ce projet utilise Supabase uniquement comme fournisseur PostgreSQL.

Cela implique :

- Laravel reste la seule couche applicative.
- La connexion base de donnees passe par `pgsql`.
- Aucun service Supabase Auth, Storage, Realtime ou SDK JavaScript/PHP n'est necessaire pour faire fonctionner l'application.

## Variables d'environnement

Renseigner les variables suivantes dans `.env` a partir des informations de connexion de votre projet Supabase :

```env
APP_NAME=NoteFlow
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=pgsql
DB_HOST=db.<project-ref>.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your-database-password
DB_SCHEMA=public
DB_SSLMODE=require
```

Optionnellement, vous pouvez utiliser une URL unique :

```env
DB_URL=postgresql://postgres:your-database-password@db.<project-ref>.supabase.co:5432/postgres?sslmode=require
```

Laravel lira `DB_URL` si elle est fournie.

## Mise en place locale

1. Copier le fichier d'exemple.
2. Generer la cle applicative.
3. Vider le cache de configuration si necessaire.
4. Executer les migrations.

```bash
cp .env.example .env
php artisan key:generate
php artisan config:clear
php artisan migrate
```

## Comportement du projet

- La connexion par defaut de Laravel est `pgsql`.
- Le schema PostgreSQL est configurable via `DB_SCHEMA` et vaut `public` par defaut.
- `DB_SSLMODE` vaut `require` par defaut pour convenir a une base Supabase distante.
- Les scripts de build et de demarrage ne creent plus de base SQLite locale.

## Docker / deploiement

Le conteneur est prepare pour se connecter a une base PostgreSQL externe.

Variables minimales a fournir en deploiement :

```env
APP_KEY=base64:...
APP_URL=https://votre-domaine
DB_CONNECTION=pgsql
DB_HOST=db.<project-ref>.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=your-database-password
DB_SCHEMA=public
DB_SSLMODE=require
```

Au demarrage :

- le fichier `.env` est reconstruit depuis les variables d'environnement ;
- les caches Laravel sont purges ;
- les migrations sont lancees ;
- la documentation Scribe est regeneree.

## Verification

Verifier la connexion :

```bash
php artisan migrate:status
```

Si la connexion est correcte, Laravel doit afficher l'etat des migrations sur la base Supabase.

## Points d'attention

- Sur Supabase, le mot de passe a utiliser est le mot de passe PostgreSQL du projet, pas une cle API `anon` ou `service_role`.
- Si vous utilisez le pooler Supabase au lieu de l'hote direct, adaptez `DB_HOST` et `DB_PORT` selon les valeurs fournies par Supabase.
- Les tests PHPUnit restent configures en SQLite en memoire pour garder des tests rapides et isoles.
