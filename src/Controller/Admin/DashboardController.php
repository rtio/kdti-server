<?php

namespace App\Controller\Admin;

use App\Entity\Company;
use App\Entity\JobOffer;
use App\Entity\StaffUser;
use App\Entity\Tag;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\Security\Core\User\UserInterface;

class DashboardController extends AbstractDashboardController
{
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('KDTI');
    }

    public function configureCrud(): Crud
    {
        return Crud::new()
            ->setDateFormat('dd/MM/yyyy')
            ->setDateTimeFormat('dd/MM/yyyy HH:mm:ss')
            ->setTimeFormat('HH:mm');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::section('Companies');
        yield MenuItem::linkToCrud('Company', 'fa fa-building', Company::class);
        yield MenuItem::section('Jobs');
        yield MenuItem::linkToCrud('JobOffer', 'fa fa-newspaper', JobOffer::class);
        yield MenuItem::linkToCrud('Tag', 'fa fa-tag', Tag::class);
        yield MenuItem::section('Profile');
        yield MenuItem::linkToLogout('Logout', 'fa fa-sign-out');
    }
}
