<?php

namespace App\Controller\Admin;

use App\Repository\ResultatDiagnosticRepository;
use App\Repository\UserRepository;
use App\Repository\RessourceRepository;
use App\Entity\Question;
use App\Entity\Questionnaire;
use App\Entity\Ressource;
use App\Entity\ResultatDiagnostic;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private ResultatDiagnosticRepository $resultatRepo,
        private UserRepository $userRepo,
        private RessourceRepository $ressourceRepo,
    ) {}

    public function index(): Response
    {
        $resultats = $this->resultatRepo->findAll();

        $faible = 0;
        $modere = 0;
        $eleve = 0;
        $totalScore = 0;

        foreach ($resultats as $r) {
            $totalScore += $r->getScore();
            if ($r->getScore() < 150) $faible++;
            elseif ($r->getScore() < 300) $modere++;
            else $eleve++;
        }

        $moyenneScore = count($resultats) > 0
            ? round($totalScore / count($resultats))
            : 0;

        return $this->render('admin/dashboard.html.twig', [
            'nbUtilisateurs' => count($this->userRepo->findAll()),
            'nbDiagnostics' => count($resultats),
            'nbRessources' => count($this->ressourceRepo->findAll()),
            'moyenneScore' => $moyenneScore,
            'faible' => $faible,
            'modere' => $modere,
            'eleve' => $eleve,
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('CESIZen Admin');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Tableau de bord', 'fa fa-home');
        yield MenuItem::linkTo(UserCrudController::class, 'Utilisateurs', 'fa fa-users')->setAction('index');
        yield MenuItem::linkTo(RessourceCrudController::class, 'Ressources', 'fa fa-file-alt')->setAction('index');
        yield MenuItem::linkTo(QuestionnaireCrudController::class, 'Questionnaires', 'fa fa-clipboard')->setAction('index');
        yield MenuItem::linkTo(QuestionCrudController::class, 'Questions', 'fa fa-question')->setAction('index');
        yield MenuItem::linkTo(ResultatDiagnosticCrudController::class, 'Résultats', 'fa fa-chart-bar')->setAction('index');
    }
}
