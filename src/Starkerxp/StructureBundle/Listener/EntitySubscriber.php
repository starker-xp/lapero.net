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
use Starkerxp\StructureBundle\Entity\UtilisateurInterface;

//
class EntitySubscriber implements EventSubscriber
{

    /**
     * @var UtilisateurInterface
     */
    protected $utilisateur;

    public function __construct($utilisateur)
    {
        $this->utilisateur = $utilisateur;
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
        if (empty($entity->getUtilisateur())) {
            $entity->setUtilisateur($this->utilisateur);
        }
    }


    public function onFlush(OnFlushEventArgs $event)
    {
        $entityManager = $event->getEntityManager();
        $uow = $entityManager->getUnitOfWork();
        foreach ($uow->getScheduledEntityUpdates() as $entity) {
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
            if (empty($entity->getUtilisateur())) {
                $entity->setUtilisateur($this->utilisateur);
            }

        }
    }
}
