<?php

namespace Starkerxp\CampaignBundle\Repository;

/**
 * TemplateRepository.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TemplateRepository extends \Doctrine\ORM\EntityRepository
{

    public function getQueryListe()
    {
        $query = $this->createQueryBuilder('t');

        return $query;
    }

}