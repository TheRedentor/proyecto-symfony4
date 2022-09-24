<?php

namespace App\Form;

use App\Entity\Supplier;
use phpDocumentor\Reflection\Types\Nullable;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SupplierFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('email', EmailType::class)
            ->add('phone', NumberType::class)
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Hotel' => 1, 
                    'Pista' => 2, 
                    'Complemento' => 3,
                ],
                'placeholder' => 'Escoge un tipo',
            ])
            ->add('active', CheckboxType::class, ['required' => false])
            ->add('Guardar', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Supplier::class,
        ]);
    }
}
