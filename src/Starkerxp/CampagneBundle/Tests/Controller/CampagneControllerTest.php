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
        $data = [
            //'nom'     => "Mon nom", //exemple
        ];
        $client = static::createClient();
        $client->request('POST', '/campagnes', $data);
        $response = $client->getResponse();
        $this->assertEquals(201, $response->getStatusCode());
        $manager = $this->getContainer()->get('starkerxp_campagne.manager.campagne');
        $Campagnes = $manager->findAll();
        $this->assertCount(1, $Campagnes);
    }

    /**
     * @group campagne
     * @group post
     * @group controller
     */
    public function testPostInvalide()
    {
        $client = static::createClient();
        $client->request('POST', '/campagnes', []);
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
        $this->loadFixtureFiles(['@StarkerxpCampagneBundle/Tests/DataFixtures/CampagneManager/DefaultCampagne.yml']);
        $manager = $this->getContainer()->get('starkerxp_campagne.manager.campagne');
        $listeCampagnes = $manager->getRepository()->findAll();
        $this->assertCount(1, $listeCampagnes);
        $CampagneDepart = $manager->toArray($listeCampagnes[0], [/*'nom'*/]);// Exemple
        $data = [
            //'nom'     => "Mon nom", //exemple
        ];
        $client = static::createClient();
        $client->request('PUT', '/campagnes/'.$listeCampagnes[0]->getId(), $data);
        $response = $client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
        $manager->clear();
        $Campagnes = $manager->findAll();
        $this->assertCount(1, $Campagnes);
        $CampagneFinal = $manager->toArray($Campagnes[0], [/*'nom'*/]);// Exemple
        $this->assertNotEquals($CampagneDepart, $CampagneFinal);
        $this->assertEquals($data, $CampagneFinal);
    }

    /**
     * @group campagne
     * @group put
     * @group controller
     */
    public function testPutInvalide()
    {
        $this->loadFixtureFiles(['@StarkerxpCampagneBundle/Tests/DataFixtures/CampagneManager/DefaultCampagne.yml']);
        $manager = $this->getContainer()->get('starkerxp_campagne.manager.campagne');
        $listeCampagnes = $manager->getRepository()->findAll();
        $this->assertCount(1, $listeCampagnes);
        $client = static::createClient();
        $client->request('PUT', '/campagnes/'.$listeCampagnes[0]->getId(), []);
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
        $data = [
            //'nom'     => "Mon nom", //exemple
        ];
        $client = static::createClient();
        $client->request('PUT', '/campagnes/404', $data);
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
        $this->loadFixtureFiles(['@StarkerxpCampagneBundle/Tests/DataFixtures/CampagneManager/CampagneManager.yml']);
        $client = static::createClient();
        $client->request('GET', '/campagnes', []);
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
        $client = static::createClient();
        $client->request('GET', '/campagnes', []);
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
        $client = static::createClient();
        $client->request('GET', '/campagnes', ["filter_erreur" => "+h"]);
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
        $this->loadFixtureFiles(['@StarkerxpCampagneBundle/Tests/DataFixtures/CampagneManager/CampagneManager.yml']);
        $manager = $this->getContainer()->get('starkerxp_campagne.manager.campagne');
        $listeCampagnes = $manager->getRepository()->findAll();
        $this->assertCount(10, $listeCampagnes);
        $client = static::createClient();
        $client->request('GET', '/campagnes/'.$listeCampagnes[0]->getId(), []);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getContent(), true);
        $this->assertCount(5, $body);
        //$this->assertArrayHasKey("nom", $body); // Exemple
    }

    /**
     * @group campagne
     * @group get
     * @group controller
     */
    public function testGetValideSansResultat()
    {
        $client = static::createClient();
        $client->request('GET', '/campagnes/404', []);
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
        $client = static::createClient();
        $client->request('GET', '/campagnes/500', ["filter_erreur" => "+h"]);
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
        $this->loadFixtureFiles(['@StarkerxpCampagneBundle/Tests/DataFixtures/CampagneManager/DefaultCampagne.yml']);
        $manager = $this->getContainer()->get('starkerxp_campagne.manager.campagne');
        $listeCampagnes = $manager->getRepository()->findAll();
        $this->assertCount(1, $listeCampagnes);
        $client = static::createClient();
        $client->request('DELETE', '/campagnes/'.$listeCampagnes[0]->getId());
        $response = $client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
        $manager->clear();
        $Campagnes = $manager->findAll();
        $this->assertCount(0, $Campagnes);
    }

    /**
     * @group campagne
     * @group delete
     * @group controller
     */
    public function testDeleteSansResultat()
    {
        $client = static::createClient();
        $client->request('DELETE', '/campagnes/404', []);
        $response = $client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $body = json_decode($response->getContent(), true);
        $this->assertNotEmpty($body);
    }
}
