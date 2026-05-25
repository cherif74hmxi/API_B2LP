![Logo LyonPalme](logo_lp.png)

# Application blog de Lyon Palme "**B2LP**"

Code du webservice développé avec Laravel. Ce webservice sera interrogé par l'application client leger "**b2LP**" développée avec ReactNative.

Mise à jour  _Mai 2026_.

### 1. Programmation.

- Développement avec le framework **Laravel**.
- Mise en place d'un système de **logs**.
- Utilisation du système d'authentification **Sanctum**  par Bearer Token.

### 2. Installation sur la VM WSL.

- Créer la base de données et le user sur Mariadb,
- Cloner le projet dans websites/laravel,
- Donner les droits aux répertoires _boostrap/cache_ et _storage_,
- Dupliquer le fichier _.env.example_, le renommer _.env_,
- Paramétrer l'accès à la db,
- Faire `composer install`,
- Dans vscode :
    * faire `Artisan: key generation`,
    * faire `Artisan: migrate install`,
    * faire `Artisan: migrate`.
- Pour créer les tables et peupler la db en local :
    * faire `php artisan migrate:fresh --seed`.

- _Pour la mise en production_ :
    * créer un user dans la DB avec des droits CRUD uniquement,
    * [suivre les instructions de laravel.](https://laravel.com/docs/11.x/deployment)

### 3. Déploiement sur le VPS.

Depuis le VPS, se placer dans le dossier du projet puis récupérer la dernière version :

```bash
cd /chemin/du/projet
git pull origin main
composer install --no-dev --optimize-autoloader
php artisan migrate:fresh --seed --force
php artisan config:cache
php artisan route:clear
```

`migrate:fresh --seed` supprime les tables existantes, les recrée, puis relance les seeders. Cette commande est adaptée tant que la base du VPS ne contient pas encore de vraies données à conserver.

Si la base contient déjà des données importantes, utiliser plutôt une migration dédiée et ne pas lancer `migrate:fresh`.
    

### 4. API Endpoints.

#### Authentification

| **Nom** | **Méthode** | **Url** | **Response Code** |
| ------- | ----------- | ------- | ----------------- | 
| Register | `POST` | _api/register_ | `200`, `404`, `500` |

**Création du compte d'un utilisateur**.

- Data Received
    - `name` (string) : nom de l'utilisateur.
    - `mail` (string) : email de l'utilisateur.
    - `password` (string) : Mot de passe.
- Data Send
    - `auth_token` (cookie) : Bearer Token pour l'authentification.

| **Nom** | **Méthode** | **Url** | **Response Code** |
| ------- | ----------- | ------- | ----------------- | 
| Login | `POST` | _api/login_ | `200`, `422`, `404` |

**Connexion d'un utilisateur**.

- Data Received
    - `mail` (string) : mail de l'utilisateur.
    - `password` (string) : Mot de passe.
- Data Send
    - `auth_token` (cookie) : Bearer Token pour l'authentification.

| **Nom** | **Méthode** | **Url** | **Response Code** |
| ------- | ----------- | ------- | ----------------- | 
| Logout | `POST` | _api/user/logout_ | `200` |

**Déconnexion d'un utilisateur**.

- Data Received
    - `auth_token` (cookie) : Bearer Token pour l'authentification.
- Data Send
    - `None`.

| **Nom** | **Méthode** | **Url** | **Response Code** |
| ------- | ----------- | ------- | ----------------- | 
| User | `GET` | _api/user_ | `200`, `401` |

**Vérification de la connexion d'un utilisateur**.

- Data Received
    - `auth_token` (cookie) : Bearer Token pour l'authentification.
- Data Send
    - `id`,
    - `nom`,
    - `email`.

#### Application Blog : consultation des billets.

| **Nom** | **Méthode** | **Url** | **Response Code** |
| ------- | ----------- | ------- | ----------------- | 
| Billets | `GET` | _api/billets_ | `200`, `500` |

**Listing de tous les billets**. Affichage réduit : titre, date, contenu.
_Pas d'authentification requise_.

- Data Received
    - `None`.
- Data Send
    - `billets complet` (array) : les billets.

| **Nom** | **Méthode** | **Url** | **Response Code** |
| ------- | ----------- | ------- | ----------------- | 
| BilletById | `GET` | _api/billets/{id}_ | `200`, `500`|

**Affiche le détail d'un billet d'id {id}**.

- Data Received
    - `auth_token` (cookie) : Bearer Token pour l'authentification.
    - `{id}`
- Data Send
    - `billet` (array) : Le détail du billet sélectionné avec ses commentaires et les noms des auteurs des commentaires. 

#### Application Blog : insertion en base.

| **Nom** | **Méthode** | **Url** | **Response Code** |
| ------- | ----------- | ------- | ----------------- | 
|  | `POST` | _api/commentaires_ | `201`, `500` |

**Insertion d'un commentaire dans la base de données**.

- Data Received
    - `auth_token` (cookie) : Bearer Token pour l'authentification.
    - `date` : date de création,
    - `contenu` : message de l'auteur,
    - `billet_id` : id du billet auquel correspond le commentaire,
    - `user_id` : id de l'auteur du commentaire.
- Data Send
    - `success` : code 201.
 

#### Application Blog : administration des billets et commentaires.

Les fonctionnalités d'administration permettent de créer, modifier et supprimer des billets, ainsi que de supprimer des commentaires.

Elles nécessitent une authentification par Bearer Token avec Sanctum. Elles sont réservées aux utilisateurs dont la colonne `role` dans la table `users` vaut `admin`.

Les utilisateurs classiques ont le rôle `adherent`.

Le seeder `UserSeeder` crée un compte administrateur par défaut :

```txt
Nom : Cherif
Email : cherif@lyonpalm.fr
Rôle : admin
```

Après connexion via `POST /api/login`, le token retourné doit être envoyé dans les requêtes protégées :

```http
Authorization: Bearer {token}
```

| **Nom** | **Méthode** | **Url** | **Response Code** |
| ------- | ----------- | ------- | ----------------- |
| BilletCreate | `POST` | _api/billets_ | `201`, `401`, `403`, `422`, `500` |

**Création d'un billet**.

- Data Received
    - `auth_token` (Bearer Token) : token de l'administrateur connecté,
    - `BIL_DATE` (date) : date du billet,
    - `BIL_TITRE` (string) : titre du billet,
    - `BIL_CONTENU` (string) : contenu du billet.
- Data Send
    - `billet` (array) : le billet créé.

| **Nom** | **Méthode** | **Url** | **Response Code** |
| ------- | ----------- | ------- | ----------------- |
| BilletUpdate | `PATCH` | _api/billets/{billet}_ | `200`, `401`, `403`, `404`, `422`, `500` |

**Modification d'un billet**.

- Data Received
    - `auth_token` (Bearer Token) : token de l'administrateur connecté,
    - `{billet}` : id du billet à modifier,
    - `BIL_DATE` (date) : nouvelle date du billet,
    - `BIL_TITRE` (string) : nouveau titre du billet,
    - `BIL_CONTENU` (string) : nouveau contenu du billet.
- Data Send
    - `billet` (array) : le billet modifié.

| **Nom** | **Méthode** | **Url** | **Response Code** |
| ------- | ----------- | ------- | ----------------- |
| BilletDelete | `DELETE` | _api/billets/{billet}_ | `204`, `401`, `403`, `404`, `500` |

**Suppression d'un billet**.

- Data Received
    - `auth_token` (Bearer Token) : token de l'administrateur connecté,
    - `{billet}` : id du billet à supprimer.
- Data Send
    - `None`.

| **Nom** | **Méthode** | **Url** | **Response Code** |
| ------- | ----------- | ------- | ----------------- |
| CommentaireDelete | `DELETE` | _api/commentaires/{commentaire}_ | `204`, `401`, `403`, `404`, `500` |

**Suppression d'un commentaire**.

- Data Received
    - `auth_token` (Bearer Token) : token de l'administrateur connecté,
    - `{commentaire}` : id du commentaire à supprimer.
- Data Send
    - `None`.

Si l'utilisateur connecté n'a pas le rôle `admin`, l'API retourne une erreur `403`.
