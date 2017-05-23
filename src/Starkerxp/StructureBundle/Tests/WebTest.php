<?php

namespace Starkerxp\StructureBundle\Tests;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Starkerxp\UtilisateurBundle\Entity\Utilisateur;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class WebTest extends WebTestCase
{
    protected $identifiant = "test@yopmail.com";
    protected $motDePasse = "motMotDePasse";

    /**
     * Erase all database data.
     */
    public function setUp()
    {
        $this->loadFixtureFiles([]);
    }

    /**
     * Retourne l'entity manager de la connexion defaut.
     *
     * @return \Doctrine\Common\Persistence\ObjectManager|object
     */
    public function getEntityManager()
    {
        return $this->getContainer()->get('doctrine')->getManager();
    }

    public function getAuthClient()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/login_check',
            [
                'identifiant' => $this->identifiant,
                'motDePasse'  => $this->motDePasse,
            ]
        );
        $dataHeader = json_decode($client->getResponse()->getContent(), true);

        $client = static::createClient();
        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $dataHeader['token']));
        return $client;
    }

    /**
     * Generates a URL from the given parameters.
     *
     * @param string $route         The name of the route
     * @param mixed  $parameters    An array of parameters
     * @param int    $referenceType The type of reference (one of the constants in UrlGeneratorInterface)
     *
     * @return string The generated URL
     *
     * @see UrlGeneratorInterface
     */
    protected function generateUrl($route, $parameters = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        return $this->getContainer()->get('router')->generate($route, $parameters, $referenceType);
    }
}
