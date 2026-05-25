# API Blog Lyon Palme

Base URL locale Laravel :

```text
http://localhost:8000/api
```

Headers JSON :

```http
Accept: application/json
Content-Type: application/json
Authorization: Bearer <access_token>
```

CORS :

```env
CORS_ALLOWED_ORIGINS=http://localhost:3000,http://127.0.0.1:3000,http://localhost:5173,http://127.0.0.1:5173
```

Format standard des reponses JSON :

```json
{
  "success": true,
  "message": "Message lisible",
  "data": {}
}
```

En erreur de validation :

```json
{
  "success": false,
  "message": "Donnees invalides.",
  "data": null,
  "errors": {}
}
```

Les suppressions reussies renvoient `204 No Content`.

## Authentification

| Methode | URL | Auth | Role | Body |
| --- | --- | --- | --- | --- |
| POST | `/api/register` | Non | Aucun | `name`, `email`, `password` |
| POST | `/api/login` | Non | Aucun | `email`, `password` |
| GET | `/api/user` | Oui | Nageur ou admin | Aucun |
| POST | `/api/logout` | Oui | Nageur ou admin | Aucun |
| POST | `/api/user/logout` | Oui | Nageur ou admin | Aucun |

`POST /api/login` renvoie :

```json
{
  "success": true,
  "message": "Connexion reussie.",
  "data": {
    "access_token": "token",
    "token_type": "Bearer",
    "user": {}
  }
}
```

## Billets

| Methode | URL | Auth | Role | Body |
| --- | --- | --- | --- | --- |
| GET | `/api/billets` | Non | Aucun | Aucun |
| GET | `/api/billets/{id}` | Non | Aucun | Aucun |
| POST | `/api/billets` | Oui | Admin | `titre`/`BIL_TITRE`, `contenu`/`BIL_CONTENU`, `date`/`BIL_DATE` optionnel |
| PUT | `/api/billets/{id}` | Oui | Admin | champs a modifier |
| PATCH | `/api/billets/{id}` | Oui | Admin | champs a modifier |
| DELETE | `/api/billets/{id}` | Oui | Admin | Aucun |

Exemple creation billet :

```json
{
  "titre": "Nouveau billet",
  "contenu": "Contenu du billet",
  "date": "2026-04-27"
}
```

## Commentaires

| Methode | URL | Auth | Role | Body |
| --- | --- | --- | --- | --- |
| GET | `/api/billets/{id}/commentaires` | Non | Aucun | Aucun |
| POST | `/api/billets/{id}/commentaires` | Oui | Nageur ou admin | `commentaire`/`COM_CONTENU`, `date`/`COM_DATE` optionnel |
| POST | `/api/commentaires` | Oui | Nageur ou admin | `billet_id`, `commentaire`/`COM_CONTENU`, `date`/`COM_DATE` optionnel |
| DELETE | `/api/commentaires/{id}` | Oui | Admin | Aucun |

Le serveur ignore toujours le `user_id` envoye par le frontend pour les commentaires : l'auteur est l'utilisateur authentifie par le token.

## Roles et tables

Le projet fourni utilise les noms de tables existants `billets` et `commentaires`. Pour respecter la structure Laravel deja fournie et les routes React existantes, ces noms ont ete conserves.

Tables backend principales :

- `users`
- `roles`
- `billets`
- `commentaires`

Roles seedes :

- `admin`
- `nageur`

Relations Eloquent :

- `User belongsTo Role`
- `User hasMany Billet`
- `User hasMany Commentaire`
- `Billet belongsTo User`
- `Billet hasMany Commentaire`
- `Commentaire belongsTo Billet`
- `Commentaire belongsTo User`

## Commandes

```bash
composer install
php artisan key:generate
php artisan migrate
php artisan db:seed
php artisan test
php artisan serve --host=0.0.0.0 --port=8000
```

Compte admin de seed par defaut :

```text
email: admin@lyonpalme.local
password: Admin1234!
```

Ces valeurs peuvent etre surchargees avec `ADMIN_EMAIL`, `ADMIN_NAME` et `ADMIN_PASSWORD`.
