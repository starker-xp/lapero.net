<?php

namespace Starkerxp\StructureBundle\Tests;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Starkerxp\UtilisateurBundle\Entity\Utilisateur;

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
}
