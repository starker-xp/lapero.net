<?php

namespace Starkerxp\UtilisateurBundle\Tests\Controller;

use Starkerxp\StructureBundle\Tests\WebTest;

class UtilisateurControllerTest extends WebTest
{

    /**
     * @group utilisateur
     * @group post
     * @group controller
     */
    public function testPostValide()
    {
        $data = [
            //'nom'     => "Mon nom", //exemple
        ];
        $client = static::createClient();
        $client->request('POST', '/utilisateurs', $data);
        $response = $client->getResponse();
        $this->assertEquals(201, $response->getStatusCode());
        $manager = $this->getContainer()->get('starkerxp_utilisateur.manager.utilisateur');
        $Utilisateurs = $manager->findAll();
        $this->assertCount(1, $Utilisateurs);
    }

    /**
     * @group utilisateur
     * @group post
     * @group controller
     */
    public function testPostInvalide()
    {
        $client = static::createClient();
        $client->request('POST', '/utilisateurs', []);
        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
        $body = json_decode($response->getContent(), true)['payload'];
        //$this->assertArrayHasKey("nom", $body); // Exemple
    }


    /**
     * @group utilisateur
     * @group put
     * @group controller
     */
    public function testPutValide()
    {
        $this->loadFixtureFiles(['@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/DefaultUtilisateur.yml']);
        $manager = $this->getContainer()->get('starkerxp_utilisateur.manager.utilisateur');
        $listeUtilisateurs = $manager->getRepository()->findAll();
        $this->assertCount(1, $listeUtilisateurs);
        $UtilisateurDepart = $manager->toArray($listeUtilisateurs[0], [/*'nom'*/]);// Exemple
        $data = [
            //'nom'     => "Mon nom", //exemple
        ];
        $client = static::createClient();
        $client->request('PUT', '/utilisateurs/'.$listeUtilisateurs[0]->getId(), $data);
        $response = $client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
        $manager->clear();
        $Utilisateurs = $manager->findAll();
        $this->assertCount(1, $Utilisateurs);
        $UtilisateurFinal = $manager->toArray($Utilisateurs[0], [/*'nom'*/]);// Exemple
        $this->assertNotEquals($UtilisateurDepart, $UtilisateurFinal);
        $this->assertEquals($data, $UtilisateurFinal);
    }

    /**
     * @group utilisateur
     * @group put
     * @group controller
     */
    public function testPutInvalide()
    {
        $this->loadFixtureFiles(['@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/DefaultUtilisateur.yml']);
        $manager = $this->getContainer()->get('starkerxp_utilisateur.manager.utilisateur');
        $listeUtilisateurs = $manager->getRepository()->findAll();
        $this->assertCount(1, $listeUtilisateurs);
        $client = static::createClient();
        $client->request('PUT', '/utilisateurs/'.$listeUtilisateurs[0]->getId(), []);
        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
        $body = json_decode($response->getContent(), true)['payload'];
        //$this->assertArrayHasKey("nom", $body); // Exemple
        
    }

    /**
     * @group utilisateur
     * @group put
     * @group controller
     */
    public function testPutSansResultat()
    {
        $data = [
            //'nom'     => "Mon nom", //exemple
        ];
        $client = static::createClient();
        $client->request('PUT', '/utilisateurs/404', $data);
        $response = $client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $body = json_decode($response->getContent(), true);
        $this->assertNotEmpty($body);
    }


    /**
     * @group utilisateur
     * @group cget
     * @group controller
     */
    public function testCGetValideAvecResultats()
    {
        $this->loadFixtureFiles(['@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/UtilisateurManager.yml']);
        $client = static::createClient();
        $client->request('GET', '/utilisateurs', []);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getContent(), true);
        $this->assertCount(10, $body);
        foreach ($body as $element) {
            //$this->assertArrayHasKey("nom", $body); // Exemple
        }
    }

    /**
     * @group utilisateur
     * @group cget
     * @group controller
     */
    public function testCGetValideSansResultat()
    {
        $client = static::createClient();
        $client->request('GET', '/utilisateurs', []);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getContent(), true);
        $this->assertCount(0, $body);
    }

    /**
     * @group utilisateur
     * @group cget
     * @group controller
     */
    public function testCGetInvalide()
    {
        $client = static::createClient();
        $client->request('GET', '/utilisateurs', ["filter_erreur" => "+h"]);
        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
    }

    /**
     * @group utilisateur
     * @group get
     * @group controller
     */
    public function testGetValideAvecResultats()
    {
        $this->loadFixtureFiles(['@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/UtilisateurManager.yml']);
        $manager = $this->getContainer()->get('starkerxp_utilisateur.manager.utilisateur');
        $listeUtilisateurs = $manager->getRepository()->findAll();
        $this->assertCount(10, $listeUtilisateurs);
        $client = static::createClient();
        $client->request('GET', '/utilisateurs/'.$listeUtilisateurs[0]->getId(), []);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getContent(), true);
        $this->assertCount(5, $body);
        //$this->assertArrayHasKey("nom", $body); // Exemple
    }

    /**
     * @group utilisateur
     * @group get
     * @group controller
     */
    public function testGetValideSansResultat()
    {
        $client = static::createClient();
        $client->request('GET', '/utilisateurs/404', []);
        $response = $client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $body = json_decode($response->getContent(), true);
        $this->assertNotEmpty($body);
    }

    /**
     * @group utilisateur
     * @group get
     * @group controller
     */
    public function testGetInvalide()
    {
        $client = static::createClient();
        $client->request('GET', '/utilisateurs/500', ["filter_erreur" => "+h"]);
        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
    }

    /**
     * @group utilisateur
     * @group delete
     * @group controller
     */
    public function testDeleteValide()
    {
        $this->loadFixtureFiles(['@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/DefaultUtilisateur.yml']);
        $manager = $this->getContainer()->get('starkerxp_utilisateur.manager.utilisateur');
        $listeUtilisateurs = $manager->getRepository()->findAll();
        $this->assertCount(1, $listeUtilisateurs);
        $client = static::createClient();
        $client->request('DELETE', '/utilisateurs/'.$listeUtilisateurs[0]->getId());
        $response = $client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
        $manager->clear();
        $Utilisateurs = $manager->findAll();
        $this->assertCount(0, $Utilisateurs);
    }

    /**
     * @group utilisateur
     * @group delete
     * @group controller
     */
    public function testDeleteSansResultat()
    {
        $client = static::createClient();
        $client->request('DELETE', '/utilisateurs/404', []);
        $response = $client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $body = json_decode($response->getContent(), true);
        $this->assertNotEmpty($body);
    }
}
