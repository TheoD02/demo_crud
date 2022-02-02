<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ProductCrudController extends AbstractCrudController
{
    /**
     * Entité correspondante à la page actuelle
     */
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }

    /**
     * Configuration simple des champs visible sur les pages
     */
    public function configureFields(string $pageName): iterable
    {
        $id = IdField::new('id', 'ID');
        $price = IntegerField::new('price', 'ID');
        $description = TextField::new('description', 'ID');
        $createdAt = DateTimeField::new('createdAt', 'ID');


        return [$id, $price, $description, $createdAt];
    }
}
