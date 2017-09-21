<?php

namespace EcommerceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class RechercheType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('recherche', TextType::class, array('label' => false, 'attr' => array('class' => 'input-medium search-query') ));
    }

    public function getName(){
        return 'ecommerce_ecommercebundle_recherche';
    }

}