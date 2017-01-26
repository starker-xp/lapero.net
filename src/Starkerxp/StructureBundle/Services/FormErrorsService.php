<?php
namespace Starkerxp\StructureBundle\Services;

use Symfony\Bundle\FrameworkBundle\Translation\Translator;
use Symfony\Component\Form\Form;


class FormErrorsService
{

    /**
     * @var Translator
     */
    private $translator;

    /**
     * FormErrorsService constructor.
     * @param $translator
     */
    public function __construct(Translator $translator)
    {
        $this->translator = $translator;
    }


    public function getFormErrors(Form $form)
    {
        $errors = [];
        foreach ($form->getErrors() as $error) {
            $errors[$form->getName()][] = $error->getMessage();
        }
        foreach ($form as $child) {
            if (!$child->isValid()) {
                foreach ($child->getErrors() as $error) {
                    $message = !empty($this->translator) ? $this->translator->trans($error->getMessage()) : $error->getMessage();
                    $errors[$child->getName()][] = $message;
                }
            }
        }

        return $errors;
    }

}
