# Polaroids & Photos
Génération des polaroids d'un coworker. Génération de la photo et d'un PDF permettant l'impression sur l'imprimante Canon SELPHY.

## Prérequis
- Serveur web Apache avec mod_rewrite activé.
- PHP 7.4 ou supérieur.
- [Composer](https://getcomposer.org/) pour gérer les dépendances PHP.

## Installation
1. Clonez le dépôt ou téléchargez le dossier `Photos-Polaroids-master` sur votre serveur.
2. Dans le répertoire du projet, exécutez `composer install` pour installer les dépendances PHP.

# Administration

Les réglages des polaroids sont disponibles dans Wordpress > Trombi > Réglages

# Fonctions

Accéder à la route `/{wpUserId}.json` pour afficher la liste des toutes les images disponibles pour un membre. 

Exemple : `https://photos.coworking-metz.fr/225.json`

```json
{
  "pdf": "https://photos.coworking-metz.fr/225.pdf", // Url du fichier PDF destiné aux impressions
  "photo": { // Urls de toutes les tailles de la photo du coworker brute (pas de cadre pola)
    "micro": "https://photos.coworking-metz.fr/photo/size/micro/225.jpg",
    "small": "https://photos.coworking-metz.fr/photo/size/small/225.jpg",
    "medium": "https://photos.coworking-metz.fr/photo/size/medium/225.jpg",
    "big": "https://photos.coworking-metz.fr/photo/size/big/225.jpg"
  },
  "polaroid": { // Urls de toutes les tailles du polaroid du coworker (avec badge d'ancienneté et fond événementiels)
    "micro": "https://photos.coworking-metz.fr/polaroid/size/micro/225.jpg",
    "small": "https://photos.coworking-metz.fr/polaroid/size/small/225.jpg",
    "medium": "https://photos.coworking-metz.fr/polaroid/size/medium/225.jpg",
    "big": "https://photos.coworking-metz.fr/polaroid/size/big/225.jpg"
  },
  "anonyme": {// Urls de toutes les tailles du polaroid anonyme du coworker (avec badge d'ancienneté et fond événementiels mais avec faux nom et photo par défaut)
    "micro": "https://photos.coworking-metz.fr/polaroid/size/micro/anonyme/225.jpg",
    "small": "https://photos.coworking-metz.fr/polaroid/size/small/anonyme/225.jpg",
    "medium": "https://photos.coworking-metz.fr/polaroid/size/medium/anonyme/225.jpg",
    "big": "https://photos.coworking-metz.fr/polaroid/size/big/anonyme/225.jpg"
  },
  "classic": { // Urls de toutes les tailles du polaroid classique du coworker (sans badge d'ancienneté ni fond événementiels)
    "micro": "https://photos.coworking-metz.fr/polaroid/size/micro/classic/225.jpg",
    "small": "https://photos.coworking-metz.fr/polaroid/size/small/classic/225.jpg",
    "medium": "https://photos.coworking-metz.fr/polaroid/size/medium/classic/225.jpg",
    "big": "https://photos.coworking-metz.fr/polaroid/size/big/classic/225.jpg"
  }
}
```
