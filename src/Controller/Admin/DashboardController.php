<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Company;
use App\Entity\Conference;
use App\Entity\JobOffer;
use App\Entity\Staff;
use App\Entity\Tag;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin_dashboard")
     */
    public function index(): Response
    {
        $productListUrl = $this
            ->get(CrudUrlGenerator::class)
            ->build()
            ->setController(JobOfferCrudController::class)
            ->generateUrl();

        return $this->redirect($productListUrl);
    }

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
        yield MenuItem::section('Conferences');
        yield MenuItem::linkToCrud('Conference', 'fa fa-tag', Conference::class);
        yield MenuItem::section('Profile');
        yield MenuItem::linkToCrud('Staff', 'fa fa-user', Staff::class);
        yield MenuItem::linkToLogout('Logout', 'fa fa-sign-out');
    }
}
