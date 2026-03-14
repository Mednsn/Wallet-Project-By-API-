# PayFlow API

API REST développée avec Laravel pour gérer un portefeuille électronique
permettant aux utilisateurs de créer des wallets et d'effectuer des
transactions.

------------------------------------------------------------------------

## Fonctionnalités

### Authentification

-   Inscription d'un utilisateur
-   Connexion avec génération de token
-   Déconnexion

Authentification réalisée avec Laravel Sanctum.

------------------------------------------------------------------------

## Endpoints API

### Authentification

#### POST /api/register

Créer un nouvel utilisateur.

Body: { "name": "Ali",
        "email": "ali@mail.com",
        "password": "123456"
     }

------------------------------------------------------------------------

#### POST /api/login

Connexion utilisateur.

Body: { "email": "ali@mail.com",
        "password": "123456"
     }

Response: { 
            "token": "authentication_token"
          }

------------------------------------------------------------------------

#### POST /api/logout

Déconnexion de l'utilisateur.

Header: Authorization: Bearer token

------------------------------------------------------------------------

## Wallets

#### POST /api/wallets

Créer un wallet.

Body: { 
        "name" : "wallet 1",
        "currency": "USD" 
       }

------------------------------------------------------------------------

#### GET /api/wallets

Lister les wallets de l'utilisateur connecté.

------------------------------------------------------------------------

#### GET /api/wallets/{id}

Afficher les détails d'un wallet.

------------------------------------------------------------------------

## Transactions

#### POST /api/wallets/{id}/deposit

Déposer de l'argent.

Body: { 
        "montant": 100 
        }

------------------------------------------------------------------------

#### POST /api/wallets/{id}/withdraw

Retirer de l'argent.

Body: {
        "montant": 50
     }

------------------------------------------------------------------------

#### POST /api/wallets/{id}/transfer

Transférer de l'argent vers un autre wallet.

Body: { 
         "target_wallet_id": 2,
         "montant": 30
     }

------------------------------------------------------------------------

#### GET /api/wallets/{id}/transactions

Afficher l'historique des transactions.

