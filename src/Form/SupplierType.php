<?php

namespace App\Form;

use App\Entity\Product;
use App\Entity\Shop;
use App\Entity\Supplier;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SupplierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('address')
            ->add('company')
            ->add('delivery_date')
            ->add('amount')
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Pending' => 'PENDING',
                    'Delivered' => 'DELIVERED',
                    'Canceled' => 'CANCELED',
                ],
                'multiple' => false, // Single selection (dropdown)
                'expanded' => false, // Render as a <select> dropdown
                'label' => 'Status',
                'required' => true,
                'placeholder' => 'Select a status', // Optional: Adds a default "Select" option
                'attr' => [
                    'class' => 'form-control', // Bootstrap class for styling
                ],
            ])
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'choice_label' => 'name',
            ])
            ->add('shop', EntityType::class, [
                'class' => Shop::class,
                'choice_label' => 'name',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Supplier::class,
        ]);
    }
}
