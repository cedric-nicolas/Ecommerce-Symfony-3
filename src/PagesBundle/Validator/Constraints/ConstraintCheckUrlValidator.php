<?php

namespace PagesBundle\Validator\Constraints;

use PagesBundle\Services\CurlUrl;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ConstraintCheckUrlValidator extends ConstraintValidator {


    private $curl;

    public function __construct(CurlUrl $curl){
        $this->curl = $curl;
    }

    // Value c'est le champ passé dans le text area
    public function validate($value, Constraint $constraint){

        //die($constraint->test);
        if($this->curl->findUrl($value)){
            $this->context->addViolation($constraint->message);
        }

    }
    

}