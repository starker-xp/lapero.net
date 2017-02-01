<?php

namespace Starkerxp\CampagneBundle\Tests\Manager;

use Starkerxp\StructureBundle\Tests\WebTest;

class TemplateControllerTest extends WebTest
{

    public function testPostValide()
    {
        // create our http client (Guzzle)
        $client = new \GuzzleHttp\Client(['base_uri' => 'http://web/']);
        $data = [
            'nom'     => "Mon nom",
            'sujet'   => "Mon sujet",
            'message' => "Mon message",
            'type'    => "email",
        ];
        $response = $client->post("app_test.php/templates", ['form_params' => $data]);
        $this->assertEquals(201, $response->getStatusCode());

        $manager = $this->getContainer()->get('starkerxp_campagne.manager.template');
        $templates = $manager->findAll();
        $this->assertCount(1, $templates);
    }

    public function testPostInvalide()
    {
        // create our http client (Guzzle)
        $client = new \GuzzleHttp\Client(['base_uri' => 'http://web/']);
        $response = $client->post("app_test.php/templates", ['form_params' => [], "exceptions"=>false]);
        $this->assertEquals(400, $response->getStatusCode());
        $body = json_decode($response->getBody(), true)['payload'];
        $this->assertArrayHasKey("nom", $body);
        $this->assertArrayHasKey("sujet", $body);
        $this->assertArrayHasKey("message", $body);
        $this->assertArrayHasKey("type", $body);
    }

}
