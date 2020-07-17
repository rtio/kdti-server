<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Company;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class CompanyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Company::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Company')
            ->setEntityLabelInPlural('Company')
            ->setSearchFields(['id', 'name', 'logo', 'address', 'email', 'phoneNumber']);
    }

    public function configureFields(string $pageName): iterable
    {
        $panel1 = FormField::addPanel('Company Information');
        $name = TextField::new('name');
        $logo = TextField::new('logo');
        $address = TextField::new('address');
        $email = TextField::new('email');
        $id = IntegerField::new('id', 'ID');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $name];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $name, $logo, $address, $email];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$panel1, $name, $logo, $address, $email];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$panel1, $name, $logo, $address, $email];
        }
    }
}
