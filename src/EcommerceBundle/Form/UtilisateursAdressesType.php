<?php

namespace EcommerceBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Doctrine\ORM\EntityManager;

class UtilisateursAdressesType extends AbstractType
{

    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom', null, array('label' => 'label.name'))
                ->add('prenom')
                ->add('telephone')
                ->add('adresse')
                ->add('cp', null, array('attr' => array('class' => 'cp', 'maxlength' => 5)))
                ->add('ville', ChoiceType::class, array('attr' => array('class' => 'ville')))
                ->add('pays')
                ->add('complement',null,array('required' => false));
                //->add('utilisateur');

        $city = function(FormInterface $form, $cp) {
            $villeCodePostal = $this->em->getRepository('UtilisateursBundle:Villes')->findBy(array('villeCodePostal' => $cp));
            if ($villeCodePostal) {
                $villes = array();
                foreach($villeCodePostal as $ville) {
                    $villes[$ville->getVilleNom()] = $ville->getVilleNom();
                }
            } else {
                $villes = null;
            }
            $form->add('ville',ChoiceType::class, array('attr' => array('class'   => 'ville'),
                'choices' => $villes));
        };

        $builder->get('cp')->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event) use ($city) {
            $city($event->getForm()->getParent(),$event->getForm()->getData());
        });
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'EcommerceBundle\Entity\UtilisateursAdresses'
        ));

        $resolver->setRequired('entity_manager');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ecommercebundle_utilisateursadresses';
    }


}
