<?php

namespace App\Controller\Admin;

use App\Entity\JobOffer;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class JobOfferCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return JobOffer::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('JobOffer')
            ->setEntityLabelInPlural('JobOffer')
            ->setSearchFields(['id', 'slug', 'title', 'description', 'seniorityLevel', 'minimumSalary', 'maximumSalary', 'status', 'hiringType']);
    }

    public function configureFields(string $pageName): iterable
    {
        $panel1 = FormField::addPanel('Job Offer Details');
        $slug = TextField::new('slug');
        $title = TextField::new('title');
        $description = TextareaField::new('description');
        $seniorityLevel = TextField::new('seniorityLevel');
        $minimumSalary = IntegerField::new('minimumSalary');
        $maximumSalary = IntegerField::new('maximumSalary');
        $allowRemote = Field::new('allowRemote');
        $publishedAt = DateTimeField::new('publishedAt');
        $status = TextField::new('status');
        $hiringType = TextField::new('hiringType');
        $tags = AssociationField::new('tags');
        $company = AssociationField::new('company');
        $id = IntegerField::new('id', 'ID');
        $createdAt = DateTimeField::new('createdAt');
        $updatedAt = DateTimeField::new('updatedAt');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $title, $company];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $slug, $title, $description, $seniorityLevel, $minimumSalary, $maximumSalary, $status, $createdAt, $updatedAt, $publishedAt, $hiringType, $allowRemote, $company, $tags];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$panel1, $slug, $title, $description, $seniorityLevel, $minimumSalary, $maximumSalary, $allowRemote, $publishedAt, $status, $hiringType, $tags, $company];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$panel1, $slug, $title, $description, $seniorityLevel, $minimumSalary, $maximumSalary, $allowRemote, $publishedAt, $status, $hiringType, $tags, $company];
        }
    }
}
