<?php
/**
 * Created by PhpStorm.
 * User: DIEU
 * Date: 21/06/2017
 * Time: 01:20
 */

namespace Starkerxp\StructureBundle\Services;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class EntityToIdObjectTransformer implements DataTransformerInterface
{
    /**
     * @var ObjectManager
     */
    private $om;

    /**
     * @var string
     */
    private $entityName;

    /**
     * @param ObjectManager $om
     * @param string $entityName
     */
    public function __construct(ObjectManager $om, $entityName)
    {
        $this->entityName = $entityName;
        $this->om = $om;
    }

    /**
     * Do nothing.
     *
     * @param object|null $object
     *
     * @return string
     */
    public function transform($object)
    {
        if (null === $object) {
            return '';
        }

        return current(array_values($this->om->getClassMetadata($this->entityName)->getIdentifierValues($object)));
    }

    /**
     * Transforms an array including an identifier to an object.
     *
     * @param array $idObject
     *
     * @throws TransformationFailedException if object is not found
     *
     * @return object|null
     */
    public function reverseTransform($idObject)
    {
        $identifier = current(array_values($this->om->getClassMetadata($this->entityName)->getIdentifier()));

        $object = $this->om
            ->getRepository($this->entityName)
            ->findOneBy([$identifier => $idObject]);

        if (null === $object) {
            throw new TransformationFailedException(
                sprintf(
                    'An object with identifier key "%s" and value "%s" does not exist!',
                    $identifier,
                    $idObject
                )
            );
        }

        return $object;
    }
}
