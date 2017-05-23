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
                '@StarkerxpCampagneBundle/Tests/DataFixtures/CampagneManager/DefaultCampagne.yml',
            ]
        );
        $data = [
            //'nom'     => "Mon nom", //exemple
        ];
        $url = $this->generateUrl(
            'starkerxp_campagne.event.post',
            [
                "campagne_id" => $this->getCampagneId(),
            ]
        );
        $client = $this->getAuthClient();
        $client->request('POST', $url, $data);
        $response = $client->getResponse();
        $this->assertEquals(201, $response->getStatusCode());
        $manager = $this->getContainer()->get('starkerxp_campagne.manager.event');
        $events = $manager->findAll();
        $this->assertCount(1, $events);
    }

    protected function getCampagneId()
    {
        $repositoryCampagne = $this->getEntityManager()->getRepository("StarkerxpCampagneBundle:Campagne");
        $resultats = $repositoryCampagne->findAll();

        return $resultats[0]->getId();
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
                '@StarkerxpCampagneBundle/Tests/DataFixtures/CampagneManager/DefaultCampagne.yml',
            ]
        );
        $url = $this->generateUrl(
            'starkerxp_campagne.event.post',
            [
                "campagne_id" => $this->getCampagneId(),
            ]
        );
        $client = $this->getAuthClient();
        $client->request('POST', $url, []);
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
                '@StarkerxpCampagneBundle/Tests/DataFixtures/CampagneManager/DefaultCampagne.yml',
                '@StarkerxpCampagneBundle/Tests/DataFixtures/EventManager/DefaultEvent.yml',
            ]
        );

        $manager = $this->getContainer()->get('starkerxp_campagne.manager.event');
        $listeEvents = $manager->getRepository()->findAll();
        $this->assertCount(1, $listeEvents);
        $eventDepart = $manager->toArray($listeEvents[0], [/*'nom'*/]);// Exemple
        $data = [
            //'nom'     => "Mon nom", //exemple
        ];
        $url = $this->generateUrl(
            'starkerxp_campagne.event.put',
            [
                "campagne_id" => $this->getCampagneId(),
                "id"    => $listeEvents[0]->getId(),
            ]
        );
        $client = $this->getAuthClient();
        $client->request('PUT', $url, $data);
        $response = $client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
        $manager->clear();
        $events = $manager->findAll();
        $this->assertCount(1, $events);
        $eventFinal = $manager->toArray($events[0], [/*'nom'*/]);// Exemple
        $this->assertNotEquals($eventDepart, $eventFinal);
        $this->assertEquals($data, $eventFinal);
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
                '@StarkerxpCampagneBundle/Tests/DataFixtures/CampagneManager/DefaultCampagne.yml',
                '@StarkerxpCampagneBundle/Tests/DataFixtures/EventManager/DefaultEvent.yml',
            ]
        );
        $manager = $this->getContainer()->get('starkerxp_campagne.manager.event');
        $listeEvents = $manager->getRepository()->findAll();
        $this->assertCount(1, $listeEvents);
        $url = $this->generateUrl(
            'starkerxp_campagne.event.put',
            [
                "campagne_id" => $this->getCampagneId(),
                "id"    => $listeEvents[0]->getId(),
            ]
        );
        $client = $this->getAuthClient();
        $client->request('PUT', $url, []);
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
                '@StarkerxpCampagneBundle/Tests/DataFixtures/CampagneManager/DefaultCampagne.yml',
            ]
        );
        $data = [
            //'nom'     => "Mon nom", //exemple
        ];
        $url = $this->generateUrl(
            'starkerxp_campagne.event.put',
            [
                "campagne_id" => $this->getCampagneId(),
                "id"    => 404,
            ]
        );
        $client = $this->getAuthClient();
        $client->request('PUT', $url, $data);
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
                '@StarkerxpCampagneBundle/Tests/DataFixtures/CampagneManager/DefaultCampagne.yml',
                '@StarkerxpCampagneBundle/Tests/DataFixtures/EventManager/EventManager.yml',
            ]
        );
        $url = $this->generateUrl(
            'starkerxp_campagne.event.cget',
            [
                "campagne_id" => $this->getCampagneId(),
            ]
        );
        $client = $this->getAuthClient();
        $client->request('GET', $url, []);
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
                '@StarkerxpCampagneBundle/Tests/DataFixtures/CampagneManager/DefaultCampagne.yml',
            ]
        );
        $url = $this->generateUrl(
            'starkerxp_campagne.event.cget',
            [
                "campagne_id" => $this->getCampagneId(),
            ]
        );
        $client = $this->getAuthClient();
        $client->request('GET', $url, []);
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
                '@StarkerxpCampagneBundle/Tests/DataFixtures/CampagneManager/DefaultCampagne.yml',
            ]
        );
        $url = $this->generateUrl(
            'starkerxp_campagne.event.cget',
            [
                "campagne_id" => $this->getCampagneId(),
            ]
        );
        $client = $this->getAuthClient();
        $client->request('GET', $url, ["filter_erreur" => "+h"]);
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
                '@StarkerxpCampagneBundle/Tests/DataFixtures/CampagneManager/DefaultCampagne.yml',
                '@StarkerxpCampagneBundle/Tests/DataFixtures/EventManager/EventManager.yml',
            ]
        );
        $manager = $this->getContainer()->get('starkerxp_campagne.manager.event');
        $listeEvents = $manager->getRepository()->findAll();
        $this->assertCount(10, $listeEvents);
        $url = $this->generateUrl(
            'starkerxp_campagne.event.get',
            [
                "campagne_id" => $this->getCampagneId(),
                "id"    => $listeEvents[0]->getId(),
            ]
        );
        $client = $this->getAuthClient();
        $client->request('GET', $url, []);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getContent(), true);
        $this->assertCount(2, $body);
        $this->assertArrayHasKey("id", $body); // Exemple
        $this->assertArrayHasKey("campagne_id", $body); // Exemple
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
                '@StarkerxpCampagneBundle/Tests/DataFixtures/CampagneManager/DefaultCampagne.yml',
            ]
        );
        $url = $this->generateUrl(
            'starkerxp_campagne.event.get',
            [
                "campagne_id" => $this->getCampagneId(),
                "id"    => 404,
            ]
        );
        $client = $this->getAuthClient();
        $client->request('GET', $url, []);
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
                '@StarkerxpCampagneBundle/Tests/DataFixtures/CampagneManager/DefaultCampagne.yml',
            ]
        );
        $url = $this->generateUrl(
            'starkerxp_campagne.event.get',
            [
                "campagne_id" => $this->getCampagneId(),
                "id"    => 500,
            ]
        );
        $client = $this->getAuthClient();
        $client->request('GET', $url, ["filter_erreur" => "+h"]);
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
                '@StarkerxpCampagneBundle/Tests/DataFixtures/CampagneManager/DefaultCampagne.yml',
                '@StarkerxpCampagneBundle/Tests/DataFixtures/EventManager/DefaultEvent.yml',
            ]
        );
        $manager = $this->getContainer()->get('starkerxp_campagne.manager.event');
        $listeEvents = $manager->getRepository()->findAll();
        $this->assertCount(1, $listeEvents);
        $url = $this->generateUrl(
            'starkerxp_campagne.event.delete',
            [
                "campagne_id" => $this->getCampagneId(),
                "id"    => $listeEvents[0]->getId(),
            ]
        );
        $client = $this->getAuthClient();
        $client->request('DELETE', $url);
        $response = $client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
        $manager->clear();
        $events = $manager->findAll();
        $this->assertCount(0, $events);
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
                '@StarkerxpCampagneBundle/Tests/DataFixtures/CampagneManager/DefaultCampagne.yml',
            ]
        );
        $url = $this->generateUrl(
            'starkerxp_campagne.event.delete',
            [
                "campagne_id" => $this->getCampagneId(),
                "id"    => 404,
            ]
        );
        $client = $this->getAuthClient();
        $client->request('DELETE', $url, []);
        $response = $client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $body = json_decode($response->getContent(), true);
        $this->assertNotEmpty($body);
    }
}
