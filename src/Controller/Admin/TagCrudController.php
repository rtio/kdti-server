<?php

namespace App\Controller\Admin;

use App\Entity\Tag;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TagCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tag::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Tag')
            ->setEntityLabelInPlural('Tag')
            ->setSearchFields(['id', 'name', 'slug']);
    }

    public function configureFields(string $pageName): iterable
    {
        $slug = TextField::new('slug');
        $name = TextField::new('name');
        $id = IntegerField::new('id', 'ID');
        $jobOffers = AssociationField::new('jobOffers');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $name];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $name, $slug, $jobOffers];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$slug, $name];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$slug, $name];
        }
    }
}
