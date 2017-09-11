<?php

namespace Starkerxp\LeadBundle\Validator;

use Starkerxp\LeadBundle\Entity\Lead;
use Starkerxp\LeadBundle\Repository\LeadRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * @Annotation
 */
class LeadExistValidator extends ConstraintValidator
{
    /**
     * @var LeadRepository
     */
    protected $repositoryLead;

    /**
     * NotCreatedSameLeadValidator constructor.
     * @param LeadRepository $repositoryLead
     */
    public function __construct(LeadRepository $repositoryLead)
    {
        $this->repositoryLead = $repositoryLead;
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof LeadExist) {
            throw new UnexpectedTypeException($constraint, __NAMESPACE__.'\LeadExist');
        }

        /** @var Lead $lead */
        $lead = $this->context->getRoot()->getData();
        if ($this->repositoryLead->leadExist($lead->getOrigin(), $lead->getExternalReference())) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ origin }}', $lead->getOrigin())
                ->setParameter('{{ external_reference }}', $lead->getExternalReference())
                ->addViolation();
        }
    }
}
