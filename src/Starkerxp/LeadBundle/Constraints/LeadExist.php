<?php

namespace Starkerxp\LeadBundle\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class LeadExist extends Constraint
{
    public $message = 'Lead "{{ origin }}" "{{ external_reference }}" already exist.';

}
