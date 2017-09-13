<?php

namespace Starkerxp\StructureBundle\Listener;

use DateTime;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Ramsey\Uuid\Uuid;
use Starkerxp\StructureBundle\Entity\AbstractEntity;
use Starkerxp\StructureBundle\Entity\TimestampInterface;
use Starkerxp\StructureBundle\Entity\AbstractUser;
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
    protected $user;

    /**
     * @var EventDispatcher
     */
    protected $eventDispatcher;

    public function __construct($user, $eventDispatcher)
    {
        $this->user = $user;
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

        if (($entity instanceof TimestampInterface )&& empty($entity->getCreatedAt())) {
            $entity->setCreatedAt(new DateTime());
        }
        if ($entity instanceof AbstractEntity && empty($entity->getUuid())) {
            $uuid = Uuid::uuid4();
            $entity->setUuid($uuid);
        }
        if (!$entity instanceof AbstractUser) {
            return false;
        }
        if (empty($entity->getUser()) && $user = $this->getUser()) {
            $entity->setUser($user);
        }

        return true;
    }

    protected function getUser()
    {
        if (!$token = $this->user->getToken()) {
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
            if ($entity instanceof TimestampInterface) {
                $entity->setUpdatedAt(new DateTime());
            }
            if ($entity instanceof AbstractEntity && empty($entity->getUuid())) {
                $uuid = Uuid::uuid4();
                $entity->setUuid($uuid);
            }
            if (!$entity instanceof AbstractUser) {
                continue;
            }
            if (empty($entity->getUser()) && $user = $this->getUser()) {
                $entity->setUser($user);
            }
        }
    }
}
