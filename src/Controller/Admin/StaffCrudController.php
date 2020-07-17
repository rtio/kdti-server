<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Staff;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class StaffCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Staff::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Staff')
            ->setEntityLabelInPlural('Staff')
            ->setSearchFields(['id', 'username']);
    }

    public function configureFields(string $pageName): iterable
    {
        yield FormField::addPanel('Staff Details');
        yield IdField::new('id');
        yield TextField::new('username');
    }
}
