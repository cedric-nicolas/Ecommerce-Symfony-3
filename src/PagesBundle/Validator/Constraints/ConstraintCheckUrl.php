<?php

namespace PagesBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ConstraintCheckUrl extends Constraint {

    public $message = "Le champs contient des liens non valide";

    public function validatedBy(){
        return 'validatorCheckUrl';
    }

}