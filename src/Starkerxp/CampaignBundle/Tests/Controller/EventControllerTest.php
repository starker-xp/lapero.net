<?php

namespace Starkerxp\CampaignBundle\Tests\Controller;

use Starkerxp\StructureBundle\Test\WebTest;

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
                '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/CampaignManager/DefaultCampaign.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/TemplateManager/TemplateManager.yml',
            ]
        );
        $data = [
            "campaign" => $this->getCampaignId(),
            "template" => $this->getTemplateId(),
        ];
        $url = $this->generateUrl(
            'starkerxp_campaign.event.post',
            [
                "campaign_id" => $this->getCampaignId(),
            ]
        );
        $client = $this->getAuthClient();
        $client->request('POST', $url, $data);
        $response = $client->getResponse();

        $this->assertEquals(201, $response->getStatusCode());
        $manager = $this->getContainer()->get('starkerxp_campaign.manager.event');
        $events = $manager->findAll();
        $this->assertCount(1, $events);
    }

    protected function getCampaignId()
    {
        $repositoryCampaign = $this->getEntityManager()->getRepository("StarkerxpCampaignBundle:Campaign");
        $resultats = $repositoryCampaign->findAll();

        return $resultats[0]->getId();
    }

    protected function getTemplateId()
    {
        $repositoryTemplate = $this->getEntityManager()->getRepository("StarkerxpCampaignBundle:Template");
        $resultats = $repositoryTemplate->findAll();

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
                '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/CampaignManager/DefaultCampaign.yml',
            ]
        );
        $url = $this->generateUrl(
            'starkerxp_campaign.event.post',
            [
                "campaign_id" => $this->getCampaignId(),
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
                '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/CampaignManager/DefaultCampaign.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/TemplateManager/TemplateManager.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/EventManager/DefaultEvent.yml',
            ]
        );
        $templates = $this->getRepository("StarkerxpCampaignBundle:Template")->findBy([], ['id' => 'ASC']);

        $manager = $this->getContainer()->get('starkerxp_campaign.manager.event');
        $listeEvents = $manager->getRepository()->findAll();
        $this->assertCount(1, $listeEvents);
        $eventDepart = $manager->toArray($listeEvents[0], ['campaign_id', 'template_id']);
        $data = [
            "campaign" => $this->getCampaignId(),
            "template" => $templates[1]->getId(),
        ];
        $url = $this->generateUrl(
            'starkerxp_campaign.event.put',
            [
                "campaign_id" => $this->getCampaignId(),
                "id"          => $listeEvents[0]->getId(),
            ]
        );
        $client = $this->getAuthClient();
        $client->request('PUT', $url, $data);
        $response = $client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
        $manager->clear();
        $events = $manager->findAll();
        $this->assertCount(1, $events);
        $eventFinal = $manager->toArray($events[0], ['campaign_id', 'template_id']);
        $this->assertNotEquals($eventDepart, $eventFinal);
        $this->assertEquals(array_values($data), array_values($eventFinal));
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
                '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/CampaignManager/DefaultCampaign.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/TemplateManager/TemplateManager.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/EventManager/DefaultEvent.yml',
            ]
        );
        $manager = $this->getContainer()->get('starkerxp_campaign.manager.event');
        $listeEvents = $manager->getRepository()->findAll();
        $this->assertCount(1, $listeEvents);
        $url = $this->generateUrl(
            'starkerxp_campaign.event.put',
            [
                "campaign_id" => $this->getCampaignId(),
                "id"          => $listeEvents[0]->getId(),
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
                '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/TemplateManager/TemplateManager.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/CampaignManager/DefaultCampaign.yml',
            ]
        );
        $data = [
            //'nom'     => "Mon nom", //exemple
        ];
        $url = $this->generateUrl(
            'starkerxp_campaign.event.put',
            [
                "campaign_id" => $this->getCampaignId(),
                "id"          => 404,
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
                '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/CampaignManager/DefaultCampaign.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/TemplateManager/TemplateManager.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/EventManager/EventManager.yml',
            ]
        );
        $url = $this->generateUrl(
            'starkerxp_campaign.event.cget',
            [
                "campaign_id" => $this->getCampaignId(),
            ]
        );
        $client = $this->getAuthClient();
        $client->request('GET', $url, []);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getContent(), true);
        $this->assertCount(10, $body);
        foreach ($body as $element) {
            //$this->assertArrayHasKey("name", $body); // Exemple
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
                '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/TemplateManager/TemplateManager.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/CampaignManager/DefaultCampaign.yml',
            ]
        );
        $url = $this->generateUrl(
            'starkerxp_campaign.event.cget',
            [
                "campaign_id" => $this->getCampaignId(),
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
                '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/TemplateManager/TemplateManager.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/CampaignManager/DefaultCampaign.yml',
            ]
        );
        $url = $this->generateUrl(
            'starkerxp_campaign.event.cget',
            [
                "campaign_id" => $this->getCampaignId(),
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
                '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/CampaignManager/DefaultCampaign.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/TemplateManager/TemplateManager.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/EventManager/EventManager.yml',
            ]
        );
        $manager = $this->getContainer()->get('starkerxp_campaign.manager.event');
        $listeEvents = $manager->getRepository()->findAll();
        $this->assertCount(10, $listeEvents);
        $url = $this->generateUrl(
            'starkerxp_campaign.event.get',
            [
                "campaign_id" => $this->getCampaignId(),
                "id"          => $listeEvents[0]->getId(),
            ]
        );
        $client = $this->getAuthClient();
        $client->request('GET', $url, []);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getContent(), true);
        $this->assertCount(3, $body);
        $this->assertArrayHasKey("id", $body); // Exemple
        $this->assertArrayHasKey("campaign_id", $body); // Exemple
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
                '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/TemplateManager/TemplateManager.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/CampaignManager/DefaultCampaign.yml',
            ]
        );
        $url = $this->generateUrl(
            'starkerxp_campaign.event.get',
            [
                "campaign_id" => $this->getCampaignId(),
                "id"          => 404,
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
                '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/TemplateManager/TemplateManager.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/CampaignManager/DefaultCampaign.yml',
            ]
        );
        $url = $this->generateUrl(
            'starkerxp_campaign.event.get',
            [
                "campaign_id" => $this->getCampaignId(),
                "id"          => 500,
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
                '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/CampaignManager/DefaultCampaign.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/TemplateManager/TemplateManager.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/EventManager/DefaultEvent.yml',
            ]
        );
        $manager = $this->getContainer()->get('starkerxp_campaign.manager.event');
        $listeEvents = $manager->getRepository()->findAll();
        $this->assertCount(1, $listeEvents);
        $url = $this->generateUrl(
            'starkerxp_campaign.event.delete',
            [
                "campaign_id" => $this->getCampaignId(),
                "id"          => $listeEvents[0]->getId(),
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
                '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/TemplateManager/TemplateManager.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/CampaignManager/DefaultCampaign.yml',
            ]
        );
        $url = $this->generateUrl(
            'starkerxp_campaign.event.delete',
            [
                "campaign_id" => $this->getCampaignId(),
                "id"          => 404,
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
