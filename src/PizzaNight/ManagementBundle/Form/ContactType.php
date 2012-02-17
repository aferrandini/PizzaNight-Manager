<?php

namespace PizzaNight\ManagementBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilder $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('email')
            ->add('phone')
        ;
    }

    public function getName()
    {
        return 'pizzanight_managementbundle_contacttype';
    }

    public function getDefaultOptions(array $options)
    {
        return array(
            'data_class' => 'PizzaNight\ManagementBundle\Entity\Contact',
        );
    }
}
