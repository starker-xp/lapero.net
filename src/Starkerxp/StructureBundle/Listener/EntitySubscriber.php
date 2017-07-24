<?php

namespace Starkerxp\StructureBundle\Listener;

use DateTime;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Ramsey\Uuid\Uuid;
use Starkerxp\StructureBundle\Entity\Entity;
use Starkerxp\StructureBundle\Entity\TimestampEntity;
use Starkerxp\StructureBundle\Entity\UtilisateurEntity;
use Starkerxp\StructureBundle\Events;
use Starkerxp\StructureBundle\Manager\EntityReadOnlyInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

//
class EntitySubscriber implements EventSubscriber
{

    /**
     * @var TokenStorage
     */
    protected $utilisateur;

    /**
     * @var EventDispatcher
     */
    protected $eventDispatcher;

    public function __construct($utilisateur, $eventDispatcher)
    {
        $this->utilisateur = $utilisateur;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function getSubscribedEvents()
    {
        return [
            'prePersist',
            'onFlush',
        ];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if ($entity instanceof TimestampEntity && empty($entity->getCreatedAt())) {
            $entity->setCreatedAt(new DateTime());
        }
        if ($entity instanceof Entity && empty($entity->getUuid())) {
            $uuid = Uuid::uuid4();
            $entity->setUuid($uuid);
        }
        if (!$entity instanceof UtilisateurEntity) {
            return false;
        }
        if (empty($entity->getUtilisateur()) && $utilisateur = $this->getUtilisateur()) {
            $entity->setUtilisateur($utilisateur);
        }
        return true;
    }

    protected function getUtilisateur()
    {
        if (!$token = $this->utilisateur->getToken()) {
            return null;
        }

        return $token->getUser();
    }

    public function onFlush(OnFlushEventArgs $event)
    {
        $entityManager = $event->getEntityManager();
        $uow = $entityManager->getUnitOfWork();
        foreach ($uow->getScheduledEntityUpdates() as $entity) {
            if ($entity instanceof EntityReadOnlyInterface) {
                $uow->detach($entity);
                $this->eventDispatcher->dispatch(Events::ENTITY_READ_ONLY, new GenericEvent($entity));
                continue;
            }
            if ($entity instanceof TimestampEntity) {
                $entity->setUpdatedAt(new DateTime());
            }
            if ($entity instanceof Entity && empty($entity->getUuid())) {
                $uuid = Uuid::uuid4();
                $entity->setUuid($uuid);
            }
            if (!$entity instanceof UtilisateurEntity) {
                continue;
            }
            if (empty($entity->getUtilisateur()) && $utilisateur = $this->getUtilisateur()) {
                $entity->setUtilisateur($utilisateur);
            }
        }
    }
}
