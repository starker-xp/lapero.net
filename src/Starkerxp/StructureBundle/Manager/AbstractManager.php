<?php

namespace Starkerxp\StructureBundle\Manager;

use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Starkerxp\StructureBundle\Entity\Entity;
use Starkerxp\StructureBundle\Manager\Exception\ObjectClassNotAllowedException;

abstract class AbstractManager implements ManagerInterface
{
    /** @var EntityManager */
    protected $entityManager;

    /** @var EntityRepository */
    protected $repository;

    /**
     * @param EntityManager $entityManager
     * @param $entity
     */
    public function __construct(EntityManager $entityManager, $entity)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository($entity);
    }

    public function insert(Entity $object)
    {
        if (!$this->getSupport($object)) {
            throw new ObjectClassNotAllowedException();
        }
        $object->setCreatedAt(new DateTime());
        $this->entityManager->persist($object);
        $this->entityManager->flush();

        return $object;
    }

    public function update(Entity $object)
    {
        if (!$this->getSupport($object)) {
            throw new ObjectClassNotAllowedException();
        }
        $object->setUpdatedAt(new DateTime());
        $this->entityManager->flush();

        return $object;
    }

    public function find($id)
    {
        $this->repository->find($id);
    }

    public function findOneBy($criteria = [])
    {
        return $this->repository->findOneBy($criteria);
    }

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->repository->find($criteria, $orderBy, $limit, $offset);
    }

    public function findAll()
    {
        return $this->repository->findAll();
    }

    public function getRepository()
    {
        return $this->repository;
    }
}
