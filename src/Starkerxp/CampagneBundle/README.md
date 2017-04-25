# Gestionnaire de campagne


# Ajouter un render

* Créer dans le dossier `src\Starkerxp\CampagneBundle\Render`
* Créer une nouvelle classe qui implémente `AbstractRender` :
```
namespace Qdk\CampagneBundle\Services\Query;

class MonRenderRender extends Starkerxp\CampagneBundle\Render\AbstractRender
{
}
```

* Dans le fichier `src\Starkerxp\CampagneBundle\Ressources\config\renders.yml` ajouter un nouveau service avec le tag `starkerxp_campagne.render`
```
services:
    starkerxp_campagne.render.mon_render:
        class: Starkerxp\CampagneBundle\Render\MonRenderRender
        tags:
          - { name: starkerxp_campagne.render }
```
* Créer dans le dossier `src\Starkerxp\CampagneBundle\Tests\Render` le test unitaire de votre nouveau render


# Lancer les tests unitaires

```
php /usr/local/phpunit src/Starkerxp/TemplateBundle/Tests/
php /usr/local/phpunit src/Starkerxp/CampagneBundle/Tests/
```
