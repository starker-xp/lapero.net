<?php

namespace Starkerxp\LeadBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class LeadNotExist extends Constraint
{
    public $message = 'Lead "{{ origin }}" "{{ external_reference }}" not exist.';

}
