<?php

namespace Starkerxp\LeadBundle\Tests\Controller;

use Starkerxp\LeadBundle\Entity\Lead;
use Starkerxp\StructureBundle\Test\WebTest;

class LeadControllerTest extends WebTest
{

    /**
     * @group lead
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
            'origin' => "validatemy.com",
            'external_reference' => '12',
            'product' => "form",
            'date_event' => "2017-08-05 00:00:00",
        ];
        $url = $this->generateUrl(
            'starkerxp_lead.lead.post',
            []
        );
        $client = $this->getAuthClient();
        $client->request('POST', $url, $data);
        $response = $client->getResponse();
        $this->assertEquals(201, $response->getStatusCode());
        $manager = $this->getContainer()->get('starkerxp_lead.manager.lead');
        $leads = $manager->findAll();
        $this->assertCount(1, $leads);
    }

    /**
     * @group lead
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
        $url = $this->generateUrl(
            'starkerxp_lead.lead.post',
            []
        );
        $client = $this->getAuthClient();
        $client->request('POST', $url, []);
        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
        $body = json_decode($response->getContent(), true)['payload'];
        //$this->assertArrayHasKey("nom", $body); // Exemple
    }


    /**
     * @group lead
     * @group put
     * @group controller
     */
    public function testPutValide()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',

                '@StarkerxpLeadBundle/Tests/DataFixtures/LeadManager/DefaultLead.yml',
            ]
        );
        $manager = $this->getContainer()->get('starkerxp_lead.manager.lead');
        $listeLeads = $manager->getRepository()->findAll();
        $this->assertCount(1, $listeLeads);
        $leadDepart = $manager->toArray($listeLeads[0], ['product', 'date_event']);
        $data = [
            'product' => "form2",
            'date_event' => "2017-08-05 12:00:00",
        ];
        $url = $this->generateUrl(
            'starkerxp_lead.lead.put',
            [
                "lead_id" => $listeLeads[0]->getId(),
            ]
        );
        $client = $this->getAuthClient();
        $client->request('PUT', $url, $data);
        $response = $client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
        $manager->clear();
        $leads = $manager->findAll();
        $this->assertCount(1, $leads);
        $leadFinal = $manager->toArray($leads[0], ['product', 'date_event']);
        $this->assertNotEquals($leadDepart, $leadFinal);
        $this->assertEquals($data, $leadFinal);
    }

    /**
     * @group lead
     * @group put
     * @group controller
     */
    public function testPutInvalide()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',

                '@StarkerxpLeadBundle/Tests/DataFixtures/LeadManager/DefaultLead.yml',
            ]
        );
        $manager = $this->getContainer()->get('starkerxp_lead.manager.lead');
        $listeLeads = $manager->getRepository()->findAll();
        $this->assertCount(1, $listeLeads);
        $url = $this->generateUrl(
            'starkerxp_lead.lead.put',
            [
                "lead_id" => $listeLeads[0]->getId(),
            ]
        );
        $data = [
            "external_reference" => null,
        ];
        $client = $this->getAuthClient();
        $client->request('PUT', $url, $data);
        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
        $body = json_decode($response->getContent(), true)['payload'];
        //$this->assertArrayHasKey("nom", $body); // Exemple

    }

    /**
     * @group lead
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
            //'nom'     => "Mon nom", //exemple
        ];
        $url = $this->generateUrl(
            'starkerxp_lead.lead.put',
            [
                "lead_id" => 404,
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
     * @group lead
     * @group cget
     * @group controller
     */
    public function testCGetValideAvecResultats()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',

                '@StarkerxpLeadBundle/Tests/DataFixtures/LeadManager/LeadManager.yml',
            ]
        );
        $url = $this->generateUrl(
            'starkerxp_lead.lead.cget',
            [

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
     * @group lead
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
        $url = $this->generateUrl(
            'starkerxp_lead.lead.cget',
            [

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
     * @group lead
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
        $url = $this->generateUrl(
            'starkerxp_lead.lead.cget',
            [

            ]
        );
        $client = $this->getAuthClient();
        $client->request('GET', $url, ["filter_erreur" => "+h"]);
        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
    }

    /**
     * @group lead
     * @group get
     * @group controller
     */
    public function testGetValideAvecResultats()
    {
        $this->loadFixtureFiles(
            [
                '@StarkerxpUserBundle/Tests/DataFixtures/UserManager/DefaultUser.yml',

                '@StarkerxpLeadBundle/Tests/DataFixtures/LeadManager/LeadManager.yml',
            ]
        );
        $manager = $this->getContainer()->get('starkerxp_lead.manager.lead');
        /** @var Lead[] $listeLeads */
        $listeLeads = $manager->getRepository()->findAll();
        $this->assertCount(10, $listeLeads);
        $url = $this->generateUrl(
            'starkerxp_lead.lead.get',
            [
                "lead_id" => $listeLeads[0]->getId(),
            ]
        );
        $client = $this->getAuthClient();
        $client->request('GET', $url, []);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getContent(), true);
        $this->assertCount(8, $body);
        //$this->assertArrayHasKey("nom", $body); // Exemple
    }

    /**
     * @group lead
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
        $url = $this->generateUrl(
            'starkerxp_lead.lead.get',
            [
                "lead_id" => 404,
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
     * @group lead
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
        $url = $this->generateUrl(
            'starkerxp_lead.lead.get',
            [
                "lead_id" => 500,
            ]
        );
        $client = $this->getAuthClient();
        $client->request('GET', $url, ["filter_erreur" => "+h"]);
        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
    }


}
