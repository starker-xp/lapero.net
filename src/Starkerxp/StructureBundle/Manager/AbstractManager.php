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

    private $modeTransactionnal = false;

    /**
     * @param EntityManager $entityManager
     * @param $entity
     */
    public function __construct(EntityManager $entityManager, $entity)
    {
        $this->entityManager = $entityManager;
        $this->repository = $this->entityManager->getRepository($entity);
    }

    /**
     * @param Entity $object
     *
     * @return Entity|boolean
     *
     * @throws ObjectClassNotAllowedException
     */
    public function insert(Entity $object)
    {
        if (!$this->getSupport($object)) {
            throw new ObjectClassNotAllowedException();
        }
        $object->setCreatedAt(new DateTime());
        $this->entityManager->persist($object);
        $this->flush();

        return $object;
    }

    /**
     * @param Entity $object
     *
     * @return Entity|boolean
     *
     * @throws ObjectClassNotAllowedException
     */
    public function update(Entity $object)
    {
        if (!$this->getSupport($object)) {
            throw new ObjectClassNotAllowedException();
        }
        $object->setUpdatedAt(new DateTime());
        $this->flush();

        return $object;
    }

    /**
     * @param Entity $object
     * @throws ObjectClassNotAllowedException
     */
    public function delete(Entity $object)
    {
        if (!$this->getSupport($object)) {
            throw new ObjectClassNotAllowedException();
        }
        $this->entityManager->remove($object);
        $this->flush();
    }

    /**
     * Finds an entity by its primary key / identifier.
     *
     * @param mixed $id The identifier.
     * @param int|null $lockMode One of the \Doctrine\DBAL\LockMode::* constants
     *                              or NULL if no specific lock mode should be used
     *                              during the search.
     * @param int|null $lockVersion The lock version.
     *
     * @return object|null The entity instance or NULL if the entity can not be found.
     */
    public function find($id, $lockMode = null, $lockVersion = null)
    {
        return $this->repository->find($id, $lockMode, $lockVersion);
    }

    /**
     * Finds a single entity by a set of criteria.
     *
     * @param array $criteria
     * @param array|null $orderBy
     *
     * @return object|null The entity instance or NULL if the entity can not be found.
     */
    public function findOneBy(array $criteria, array $orderBy = null)
    {
        return $this->repository->findOneBy($criteria, $orderBy);
    }

    /**
     * Finds entities by a set of criteria.
     *
     * @param array $criteria
     * @param array|null $orderBy
     * @param int|null $limit
     * @param int|null $offset
     *
     * @return array The objects.
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->repository->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * Finds all entities in the repository.
     *
     * @return array The entities.
     */
    public function findAll()
    {
        return $this->repository->findAll();
    }

    /**
     * @return EntityRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * Vide l'UnitOfWork de l'entity manager.
     */
    public function clear(){
        $this->entityManager->clear();
    }

    /**
     * Permet de passer en gestion des transations manuelles. (Conseillé par SensioLabs).
     */
    public function beginTransaction(){
        $this->modeTransactionnal = true;
        $this->entityManager->beginTransaction();
    }

    /**
     * Dans le cas d'une gestion des transactions manuelles en cas d'échec on rollback le tout.
     */
    public function rollback(){
        if($this->modeTransactionnal) {
            $this->entityManager->rollback();
            $this->entityManager->close();
            $this->modeTransactionnal = false;
        }
    }

    /**
     * Permet de gérer un flush en mode transactions manuelles.
     */
    private function flush(){
        if($this->modeTransactionnal){
            $this->entityManager->commit();
        }
        $this->entityManager->flush();
    }


}
