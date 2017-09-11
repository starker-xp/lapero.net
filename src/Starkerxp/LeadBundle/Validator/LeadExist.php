<?php

namespace Starkerxp\LeadBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class LeadExist extends Constraint
{
    public $message = 'Lead "{{ origin }}" "{{ external_reference }}" already exist.';

}
