###Fonctionnalitées:
- Génération de controller rest + tests unitaire
- Génération d'entité + tests unitaire
- Utilisation d'un manager unique dans le projet

## Outils de développement
### Générateur CRUD
Afin de simplifier les phases de développement il existe des commandes qui permettent de générer un ensemble de fichier autour de l'entite/controller désiré.
```
php bin/console starkerxp:generate:entite Bundle:Entite
php bin/console starkerxp:generate:controller Bundle:Controller
```

### Export de la base de données
Afin de pouvoir écrire des fichiers de tests fonctionnels ont a besoin de certaines données. Ce script permet de faire un dump au format YML de l'état de la base de données actuelle.
```
php bin/console starkerxp:dump-database
```
