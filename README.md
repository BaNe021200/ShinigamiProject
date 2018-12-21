# Shinigami Laser

## Objectif

Réaliser un projet de qualité, testé et documenté à présenter sur une soutenance.

Proposer une couverture unitaire et fonctionnelle à la réalisation.

Créer un diagrammme UML afin de visualiser les modèles de votre application.

## Contexte

"Shinigami Laser" est un laser game assez connu en Bourgogne. Malheureusement, le suivi des joueurs est aujourd'hui assez chaotique avec des fiches papier sur lesquelles sont écrits le nom, le surnom, l'adresse, le numéro de téléphone et la date de naissance d'un joueur. Ces fiches sont devenues totalement obsolètes, aussi nous aimerions nous doter d'un système de cartes de fidélité, cartes physiques ou numériques (avec une application sur smartphone comme interface).

Pour ce faire, nous avons contacté un fournisseur de cartes qui pourrait nous imprimer des cartes avec une puce NFC/RFID, un QR code optionnel, et bien sûr des informations précieuses sur notre club.

La carte elle-même aura un numéro. Celui-ci servira pour nos références internes, mais aussi à nos clients qui pourront les rattacher à leur compte en ligne pour suivre différentes informations : leurs visites, leurs scores, leurs offres, etc.

## Réalisation

### Proposer cette gestion de carte de fidélité

Sachant qu'une carte physique possèdera un motif :

> CODE_CENTRE CODE_CARTE CHECKSUM
>
> CODE_CENTRE : 3 chiffres décrivant un établissement
>
> CODE_CARTE : 6 chiffres décrivant un client
>
> CHECKSUM : somme des chiffres précédents modulo 9
>
> Exemple : 123 142121 8

### Réaliser une plate-forme web

Permettant les interactions suivantes :

- Un client pourra ouvrir son compte ;
- Un client pourra rattacher une carte de fidélité délivrée dans nos locaux ;
- Un client pourra pourra afficher des informations sur ses cartes de fidélité ;
- Un membre du staff pourra trouver un client d'après un numéro de carte.

> Note :
>
> Nous ne souhaitons pas dans l'immédiat gérer les informations relatives aux services associés à ces cartes (affichage des visites, scores ou autres) bien que cela soit évidemment un "plus".

## Base de départ

### IHM

Schéma du site web et de ses interactions avec les utilisateurs

#### Accueil

- connexion
- inscription
- les offres du moment (carrousel)
- présentation du club Shinigami Laser


#### Staff

- recherche client par code carte, (par nom)+
- créer des offres
- (commander des cartes)

#### Joueur

- Afficher les infos de la carte de fidélité
- Rattacher une carte de fidélité
- (afficher les scores)+
- (afficher les visites)+

### Les rôles

ROLE_STAFF, ROLE_PLAYER, ROLE_ADMIN

### Fonctionnalités

 - ouverture de compte client gérée par token (B) v. userbundle / swiftmailer
 - rechercher un client avec un numéro de carte (S)
 - mise en place paiement paypal (B)
 - génération du cardCode du client (B)
 - upload image dans formulaire sous forme de service (S)

 Feuille de route à créer....

### Formulaires

- Inscription / avec champ paiement
- Connexion / Authentification
- Rattacher une carte à un compte client
- rechercher un client avec un numéro de carte
- création d'offres


### Ambiguités

> "Nous ne souhaitons pas dans l'immédiat gérer les informations relatives aux services associés à ces cartes (affichage des visites, scores ou autres) bien que cela soit évidemment un "plus"."

On doit pouvoir afficher ces informations mais pour l'instant il n'est pas prévu d'interaction.
