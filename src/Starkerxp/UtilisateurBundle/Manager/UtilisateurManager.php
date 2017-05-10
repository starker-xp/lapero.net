<?php

namespace Starkerxp\UtilisateurBundle\Manager;

use Starkerxp\UtilisateurBundle\Entity\Utilisateur;
use Starkerxp\StructureBundle\Entity\Entity;
use Starkerxp\StructureBundle\Manager\AbstractManager;

class UtilisateurManager extends AbstractManager
{
	
	public function getSupport(Entity $object)
    {
        return $object instanceof Utilisateur;
    }

}
