<?php

namespace App\Controller\Api;

use App\Entity\ResultatDiagnostic;
use App\Repository\QuestionnaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class DiagnosticController extends AbstractController
{
    #[Route('/diagnostic', name: 'api_diagnostic', methods: ['GET'])]
    public function index(QuestionnaireRepository $repo): JsonResponse
    {
        $questionnaire = $repo->findOneBy(['titre' => 'Échelle de stress de Holmes et Rahe']);

        $questions = [];
        foreach ($questionnaire->getQuestions() as $question) {
            $questions[] = [
                'id' => $question->getId(),
                'texte' => $question->getTexte(),
                'poids' => $question->getPoids(),
            ];
        }

        return $this->json([
            'id' => $questionnaire->getId(),
            'titre' => $questionnaire->getTitre(),
            'questions' => $questions,
        ]);
    }

    #[Route('/diagnostic/resultat', name: 'api_diagnostic_resultat', methods: ['POST'])]
    public function resultat(Request $request, EntityManagerInterface $em, QuestionnaireRepository $repo): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $questionsSelectionnees = $data['questions'] ?? [];

        $questionnaire = $repo->findOneBy(['titre' => 'Échelle de stress de Holmes et Rahe']);

        $score = 0;
        foreach ($questionnaire->getQuestions() as $question) {
            if (in_array((string)$question->getId(), array_map('strval', $questionsSelectionnees))) {
                $score += $question->getPoids();
            }
        }

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

        if ($this->getUser()) {
            $resultat = new ResultatDiagnostic();
            $resultat->setScore($score);
            $resultat->setUser($this->getUser());
            $em->persist($resultat);
            $em->flush();
        }

        return $this->json([
            'score' => $score,
            'niveau' => $niveau,
            'message' => $message,
        ]);
    }
}
