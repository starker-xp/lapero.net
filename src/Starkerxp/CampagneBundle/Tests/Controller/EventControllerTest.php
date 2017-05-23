<?php

namespace Starkerxp\CampagneBundle\Tests\Controller;

use Starkerxp\StructureBundle\Tests\WebTest;

class EventControllerTest extends WebTest
{

    /**
     * @group event
     * @group post
     * @group controller
     */
    public function testPostValide()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/DefaultUtilisateur.yml',
            ]
        );
        $data = [
            //'nom'     => "Mon nom", //exemple
        ];
        $client = $this->getAuthClient();
        $client->request('POST', '/campagnes/events', $data);
        $response = $client->getResponse();
        $this->assertEquals(201, $response->getStatusCode());
        $manager = $this->getContainer()->get('starkerxp_campagne.manager.event');
        $Events = $manager->findAll();
        $this->assertCount(1, $Events);
    }

    /**
     * @group event
     * @group post
     * @group controller
     */
    public function testPostInvalide()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/DefaultUtilisateur.yml',
            ]
        );
        $client = $this->getAuthClient();
        $client->request('POST', '/campagnes/events', []);
        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
        $body = json_decode($response->getContent(), true)['payload'];
        //$this->assertArrayHasKey("nom", $body); // Exemple
    }


    /**
     * @group event
     * @group put
     * @group controller
     */
    public function testPutValide()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/DefaultUtilisateur.yml',
                '@StarkerxpCampagneBundle/Tests/DataFixtures/EventManager/DefaultEvent.yml',
            ]
        );

        $manager = $this->getContainer()->get('starkerxp_campagne.manager.event');
        $listeEvents = $manager->getRepository()->findAll();
        $this->assertCount(1, $listeEvents);
        $EventDepart = $manager->toArray($listeEvents[0], [/*'nom'*/]);// Exemple
        $data = [
            //'nom'     => "Mon nom", //exemple
        ];
        $client = $this->getAuthClient();
        $client->request('PUT', '/campagnes/events/'.$listeEvents[0]->getId(), $data);
        $response = $client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
        $manager->clear();
        $Events = $manager->findAll();
        $this->assertCount(1, $Events);
        $EventFinal = $manager->toArray($Events[0], [/*'nom'*/]);// Exemple
        $this->assertNotEquals($EventDepart, $EventFinal);
        $this->assertEquals($data, $EventFinal);
    }

    /**
     * @group event
     * @group put
     * @group controller
     */
    public function testPutInvalide()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/DefaultUtilisateur.yml',
                '@StarkerxpCampagneBundle/Tests/DataFixtures/EventManager/DefaultEvent.yml',
            ]
        );
        $manager = $this->getContainer()->get('starkerxp_campagne.manager.event');
        $listeEvents = $manager->getRepository()->findAll();
        $this->assertCount(1, $listeEvents);
        $client = $this->getAuthClient();
        $client->request('PUT', '/campagnes/events/'.$listeEvents[0]->getId(), []);
        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
        $body = json_decode($response->getContent(), true)['payload'];
        //$this->assertArrayHasKey("nom", $body); // Exemple

    }

    /**
     * @group event
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
            //'nom'     => "Mon nom", //exemple
        ];
        $client = $this->getAuthClient();
        $client->request('PUT', '/campagnes/events/404', $data);
        $response = $client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $body = json_decode($response->getContent(), true);
        $this->assertNotEmpty($body);
    }


    /**
     * @group event
     * @group cget
     * @group controller
     */
    public function testCGetValideAvecResultats()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/DefaultUtilisateur.yml',
                '@StarkerxpCampagneBundle/Tests/DataFixtures/EventManager/EventManager.yml',
            ]
        );
        $client = $this->getAuthClient();
        $client->request('GET', '/campagnes/events', []);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getContent(), true);
        $this->assertCount(10, $body);
        foreach ($body as $element) {
            //$this->assertArrayHasKey("nom", $body); // Exemple
        }
    }

    /**
     * @group event
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
        $client->request('GET', '/campagnes/events', []);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getContent(), true);
        $this->assertCount(0, $body);
    }

    /**
     * @group event
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
        $client->request('GET', '/campagnes/events', ["filter_erreur" => "+h"]);
        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
    }

    /**
     * @group event
     * @group get
     * @group controller
     */
    public function testGetValideAvecResultats()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/DefaultUtilisateur.yml',
                '@StarkerxpCampagneBundle/Tests/DataFixtures/EventManager/EventManager.yml',
            ]
        );
        $manager = $this->getContainer()->get('starkerxp_campagne.manager.event');
        $listeEvents = $manager->getRepository()->findAll();
        $this->assertCount(10, $listeEvents);
        $client = $this->getAuthClient();
        $client->request('GET', '/campagnes/events/'.$listeEvents[0]->getId(), []);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getContent(), true);
        $this->assertCount(5, $body);
        //$this->assertArrayHasKey("nom", $body); // Exemple
    }

    /**
     * @group event
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
        $client->request('GET', '/campagnes/events/404', []);
        $response = $client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $body = json_decode($response->getContent(), true);
        $this->assertNotEmpty($body);
    }

    /**
     * @group event
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
        $client->request('GET', '/campagnes/events/500', ["filter_erreur" => "+h"]);
        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
    }

    /**
     * @group event
     * @group delete
     * @group controller
     */
    public function testDeleteValide()
    {

        $this->loadFixtureFiles(
            [
                '@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/DefaultUtilisateur.yml',
                '@StarkerxpCampagneBundle/Tests/DataFixtures/EventManager/DefaultEvent.yml',
            ]
        );
        $manager = $this->getContainer()->get('starkerxp_campagne.manager.event');
        $listeEvents = $manager->getRepository()->findAll();
        $this->assertCount(1, $listeEvents);
        $client = $this->getAuthClient();
        $client->request('DELETE', '/campagnes/events/'.$listeEvents[0]->getId());
        $response = $client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
        $manager->clear();
        $Events = $manager->findAll();
        $this->assertCount(0, $Events);
    }

    /**
     * @group event
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
        $client->request('DELETE', '/campagnes/events/404', []);
        $response = $client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $body = json_decode($response->getContent(), true);
        $this->assertNotEmpty($body);
    }
}
