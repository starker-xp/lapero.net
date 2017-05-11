<?php

namespace Starkerxp\StructureBundle\Listener;

use DateTime;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Ramsey\Uuid\Uuid;
use Starkerxp\StructureBundle\Entity\Entity;
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

        if (!$entity instanceof Entity) {
            return false;
        }

        $this->traitement($entity);

        if (empty($entity->getCreatedAt())) {
            $entity->setCreatedAt(new DateTime());
        }
    }

    private function traitement(Entity $entity)
    {
        if (empty($entity->getUuid())) {
            $uid = Uuid::uuid4();
            $entity->setUuid($uid);
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
            if (!$entity instanceof Entity) {
                continue;
            }
            $this->traitement($entity);
            $entity->setUpdatedAt(new DateTime());
        }
    }
}
