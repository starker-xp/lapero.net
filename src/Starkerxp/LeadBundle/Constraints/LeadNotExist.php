<?php

namespace Starkerxp\LeadBundle\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class LeadNotExist extends Constraint
{
    public $message = 'Lead "{{ origin }}" "{{ external_reference }}" not exist.';

}
