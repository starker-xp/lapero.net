<?php

namespace Starkerxp\StructureBundle\Tests;

use Doctrine\ORM\EntityManager;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class WebTest extends WebTestCase
{
    protected $identifiant = "test@yopmail.com";
    protected $motDePasse = "motMotDePasse";

    /**
     * Erase all database data.
     */
    public function setUp()
    {
        parent::setUp();
        $this->loadFixtureFiles([]);
    }

    public function tearDown()
    {
        parent::tearDown();
        $this->getEntityManager()->getConnection()->close();
    }

    public function getAuthClient()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/api/login_check',
            [
                'identifiant' => $this->identifiant,
                'motDePasse'  => $this->motDePasse,
            ]
        );
        $dataHeader = json_decode($client->getResponse()->getContent(), true);

        $client = static::createClient();
        $client->setServerParameter('HTTP_Authorization', sprintf('Bearer %s', $dataHeader['token']));

        return $client;
    }

    /**
     * Generates a URL from the given parameters.
     *
     * @param string $route The name of the route
     * @param mixed $parameters An array of parameters
     * @param int $referenceType The type of reference (one of the constants in UrlGeneratorInterface)
     *
     * @return string The generated URL
     *
     * @see UrlGeneratorInterface
     */
    protected function generateUrl($route, $parameters = [], $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        return $this->getContainer()->get('router')->generate($route, $parameters, $referenceType);
    }

    /**
     * @param string|null $name
     * @return \Doctrine\DBAL\Connection
     */
    public function getConnection($name = null)
    {
        return $this->getContainer()->get('doctrine')->getConnection($name);
    }

    /**
     * @param string|null $name
     * @return EntityManager
     */
    public function getEntityManager($name = null)
    {
        $entityManager = $this->getContainer()->get('doctrine')->getManager($name);

        return $entityManager;
    }

    /**
     * @param $entiteCQFD
     * @param string|null $name
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    public function getRepository($entiteCQFD, $name = null)
    {
        $repository = $this->getEntityManager($name)->getRepository($entiteCQFD);

        return $repository;
    }

    protected $idGeneratorTypes = [];

    protected function allowFixedIdsFor(array $entityClasses, $name = null)
    {
        foreach ($entityClasses as $entityClass) {
            $metadata = $this->getEntityManager($name)->getClassMetadata($entityClass);
            $this->idGeneratorTypes[$entityClass] = $metadata->generatorType;
            $metadata->setIdGeneratorType(\Doctrine\ORM\Mapping\ClassMetadata::GENERATOR_TYPE_NONE);
            $metadata->setIdGenerator(new \Doctrine\ORM\Id\AssignedGenerator());
        }
    }

    protected function recoverIdGenerators($name = null)
    {
        foreach ($this->idGeneratorTypes as $entityClass => $idGeneratorType) {
            $metadata = $this->getEntityManager($name)->getClassMetadata($entityClass);
            $metadata->setIdGeneratorType($idGeneratorType);
        }
    }

    public function loadFixtureFiles(array $paths = [], $append = false, $omName = null, $registryName = 'doctrine', $purgeMode = null)
    {
        $retour = parent::loadFixtureFiles($paths, $append, $omName, $registryName, $purgeMode);
        $this->recoverIdGenerators();
        $this->getEntityManager()->clear();

        return $retour;
    }

    public function executerCommande($command = [])
    {
        $container = $this->getContainer();
        $kernel = $container->get('kernel');
        $application = new Application($kernel);
        $application->setAutoExit(false);
        $input = new ArrayInput($command);
        $output = new BufferedOutput();
        $application->run($input, $output);
    }

    /**
     * Call protected/private method of a class.
     *
     * @param object $object Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod($object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

}
