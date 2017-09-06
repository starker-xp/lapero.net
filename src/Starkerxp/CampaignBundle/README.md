# Gestionnaire de campaign


# Ajouter un render

* Créer dans le dossier `src\Starkerxp\CampaignBundle\Render`
* Créer une nouvelle classe qui implémente `AbstractRender` :
```
namespace Qdk\CampaignBundle\Services\Query;

class MonRenderRender extends Starkerxp\CampaignBundle\Render\AbstractRender
{
}
```

* Dans le fichier `src\Starkerxp\CampaignBundle\Ressources\config\renders.yml` ajouter un nouveau service avec le tag `starkerxp_campaign.render`
```
services:
    starkerxp_campaign.render.mon_render:
        class: Starkerxp\CampaignBundle\Render\MonRenderRender
        tags:
          - { name: starkerxp_campaign.render }
```
* Créer dans le dossier `src\Starkerxp\CampaignBundle\Tests\Render` le test unitaire de votre nouveau render


# Lancer les tests unitaires

```
php /usr/local/bin/phpunit src/Starkerxp/TemplateBundle/Tests/
php /usr/local/bin/phpunit src/Starkerxp/CampaignBundle/Tests/

# http://symfony.com/blog/how-to-solve-phpunit-issues-in-symfony-3-2-applications
php ./vendor/bin/phpunit src/Starkerxp/CampaignBundle/Tests/
```
