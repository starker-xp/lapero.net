<?php

namespace Starkerxp\LeadBundle\Tests\Controller;

use Starkerxp\StructureBundle\Tests\WebTest;

class LeadControllerTest extends WebTest
{

    /**
     * @group lead
     * @group post
     * @group controller
     */
    public function testPostValide()
    {
        $this->loadFixtureFiles([
            '@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/DefaultUtilisateur.yml',
        ]);
        $data = [
            //'nom'     => "Mon nom", //exemple
        ];
		$url = $this->generateUrl(
            'starkerxp_lead.lead.post',
            []
        );
        $client = $this->getAuthClient();
        $client->request('POST', $url, $data);
        $response = $client->getResponse();
        $this->assertEquals(201, $response->getStatusCode());
        $manager = $this->getContainer()->get('starkerxp_lead.manager.leadaction');
        $LeadActions = $manager->findAll();
        $this->assertCount(1, $LeadActions);
    }

    /**
     * @group lead
     * @group post
     * @group controller
     */
    public function testPostInvalide()
    {
        $this->loadFixtureFiles([
            '@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/DefaultUtilisateur.yml',
        ]);
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
		$this->loadFixtureFiles([
            '@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/DefaultUtilisateur.yml',
			'@StarkerxpLeadBundle/Tests/DataFixtures/LeadActionManager/DefaultLeadAction.yml',
		]);
        $manager = $this->getContainer()->get('starkerxp_lead.manager.leadaction');
        $listeLeads = $manager->getRepository()->findAll();
        $this->assertCount(1, $listeLeads);
        $LeadActionDepart = $manager->toArray($listeLeads[0], [/*'nom'*/]);// Exemple
        $data = [
            //'nom'     => "Mon nom", //exemple
        ];
		$url = $this->generateUrl(
            'starkerxp_lead.lead.put',
            [
				"id"    => $listeLeads[0]->getId(),
			]
        );
        $client = $this->getAuthClient();
        $client->request('PUT', $url, $data);
        $response = $client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
        $manager->clear();
        $LeadActions = $manager->findAll();
        $this->assertCount(1, $LeadActions);
        $LeadActionFinal = $manager->toArray($LeadActions[0], [/*'nom'*/]);// Exemple
        $this->assertNotEquals($LeadActionDepart, $LeadActionFinal);
        $this->assertEquals($data, $LeadActionFinal);
    }

    /**
     * @group lead
     * @group put
     * @group controller
     */
    public function testPutInvalide()
    {
		$this->loadFixtureFiles([
            '@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/DefaultUtilisateur.yml',
			'@StarkerxpLeadBundle/Tests/DataFixtures/LeadActionManager/DefaultLeadAction.yml',
		]);
        $manager = $this->getContainer()->get('starkerxp_lead.manager.leadaction');
        $listeLeads = $manager->getRepository()->findAll();
        $this->assertCount(1, $listeLeads);
		$url = $this->generateUrl(
            'starkerxp_lead.lead.put',
            [
				"id"    => $listeLeads[0]->getId(),
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
     * @group lead
     * @group put
     * @group controller
     */
    public function testPutSansResultat()
    {
		$this->loadFixtureFiles([
            '@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/DefaultUtilisateur.yml',
		]);
        $data = [
            //'nom'     => "Mon nom", //exemple
        ];
		$url = $this->generateUrl(
            'starkerxp_lead.lead.put',
            [
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
     * @group lead
     * @group cget
     * @group controller
     */
    public function testCGetValideAvecResultats()
    {
		$this->loadFixtureFiles([
            '@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/DefaultUtilisateur.yml',
			'@StarkerxpLeadBundle/Tests/DataFixtures/LeadActionManager/LeadManager.yml',
		]);
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
		$this->loadFixtureFiles([
            '@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/DefaultUtilisateur.yml',
		]);
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
		$this->loadFixtureFiles([
            '@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/DefaultUtilisateur.yml',
		]);
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
		$this->loadFixtureFiles([
            '@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/DefaultUtilisateur.yml',
			'@StarkerxpLeadBundle/Tests/DataFixtures/LeadActionManager/LeadManager.yml',
		]);
        $manager = $this->getContainer()->get('starkerxp_lead.manager.leadaction');
        $listeLeads = $manager->getRepository()->findAll();
        $this->assertCount(10, $listeLeads);
		$url = $this->generateUrl(
            'starkerxp_lead.lead.get',
            [
				"id" => $listeLeads[0]->getId(),
			]
        );
        $client = $this->getAuthClient();
        $client->request('GET', $url, []);
        $response = $client->getResponse();
        $this->assertEquals(200, $response->getStatusCode());
        $body = json_decode($response->getContent(), true);
        $this->assertCount(5, $body);
        //$this->assertArrayHasKey("nom", $body); // Exemple
    }

    /**
     * @group lead
     * @group get
     * @group controller
     */
    public function testGetValideSansResultat()
    {
		$this->loadFixtureFiles([
            '@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/DefaultUtilisateur.yml',
		]);
		$url = $this->generateUrl(
            'starkerxp_lead.lead.get',
            [
				"id" => 404,
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
		$this->loadFixtureFiles([
            '@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/DefaultUtilisateur.yml',
		]);
		$url = $this->generateUrl(
            'starkerxp_lead.lead.get',
            [
				"id" => 500,
			]
        );
        $client = $this->getAuthClient();
        $client->request('GET', $url, ["filter_erreur" => "+h"]);
        $response = $client->getResponse();
        $this->assertEquals(400, $response->getStatusCode());
    }

    /**
     * @group lead
     * @group delete
     * @group controller
     */
    public function testDeleteValide()
    {
		$this->loadFixtureFiles([
            '@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/DefaultUtilisateur.yml',
			'@StarkerxpLeadBundle/Tests/DataFixtures/LeadActionManager/DefaultLeadAction.yml',
		]);
        $manager = $this->getContainer()->get('starkerxp_lead.manager.leadaction');
        $listeLeads = $manager->getRepository()->findAll();
        $this->assertCount(1, $listeLeads);
		$url = $this->generateUrl(
            'starkerxp_lead.lead.delete',
            [
				"id" => $listeLeads[0]->getId(),
			]
        );
        $client = $this->getAuthClient();
        $client->request('DELETE', $url);
        $response = $client->getResponse();
        $this->assertEquals(204, $response->getStatusCode());
        $manager->clear();
        $LeadActions = $manager->findAll();
        $this->assertCount(0, $LeadActions);
    }

    /**
     * @group lead
     * @group delete
     * @group controller
     */
    public function testDeleteSansResultat()
    {
		$this->loadFixtureFiles([
            '@StarkerxpUtilisateurBundle/Tests/DataFixtures/UtilisateurManager/DefaultUtilisateur.yml',
		]);
		$url = $this->generateUrl(
            'starkerxp_lead.lead.get',
            [
				"id" => 404,
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
