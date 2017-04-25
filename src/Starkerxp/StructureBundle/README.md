###Fonctionnalitées:
- Génération de controller rest + tests unitaire
- Génération d'entité + tests unitaire
- Utilisation d'un manager unique dans le projet

## Outils de développement
### Générateur CRUD
Afin de simplifier les phases de développement il existe des commandes qui permettent de générer un ensemble de fichier autour de l'entite/controller désiré.

#### Génération d'une entité
```
php bin/console starkerxp:generate:entite Bundle:Entite
```
Après la génération d'une nouvelle entité, il faut aller ajouter une méthode `toArray` qui aura pour but de convertir l'entite sous un format tableau. Ces informations serviront dans l'API dédié à cette entité.
Ci-dessous un exemple avec l'entite `Template` :
```
class TemplateManager extends AbstractManager
{
    ...
    public function toArray(Template $object, $fields = [])
    {
        $array = [
            "id"      => $object->getId(),
             ...
        ];

        return $this->exportFields($array, $fields);

    }
    ...
}
 
```
Il est fortement recommandée de généré également le controller qui lui corresponds. N'oubliez pas de jouer les tests unitaires et de les maintenir à jours dans le cas d'ajout ou modifications du controller incriminné.
#### Génération d'un controller
```
php bin/console starkerxp:generate:controller Bundle:Controller
```

### Export de la base de données
Afin de pouvoir écrire des fichiers de tests fonctionnels ont a besoin de certaines données. Ce script permet de faire un dump au format YML de l'état de la base de données actuelle.
```
php bin/console starkerxp:dump-database
```
