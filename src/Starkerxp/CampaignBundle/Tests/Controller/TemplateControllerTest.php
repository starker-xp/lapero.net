<?php

namespace Starkerxp\CampaignBundle\Tests\Manager;

use Starkerxp\StructureBundle\Test\WebTest;

class TemplateControllerTest extends WebTest
{

    /**
     * @group template
     * @group post
     * @group controller
     */
    public function testPostValide()
    {
        $this->loadFixtureFiles(['@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml']);

        $data = [
            'name'     => "Mon nom",
            'object'   => "Mon sujet",
            'message' => "Mon message",
            'type'    => "email",
        ];
        $client = $this->getAuthClient();
        $client->request('POST', '/api/templates', $data);
        $response = $client->getResponse();
        $this->assertEquals(201, $response->getStatusCode());
        $manager = $this->getContainer()->get('starkerxp_campaign.manager.template');
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
        $this->loadFixtureFiles(['@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml']);
        $client = $this->getAuthClient();
        $client->request('POST', '/api/templates', []);
        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
        $body = json_decode($response->getContent(), true)['payload'];
        $this->assertArrayHasKey("name", $body);
        $this->assertArrayHasKey("object", $body);
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
        $this->loadFixtureFiles([
            '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',
            '@StarkerxpCampaignBundle/Tests/DataFixtures/TemplateManager/DefaultTemplate.yml'
        ]);
        $manager = $this->getContainer()->get('starkerxp_campaign.manager.template');
        $listeTemplates = $manager->getRepository()->findAll();
        $this->assertCount(1, $listeTemplates);
        $templateDepart = $manager->toArray($listeTemplates[0], ['name', 'object', 'message']);
        $data = [
            'name'     => "Mon nom",
            'object'   => "Mon sujet",
            'message' => "Mon message",
            'type'    => "email",
        ];
        $client = $this->getAuthClient();
        $client->request('PUT', '/api/templates/'.$listeTemplates[0]->getId(), $data);
        $response = $client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
        $manager->clear();
        $templates = $manager->findAll();
        $this->assertCount(1, $templates);
        $templateFinal = $manager->toArray($templates[0], ['name', 'object', 'message', 'type']);
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
        $this->loadFixtureFiles([
            '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',
            '@StarkerxpCampaignBundle/Tests/DataFixtures/TemplateManager/DefaultTemplate.yml'
        ]);
        $manager = $this->getContainer()->get('starkerxp_campaign.manager.template');
        $listeTemplates = $manager->getRepository()->findAll();
        $this->assertCount(1, $listeTemplates);
        $client = $this->getAuthClient();
        $client->request('PUT', '/api/templates/'.$listeTemplates[0]->getId(), []);
        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
        $body = json_decode($response->getContent(), true)['payload'];
        $this->assertArrayHasKey("name", $body);
        $this->assertArrayHasKey("object", $body);
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
        $this->loadFixtureFiles([
            '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',
        ]);
        $data = [
            'name'     => "Mon nom",
            'object'   => "Mon sujet",
            'message' => "Mon message",
            'type'    => "email",
        ];
        $client = $this->getAuthClient();
        $client->request('PUT', '/api/templates/404', $data);
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
        $this->loadFixtureFiles([
            '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',
            '@StarkerxpCampaignBundle/Tests/DataFixtures/TemplateManager/TemplateManager.yml'
        ]);
        $client = $this->getAuthClient();
        $client->request('GET', '/api/templates', []);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getContent(), true);
        $this->assertCount(10, $body);
        foreach ($body as $element) {
            $this->assertArrayHasKey("id", $element);
            $this->assertArrayHasKey("name", $element);
            $this->assertArrayHasKey("object", $element);
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
        $this->loadFixtureFiles([
            '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',
        ]);
        $client = $this->getAuthClient();
        $client->request('GET', '/api/templates', []);
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
        $this->loadFixtureFiles([
            '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',
        ]);
        $client = $this->getAuthClient();
        $client->request('GET', '/api/templates', ["filter_erreur" => "+h"]);
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
        $this->loadFixtureFiles([
            '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',
            '@StarkerxpCampaignBundle/Tests/DataFixtures/TemplateManager/TemplateManager.yml'
        ]);
        $manager = $this->getContainer()->get('starkerxp_campaign.manager.template');
        $listeTemplates = $manager->getRepository()->findAll();
        $this->assertCount(10, $listeTemplates);
        $client = $this->getAuthClient();
        $client->request('GET', '/api/templates/'.$listeTemplates[0]->getId(), []);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getContent(), true);
        $this->assertCount(5, $body);
        $this->assertArrayHasKey("id", $body);
        $this->assertArrayHasKey("name", $body);
        $this->assertArrayHasKey("object", $body);
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
        $this->loadFixtureFiles([
            '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',
        ]);
        $client = $this->getAuthClient();
        $client->request('GET', '/api/templates/404', []);
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
        $this->loadFixtureFiles([
            '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',
        ]);
        $client = $this->getAuthClient();
        $client->request('GET', '/api/templates/500', ["filter_erreur" => "+h"]);
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
        $this->loadFixtureFiles([
            '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',
            '@StarkerxpCampaignBundle/Tests/DataFixtures/TemplateManager/DefaultTemplate.yml'
        ]);
        $manager = $this->getContainer()->get('starkerxp_campaign.manager.template');
        $listeTemplates = $manager->getRepository()->findAll();
        $this->assertCount(1, $listeTemplates);
        $client = $this->getAuthClient();
        $client->request('DELETE', '/api/templates/'.$listeTemplates[0]->getId());
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
        $this->loadFixtureFiles([
            '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',
        ]);
        $client = $this->getAuthClient();
        $client->request('DELETE', '/api/templates/404', []);
        $response = $client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $body = json_decode($response->getContent(), true);
        $this->assertNotEmpty($body);
    }
}
