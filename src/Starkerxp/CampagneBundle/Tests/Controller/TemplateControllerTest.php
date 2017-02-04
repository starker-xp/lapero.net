<?php

namespace Starkerxp\CampagneBundle\Tests\Manager;

use Starkerxp\StructureBundle\Tests\WebTest;

class TemplateControllerTest extends WebTest
{

    /**
     * @group template
     * @group post
     * @group controller
     */
    public function testPostValide()
    {
        $data = [
            'nom'     => "Mon nom",
            'sujet'   => "Mon sujet",
            'message' => "Mon message",
            'type'    => "email",
        ];
        $client = static::createClient();
        $client->request('POST', '/templates', $data);
        $response = $client->getResponse();
        $this->assertEquals(201, $response->getStatusCode());
        $manager = $this->getContainer()->get('starkerxp_campagne.manager.template');
        $templates = $manager->findAll();
        $this->assertCount(1, $templates);
    }

    /**
     * @group template
     * @group post
     * @group controller
     */
    public function testPostInvalide()
    {
        $client = static::createClient();
        $client->request('POST', '/templates', []);
        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
        $body = json_decode($response->getContent(), true)['payload'];
        $this->assertArrayHasKey("nom", $body);
        $this->assertArrayHasKey("sujet", $body);
        $this->assertArrayHasKey("message", $body);
        $this->assertArrayNotHasKey("type", $body);
    }


    /**
     * @group template
     * @group put
     * @group controller
     */
    public function testPutValide()
    {
        $this->loadFixtureFiles(['@StarkerxpCampagneBundle/Tests/DataFixtures/TemplateManager/DefaultTemplate.yml']);
        $manager = $this->getContainer()->get('starkerxp_campagne.manager.template');
        $listeTemplates = $manager->getRepository()->findAll();
        $this->assertCount(1, $listeTemplates);
        $templateDepart = $manager->toArray($listeTemplates[0], ['nom', 'sujet', 'message']);
        $data = [
            'nom'     => "Mon nom",
            'sujet'   => "Mon sujet",
            'message' => "Mon message",
            'type'    => "email",
        ];
        $client = static::createClient();
        $client->request('PUT', '/templates/'.$listeTemplates[0]->getId(), $data);
        $response = $client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
        $manager->clear();
        $templates = $manager->findAll();
        $this->assertCount(1, $templates);
        $templateFinal = $manager->toArray($templates[0], ['nom', 'sujet', 'message', 'type']);
        $this->assertNotEquals($templateDepart, $templateFinal);
        $this->assertEquals($data, $templateFinal);
    }

    /**
     * @group template
     * @group put
     * @group controller
     */
    public function testPutInvalide()
    {
        $this->loadFixtureFiles(['@StarkerxpCampagneBundle/Tests/DataFixtures/TemplateManager/DefaultTemplate.yml']);
        $manager = $this->getContainer()->get('starkerxp_campagne.manager.template');
        $listeTemplates = $manager->getRepository()->findAll();
        $this->assertCount(1, $listeTemplates);
        $client = static::createClient();
        $client->request('PUT', '/templates/'.$listeTemplates[0]->getId(), []);
        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
        $body = json_decode($response->getContent(), true)['payload'];
        $this->assertArrayHasKey("nom", $body);
        $this->assertArrayHasKey("sujet", $body);
        $this->assertArrayHasKey("message", $body);
        $this->assertArrayNotHasKey("type", $body);
    }

    /**
     * @group template
     * @group put
     * @group controller
     */
    public function testPutSansResultat()
    {
        $data = [
            'nom'     => "Mon nom",
            'sujet'   => "Mon sujet",
            'message' => "Mon message",
            'type'    => "email",
        ];
        $client = static::createClient();
        $client->request('PUT', '/templates/404', $data);
        $response = $client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $body = json_decode($response->getContent(), true);
        $this->assertNotEmpty($body);
    }


    /**
     * @group template
     * @group cget
     * @group controller
     */
    public function testCGetValideAvecResultats()
    {
        $this->loadFixtureFiles(['@StarkerxpCampagneBundle/Tests/DataFixtures/TemplateManager/TemplateManager.yml']);
        $client = static::createClient();
        $client->request('GET', '/templates', []);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getContent(), true);
        $this->assertCount(10, $body);
        foreach ($body as $element) {
            $this->assertArrayHasKey("id", $element);
            $this->assertArrayHasKey("nom", $element);
            $this->assertArrayHasKey("sujet", $element);
            $this->assertArrayHasKey("message", $element);
            $this->assertArrayHasKey("type", $element);
        }
    }

    /**
     * @group template
     * @group cget
     * @group controller
     */
    public function testCGetValideSansResultat()
    {
        $client = static::createClient();
        $client->request('GET', '/templates', []);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getContent(), true);
        $this->assertCount(0, $body);
    }

    /**
     * @group template
     * @group cget
     * @group controller
     */
    public function testCGetInvalide()
    {
        $client = static::createClient();
        $client->request('GET', '/templates', ["filter_erreur" => "+h"]);
        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
    }

    /**
     * @group template
     * @group get
     * @group controller
     */
    public function testGetValideAvecResultats()
    {
        $this->loadFixtureFiles(['@StarkerxpCampagneBundle/Tests/DataFixtures/TemplateManager/TemplateManager.yml']);
        $manager = $this->getContainer()->get('starkerxp_campagne.manager.template');
        $listeTemplates = $manager->getRepository()->findAll();
        $this->assertCount(10, $listeTemplates);
        $client = static::createClient();
        $client->request('GET', '/templates/'.$listeTemplates[0]->getId(), []);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getContent(), true);
        $this->assertCount(5, $body);
        $this->assertArrayHasKey("id", $body);
        $this->assertArrayHasKey("nom", $body);
        $this->assertArrayHasKey("sujet", $body);
        $this->assertArrayHasKey("message", $body);
        $this->assertArrayHasKey("type", $body);
    }

    /**
     * @group template
     * @group get
     * @group controller
     */
    public function testGetValideSansResultat()
    {
        $client = static::createClient();
        $client->request('GET', '/templates/404', []);
        $response = $client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $body = json_decode($response->getContent(), true);
        $this->assertNotEmpty($body);
    }

    /**
     * @group template
     * @group get
     * @group controller
     */
    public function testGetInvalide()
    {
        $client = static::createClient();
        $client->request('GET', '/templates/500', ["filter_erreur" => "+h"]);
        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
    }

    /**
     * @group template
     * @group delete
     * @group controller
     */
    public function testDeleteValide()
    {
        $this->loadFixtureFiles(['@StarkerxpCampagneBundle/Tests/DataFixtures/TemplateManager/DefaultTemplate.yml']);
        $manager = $this->getContainer()->get('starkerxp_campagne.manager.template');
        $listeTemplates = $manager->getRepository()->findAll();
        $this->assertCount(1, $listeTemplates);
        $client = static::createClient();
        $client->request('DELETE', '/templates/'.$listeTemplates[0]->getId());
        $response = $client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
        $manager->clear();
        $templates = $manager->findAll();
        $this->assertCount(0, $templates);
    }

    /**
     * @group template
     * @group delete
     * @group controller
     */
    public function testDeleteSansResultat()
    {
        $client = static::createClient();
        $client->request('DELETE', '/templates/404', []);
        $response = $client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $body = json_decode($response->getContent(), true);
        $this->assertNotEmpty($body);
    }
}
