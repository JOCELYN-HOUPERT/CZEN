<?php

namespace App\Controller;

use App\Entity\ResultatDiagnostic;
use App\Repository\QuestionnaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DiagnosticController extends AbstractController
{
    #[Route('/diagnostic', name: 'app_diagnostic')]
    public function index(QuestionnaireRepository $repo): Response
    {
        $questionnaire = $repo->findOneBy(['titre' => 'Échelle de stress de Holmes et Rahe']);

        return $this->render('diagnostic/index.html.twig', [
            'questionnaire' => $questionnaire,
        ]);
    }

    #[Route('/diagnostic/resultat', name: 'app_diagnostic_resultat', methods: ['POST'])]
    public function resultat(Request $request, EntityManagerInterface $em, QuestionnaireRepository $repo): Response
    {
        $questionnaire = $repo->findOneBy(['titre' => 'Échelle de stress de Holmes et Rahe']);
        $questionsSelectionnees = $request->request->all('questions') ?? [];

        $score = 0;
        foreach ($questionnaire->getQuestions() as $question) {
            if (in_array($question->getId(), array_map('intval', $questionsSelectionnees))) {
                $score += $question->getPoids();
            }
        }

        // Interprétation du score
        if ($score < 150) {
            $niveau = 'Faible';
            $message = 'Votre niveau de stress est faible. Continuez à prendre soin de vous !';
        } elseif ($score < 300) {
            $niveau = 'Modéré';
            $message = 'Votre niveau de stress est modéré. Pensez à vous accorder du repos.';
        } else {
            $niveau = 'Élevé';
            $message = 'Votre niveau de stress est élevé. Nous vous recommandons de consulter un professionnel.';
        }

        // Sauvegarde si connecté
        if ($this->getUser()) {
            $resultat = new ResultatDiagnostic();
            $resultat->setScore($score);
            $resultat->setUser($this->getUser());
            $em->persist($resultat);
            $em->flush();
        }

        return $this->render('diagnostic/resultat.html.twig', [
            'score' => $score,
            'niveau' => $niveau,
            'message' => $message,
        ]);
    }
}
