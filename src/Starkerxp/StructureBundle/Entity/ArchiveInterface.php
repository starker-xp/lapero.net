<?php
/**
 * Created by PhpStorm.
 * User: DIEU
 * Date: 20/07/2017
 * Time: 01:06
 */

namespace Starkerxp\StructureBundle\Entity;


interface ArchiveInterface
{
    public function isArchive();
    public function getCreatedAt();
}
