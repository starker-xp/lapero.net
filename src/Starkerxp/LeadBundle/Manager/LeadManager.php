<?php

namespace Starkerxp\LeadBundle\Manager;

use Starkerxp\LeadBundle\Entity\Lead;
use Starkerxp\StructureBundle\Entity\AbstractEntity;
use Starkerxp\StructureBundle\Manager\AbstractManager;
use Starkerxp\StructureBundle\Manager\Exception\DeleteObjectNotAllowedException;

class LeadManager extends AbstractManager
{

    public function getSupport(AbstractEntity $object)
    {
        return $object instanceof Lead;
    }

    /**
     * @param AbstractEntity $object
     * @throws DeleteObjectNotAllowedException
     */
    public function delete(AbstractEntity $object)
    {
        throw new DeleteObjectNotAllowedException();
    }

    public function toArray(Lead $object, $fields = [])
    {
        $array = [
            "id" => $object->getId(),
            "date_event" => $object->getDateEvent()->format("Y-m-d H:i:s"),
            "origin" => $object->getOrigin(),
            "product" => $object->getProduct(),
            "external_reference" => $object->getExternalReference(),
            "pixel" => $object->isPixel(),
            "ip_address" => $object->getIpAddress(),
            "serialisation" => $object->getSerialisation(),
        ];

        return $this->exportFields($array, $fields);
    }
}
