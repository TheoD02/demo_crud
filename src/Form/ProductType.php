<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom',
                'help' => 'Nom du produit',
            ])
            ->add('price', MoneyType::class, [
                'currency' => 'EUR',
                'rounding_mode' => \NumberFormatter::ROUND_HALFEVEN,
                'label' => 'Prix',
                'help' => 'Prix du produit'
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'help' => 'Description du produit'
            ])
            //->add('createdAt') // Je supprime createdAt car il est auto générée
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Product::class,
        ]);
    }
}
