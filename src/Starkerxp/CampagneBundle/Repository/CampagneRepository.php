<?php

namespace Starkerxp\CampagneBundle\Repository;

/**
 * CampagneRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CampagneRepository extends \Doctrine\ORM\EntityRepository
{

    public function getQueryListe()
    {
        $query = $this->createQueryBuilder('c');
        $query->andWhere("c.deleted = 0");

        return $query;
    }

}
