![Logo LyonPalme](logo_lp.png)

# Application Sondage "**Palmoodle**"

Code du webservice développé avec Laravel. Ce webservice sera interrogé par l'application client leger "**palmoodle**" développée avec VueJS.

Mise à jour  _Mars 2024_.

### 1. Programmation.

- Développement avec le framework **Laravel**.
- Mise en place d'un système de **logs**.
- Utilisation du système d'authentification **Sanctum**  par Bearer Token.

### 2. Installation sur la VM WSL.

- Créer la base de données et le user sur Mariadb,
- Cloner le projet dans websites/laravel,
- Dupliquer le fichier _.env.example_, le renommer _.env_,
- Paramétrer l'accès à la db,
- Faire `composer install`,
- Dans vscode :
    * faire `Artisan: key generation`,
    * faire `Artisan: migrate install`,
    * faire `Artisan: migrate`.
- Pour peupler la db :
    * créer des users avec l'api : voir endpoint ci-dessous,
    * faire `php artisan db:seed --class=SondageSeeder`,
    * faire `php artisan db:seed --class=QuestionSeeder`,
    * faire `php artisan db:seed --class=SelectionSeeder`,
    * faire `php artisan db:seed --class=VoteSeeder`.

### 3. API Endpoints.

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

#### Application Sondage : consultation

| **Nom** | **Méthode** | **Url** | **Response Code** |
| ------- | ----------- | ------- | ----------------- | 
| Sondages | `GET` | _api/sondages_ | `200`, `500` |

**Listing de tous les sondages**. Affichage réduit : titre, date, description, auteur.
_Pas d'authentification requise_.

- Data Received
    - `None`.
- Data Send
    - `sondage complet` (array) : le sondage et ses détails (y compris les votes).

| **Nom** | **Méthode** | **Url** | **Response Code** |
| ------- | ----------- | ------- | ----------------- | 
| SondageById | `GET` | _api/sondages/{id}_ | `200`, `500`|

**Affiche le détail du sondage d'id {id} avec les votes**.

- Data Received
    - `auth_token` (cookie) : Bearer Token pour l'authentification.
    - `{id}`
- Data Send
    - `sondage + vote` (array) : Le détail du sondage sélectionné. 

#### Application Sondage : insertion en base.

| **Nom** | **Méthode** | **Url** | **Response Code** |
| ------- | ----------- | ------- | ----------------- | 
| Sondage | `POST` | _api/sondage_ | `201`, `500` |

**Insertion d'un sondage dans la base de données**.

- Data Received
    - `auth_token` (cookie) : Bearer Token pour l'authentification.
    - `titre` : titre du sondage,
    - `date` : date de création,
    - `description` : message de l'auteur,
    - `user_id` : id de l'auteur du sondage.
- Data Send
    - `success` : code 201.

| **Nom** | **Méthode** | **Url** | **Response Code** |
| ------- | ----------- | ------- | ----------------- | 
| Question | `POST` | _api/question_ | `201`, `500` |

**Insertion d'une question d'un sondage dans la base de données**.

- Data Received
    - `auth_token` (cookie) : Bearer Token pour l'authentification.
    - `libellé` : le libellé de la question,
    - `sondage_id` : id du sondage auquel appartient la question.
- Data Send
    - `success` : code 201.

| **Nom** | **Méthode** | **Url** | **Response Code** |
| ------- | ----------- | ------- | ----------------- | 
| Selection | `POST` | _api/selection_ | `201`, `500` |

**Insertion d'un item (sélection) d'une question dans la base de données**.

- Data Received
    - `auth_token` (cookie) : Bearer Token pour l'authentification.
    - `libellé` : le libellé de la sélection (item),
    - `question_id` : id de la question auquelle appartient la sélection.
- Data Send
    - `success` : code 201.

| **Nom** | **Méthode** | **Url** | **Response Code** |
| ------- | ----------- | ------- | ----------------- | 
| Vote | `POST` | _api/vote_ | `201`, `500` |

**Insertion d'un vote sur un sondage dans la base de données**.

- Data Received
    - `auth_token` (cookie) : Bearer Token pour l'authentification.
    - `date` : la date du vote,
    - `selection_id` : id de la sélection (item) choisie par le votant.
    - `user_id` : l'id du votant.
- Data Send
    - `success` : code 201.
