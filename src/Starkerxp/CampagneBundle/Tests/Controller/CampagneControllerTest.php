<?php

namespace Starkerxp\CampagneBundle\Tests\Controller;

use Starkerxp\StructureBundle\Tests\WebTest;

class CampagneControllerTest extends WebTest
{

    /**
     * @group campagne
     * @group post
     * @group controller
     */
    public function testPostValide()
    {
        $this->loadFixtureFiles(['@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/DefaultUtilisateur.yml',]);
        $data = [
            'name' => "Ma première campagne",
        ];
        $client = $this->getAuthClient();
        $client->request('POST', '/api/campagnes', $data);
        $response = $client->getResponse();
        $this->assertEquals(201, $response->getStatusCode());
        $manager = $this->getContainer()->get('starkerxp_campagne.manager.campagne');
        $campagnes = $manager->findAll();
        $this->assertCount(1, $campagnes);
    }

    /**
     * @group campagne
     * @group post
     * @group controller
     */
    public function testPostInvalide()
    {
        $this->loadFixtureFiles(['@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/DefaultUtilisateur.yml',]);
        $client = $this->getAuthClient();
        $client->request('POST', '/api/campagnes', []);
        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
        $body = json_decode($response->getContent(), true)['payload'];
        //$this->assertArrayHasKey("nom", $body); // Exemple
    }


    /**
     * @group campagne
     * @group put
     * @group controller
     */
    public function testPutValide()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/DefaultUtilisateur.yml',
                '@StarkerxpCampagneBundle/Tests/DataFixtures/CampagneManager/DefaultCampagne.yml',
            ]
        );
        $manager = $this->getContainer()->get('starkerxp_campagne.manager.campagne');
        $listeCampagnes = $manager->getRepository()->findAll();
        $this->assertCount(1, $listeCampagnes);
        $campagneDepart = $manager->toArray($listeCampagnes[0], ['name']);// Exemple
        $data = [
            'name'     => "Mon nom", //exemple
        ];
        $client = $this->getAuthClient();
        $client->request('PUT', '/api/campagnes/'.$listeCampagnes[0]->getId(), $data);
        $response = $client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
        $manager->clear();
        $campagnes = $manager->findAll();
        $this->assertCount(1, $campagnes);
        $campagneFinal = $manager->toArray($campagnes[0], ['name']);// Exemple
        $this->assertNotEquals($campagneDepart, $campagneFinal);
        $this->assertEquals($data, $campagneFinal);
    }

    /**
     * @group campagne
     * @group put
     * @group controller
     */
    public function testPutInvalide()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/DefaultUtilisateur.yml',
                '@StarkerxpCampagneBundle/Tests/DataFixtures/CampagneManager/DefaultCampagne.yml',
            ]
        );
        $manager = $this->getContainer()->get('starkerxp_campagne.manager.campagne');
        $listeCampagnes = $manager->getRepository()->findAll();
        $this->assertCount(1, $listeCampagnes);
        $client = $this->getAuthClient();
        $client->request('PUT', '/api/campagnes/'.$listeCampagnes[0]->getId(), []);
        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
        $body = json_decode($response->getContent(), true)['payload'];
        //$this->assertArrayHasKey("nom", $body); // Exemple

    }

    /**
     * @group campagne
     * @group put
     * @group controller
     */
    public function testPutSansResultat()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/DefaultUtilisateur.yml',
            ]
        );
        $data = [
            'name'     => "Mon nom", //exemple
        ];
        $client = $this->getAuthClient();
        $client->request('PUT', '/api/campagnes/404', $data);
        $response = $client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $body = json_decode($response->getContent(), true);
        $this->assertNotEmpty($body);
    }


    /**
     * @group campagne
     * @group cget
     * @group controller
     */
    public function testCGetValideAvecResultats()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/DefaultUtilisateur.yml',
                '@StarkerxpCampagneBundle/Tests/DataFixtures/CampagneManager/CampagneManager.yml',
            ]
        );
        $client = $this->getAuthClient();
        $client->request('GET', '/api/campagnes', []);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getContent(), true);
        $this->assertCount(10, $body);
        foreach ($body as $element) {
            //$this->assertArrayHasKey("nom", $body); // Exemple
        }
    }

    /**
     * @group campagne
     * @group cget
     * @group controller
     */
    public function testCGetValideSansResultat()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/DefaultUtilisateur.yml',
            ]
        );
        $client = $this->getAuthClient();
        $client->request('GET', '/api/campagnes', []);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getContent(), true);
        $this->assertCount(0, $body);
    }

    /**
     * @group campagne
     * @group cget
     * @group controller
     */
    public function testCGetInvalide()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/DefaultUtilisateur.yml',
            ]
        );
        $client = $this->getAuthClient();
        $client->request('GET', '/api/campagnes', ["filter_erreur" => "+h"]);
        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
    }

    /**
     * @group campagne
     * @group get
     * @group controller
     */
    public function testGetValideAvecResultats()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/DefaultUtilisateur.yml',
                '@StarkerxpCampagneBundle/Tests/DataFixtures/CampagneManager/CampagneManager.yml',
            ]
        );
        $manager = $this->getContainer()->get('starkerxp_campagne.manager.campagne');
        $listeCampagnes = $manager->getRepository()->findAll();
        $this->assertCount(10, $listeCampagnes);
        $client = $this->getAuthClient();
        $client->request('GET', '/api/campagnes/'.$listeCampagnes[0]->getId(), []);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getContent(), true);
        $this->assertCount(3, $body);
        $this->assertArrayHasKey("name", $body); // Exemple
        $this->assertArrayHasKey("status", $body); // Exemple
        $this->assertArrayHasKey("id", $body); // Exemple
    }

    /**
     * @group campagne
     * @group get
     * @group controller
     */
    public function testGetValideSansResultat()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/DefaultUtilisateur.yml',
            ]
        );
        $client = $this->getAuthClient();
        $client->request('GET', '/api/campagnes/404', []);
        $response = $client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $body = json_decode($response->getContent(), true);
        $this->assertNotEmpty($body);
    }

    /**
     * @group campagne
     * @group get
     * @group controller
     */
    public function testGetInvalide()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/DefaultUtilisateur.yml',
            ]
        );
        $client = $this->getAuthClient();
        $client->request('GET', '/api/campagnes/500', ["filter_erreur" => "+h"]);
        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
    }

    /**
     * @group campagne
     * @group delete
     * @group controller
     */
    public function testDeleteValide()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/DefaultUtilisateur.yml',
                '@StarkerxpCampagneBundle/Tests/DataFixtures/CampagneManager/DefaultCampagne.yml',
            ]
        );
        $manager = $this->getContainer()->get('starkerxp_campagne.manager.campagne');
        $listeCampagnes = $manager->getRepository()->findAll();
        $this->assertCount(1, $listeCampagnes);
        $client = $this->getAuthClient();
        $client->request('DELETE', '/api/campagnes/'.$listeCampagnes[0]->getId());
        $response = $client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
        $manager->clear();
        $campagnes = $manager->findAll();
        $this->assertCount(0, $campagnes);
    }

    /**
     * @group campagne
     * @group delete
     * @group controller
     */
    public function testDeleteSansResultat()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/DefaultUtilisateur.yml',
            ]
        );
        $client = $this->getAuthClient();
        $client->request('DELETE', '/api/campagnes/404', []);
        $response = $client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $body = json_decode($response->getContent(), true);
        $this->assertNotEmpty($body);
    }
}
