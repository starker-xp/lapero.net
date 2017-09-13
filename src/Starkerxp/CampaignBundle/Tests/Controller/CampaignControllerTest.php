<?php

namespace Starkerxp\CampaignBundle\Tests\Controller;

use Starkerxp\StructureBundle\Test\WebTest;

class CampaignControllerTest extends WebTest
{

    /**
     * @group campaign
     * @group post
     * @group controller
     */
    public function testPostValide()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',
            ]
        );
        $data = [
            'name' => "Ma première campaign",
        ];
        $client = $this->getAuthClient();
        $client->request('POST', '/api/campaigns', $data);
        $response = $client->getResponse();
        $this->assertEquals(201, $response->getStatusCode());
        $body = json_decode($response->getContent(), true);
        $this->assertEquals("La donnée a bien été créé.", $body['payload']);
        $this->assertArrayHasKey("token", $body);
        $manager = $this->getContainer()->get('starkerxp_campaign.manager.campaign');
        $campaigns = $manager->findAll();
        $this->assertCount(1, $campaigns);
    }

    /**
     * @group campaign
     * @group post
     * @group controller
     */
    public function testPostInvalide()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',

            ]
        );
        $client = $this->getAuthClient();
        $client->request('POST', '/api/campaigns', []);
        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
    }


    /**
     * @group campaign
     * @group put
     * @group controller
     */
    public function testPutValide()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',

                '@StarkerxpCampaignBundle/Tests/DataFixtures/CampaignManager/DefaultCampaign.yml',
            ]
        );
        $manager = $this->getContainer()->get('starkerxp_campaign.manager.campaign');
        $listeCampaigns = $manager->getRepository()->findAll();
        $this->assertCount(1, $listeCampaigns);
        $campaignDepart = $manager->toArray($listeCampaigns[0], ['name']);// Exemple
        $data = [
            'name' => "Mon nom", //exemple
        ];
        $client = $this->getAuthClient();
        $client->request('PUT', '/api/campaigns/'.$listeCampaigns[0]->getId(), $data);
        $response = $client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
        $manager->clear();
        $campaigns = $manager->findAll();
        $this->assertCount(1, $campaigns);
        $campaignFinal = $manager->toArray($campaigns[0], ['name']);// Exemple
        $this->assertNotEquals($campaignDepart, $campaignFinal);
        $this->assertEquals($data, $campaignFinal);
    }

    /**
     * @group campaign
     * @group put
     * @group controller
     */
    public function testPutInvalide()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',
                '@StarkerxpCampaignBundle/Tests/DataFixtures/CampaignManager/DefaultCampaign.yml',
            ]
        );
        $manager = $this->getContainer()->get('starkerxp_campaign.manager.campaign');
        $listeCampaigns = $manager->getRepository()->findAll();
        $this->assertCount(1, $listeCampaigns);
        $client = $this->getAuthClient();
        $data = [
            'name' => "M",
        ];
        $client->request('PUT', '/api/campaigns/'.$listeCampaigns[0]->getId(), $data);
        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
        $body = json_decode($response->getContent(), true)['payload'];
        $this->assertArrayHasKey("name", $body);

    }

    /**
     * @group campaign
     * @group put
     * @group controller
     */
    public function testPutSansResultat()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',

            ]
        );
        $data = [
            'name' => "Mon nom", //exemple
        ];
        $client = $this->getAuthClient();
        $client->request('PUT', '/api/campaigns/404', $data);
        $response = $client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $payload = json_decode($response->getContent(), true)['payload'];
        $this->assertEquals("La donnée demandée n'existe pas.", $payload);
    }


    /**
     * @group campaign
     * @group cget
     * @group controller
     */
    public function testCGetValideAvecResultats()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',

                '@StarkerxpCampaignBundle/Tests/DataFixtures/CampaignManager/CampaignManager.yml',
            ]
        );
        $client = $this->getAuthClient();
        $client->request('GET', '/api/campaigns', []);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getContent(), true);
        $this->assertCount(10, $body);
        foreach ($body as $element) {
            //$this->assertArrayHasKey("nom", $body); // Exemple
        }
    }

    /**
     * @group campaign
     * @group cget
     * @group controller
     */
    public function testCGetValideSansResultat()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',

            ]
        );
        $client = $this->getAuthClient();
        $client->request('GET', '/api/campaigns', []);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getContent(), true);
        $this->assertCount(0, $body);
    }

    /**
     * @group campaign
     * @group cget
     * @group controller
     */
    public function testCGetInvalide()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',

            ]
        );
        $client = $this->getAuthClient();
        $client->request('GET', '/api/campaigns', ["filter_erreur" => "+h"]);
        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
    }

    /**
     * @group campaign
     * @group get
     * @group controller
     */
    public function testGetValideAvecResultats()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',

                '@StarkerxpCampaignBundle/Tests/DataFixtures/CampaignManager/CampaignManager.yml',
            ]
        );
        $manager = $this->getContainer()->get('starkerxp_campaign.manager.campaign');
        $listeCampaigns = $manager->getRepository()->findAll();
        $this->assertCount(10, $listeCampaigns);
        $client = $this->getAuthClient();
        $client->request('GET', '/api/campaigns/'.$listeCampaigns[0]->getId(), []);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getContent(), true);
        $this->assertCount(3, $body);
        $this->assertArrayHasKey("name", $body); // Exemple
        $this->assertArrayHasKey("status", $body); // Exemple
        $this->assertArrayHasKey("id", $body); // Exemple
    }

    /**
     * @group campaign
     * @group get
     * @group controller
     */
    public function testGetValideSansResultat()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',

            ]
        );
        $client = $this->getAuthClient();
        $client->request('GET', '/api/campaigns/404', []);
        $response = $client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $body = json_decode($response->getContent(), true);
        $this->assertNotEmpty($body);
    }

    /**
     * @group campaign
     * @group get
     * @group controller
     */
    public function testGetInvalide()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',

            ]
        );
        $client = $this->getAuthClient();
        $client->request('GET', '/api/campaigns/500', ["filter_erreur" => "+h"]);
        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
    }

    /**
     * @group campaign
     * @group delete
     * @group controller
     */
    public function testDeleteValide()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',

                '@StarkerxpCampaignBundle/Tests/DataFixtures/CampaignManager/DefaultCampaign.yml',
            ]
        );
        $manager = $this->getContainer()->get('starkerxp_campaign.manager.campaign');
        $listeCampaigns = $manager->getRepository()->findAll();
        $this->assertCount(1, $listeCampaigns);
        $client = $this->getAuthClient();
        $client->request('DELETE', '/api/campaigns/'.$listeCampaigns[0]->getId());
        $response = $client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
        $manager->clear();
        $campaigns = $manager->findAll();
        $this->assertCount(0, $campaigns);
    }

    /**
     * @group campaign
     * @group delete
     * @group controller
     */
    public function testDeleteSansResultat()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',

            ]
        );
        $client = $this->getAuthClient();
        $client->request('DELETE', '/api/campaigns/404', []);
        $response = $client->getResponse();
        $this->assertEquals(404, $response->getStatusCode());
        $body = json_decode($response->getContent(), true);
        $this->assertNotEmpty($body);
    }
}
