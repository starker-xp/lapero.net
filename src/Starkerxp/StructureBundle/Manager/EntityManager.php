<?php

namespace Starkerxp\StructureBundle\Manager;

use Doctrine\ORM\EntityRepository;
use Starkerxp\StructureBundle\Entity\Entity;

class EntityManager implements ManagerInterface
{
    /**
     * @var array
     */
    private $arrayService = [];

    /**
     * @return mixed
     */
    public function insert(Entity $object)
    {
        if ($managerService = $this->getSupport($object)) {
            $managerService->insert($object);

            return $object;
        }

        return false;
    }

    /**
     * @param Entity $object
     *
     * @return bool|ManagerInterface
     */
    public function getSupport(Entity $object)
    {
        foreach ($this->arrayService as $service) {
            if ($service instanceof ManagerInterface && $service->getSupport($object)) {
                return $service;
            }
        }

        return false;
    }

    public function update(Entity $object)
    {
        if ($managerService = $this->getSupport($object)) {
            $managerService->update($object);

            return $object;
        }

        return false;
    }

    public function delete(Entity $object)
    {
        if ($managerService = $this->getSupport($object)) {
            $managerService->delete($object);

            return $object;
        }

        return false;
    }

    /**
     * @param ManagerInterface $service
     *
     * @return $this
     */
    public function addService(ManagerInterface $service)
    {
        $this->arrayService[] = $service;

        return $this;
    }

    /**
     * @return array
     */
    public function getServices()
    {
        return $this->arrayService;
    }

    /**
     * @param Entity $object
     *
     * @return bool|EntityRepository
     */
    public function getRepository(Entity $object)
    {
        if (!$manager = $this->getManager($object)) {
            return false;
        }

        return $manager->getRepository();
    }

    /**
     * @param Entity $object
     *
     * @return bool|AbstractManager
     */
    public function getManager(Entity $object)
    {
        $managerService = $this->getSupport($object);

        return $managerService;
    }
}
