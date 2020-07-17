<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\JobOffer;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
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
        $slug = SlugField::new('slug')->setTargetFieldName('title');
        $title = TextField::new('title');
        $description = TextareaField::new('description');
        $seniorityLevel = ChoiceField::new('seniorityLevel')->setChoices(
            [
                JobOffer::HIRING_TYPE_CLT => JobOffer::HIRING_TYPE_CLT,
                JobOffer::HIRING_TYPE_PJ => JobOffer::HIRING_TYPE_PJ
            ]
        );
        $minimumSalary = IntegerField::new('minimumSalary');
        $maximumSalary = IntegerField::new('maximumSalary');
        $allowRemote = Field::new('allowRemote');
        $publishedAt = DateTimeField::new('publishedAt');
        $status = ChoiceField::new('status')->setChoices(
            [
                JobOffer::STATUS_APPROVED => JobOffer::STATUS_APPROVED,
                JobOffer::STATUS_PENDING_REVIEW => JobOffer::STATUS_PENDING_REVIEW
            ]
        );
        $hiringType = ChoiceField::new('hiringType')->setChoices(
            [
                JobOffer::HIRING_TYPE_CLT => JobOffer::HIRING_TYPE_CLT,
                JobOffer::HIRING_TYPE_PJ => JobOffer::HIRING_TYPE_PJ
            ]
        );
        $tags = AssociationField::new('tags')
            ->setFormTypeOption('by_reference', false)
            ->autocomplete();
        $company = AssociationField::new('company');
        $id = IdField::new('id');
        $createdAt = DateTimeField::new('createdAt');
        $updatedAt = DateTimeField::new('updatedAt');

        if (Crud::PAGE_INDEX === $pageName) {
            return [$id, $title, $company];
        } elseif (Crud::PAGE_DETAIL === $pageName) {
            return [$id, $slug, $title, $description, $seniorityLevel, $minimumSalary, $maximumSalary, $status,
                $createdAt, $updatedAt, $publishedAt, $hiringType, $allowRemote, $company, $tags];
        } elseif (Crud::PAGE_NEW === $pageName) {
            return [$panel1, $slug, $title, $description, $seniorityLevel, $minimumSalary, $maximumSalary, $allowRemote,
                $publishedAt, $status, $hiringType, $tags, $company];
        } elseif (Crud::PAGE_EDIT === $pageName) {
            return [$panel1, $slug, $title, $description, $seniorityLevel, $minimumSalary, $maximumSalary, $allowRemote,
                $publishedAt, $status, $hiringType, $tags, $company];
        }
    }
}
