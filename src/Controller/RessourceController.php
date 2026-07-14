<?php

namespace App\Controller;

use App\Repository\RessourceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
class RessourceController extends AbstractController
{
    #[Route('/ressources', name: 'api_ressources', methods: ['GET'])]
    public function index(RessourceRepository $repo): JsonResponse
    {
        $ressources = $repo->findAll();

        $data = [];
        foreach ($ressources as $ressource) {
            $data[] = [
                'id' => $ressource->getId(),
                'titre' => $ressource->getTitre(),
                'contenu' => $ressource->getContenu(),
                'createdAt' => $ressource->getCreatedAt()->format('d/m/Y'),
            ];
        }

        return $this->json($data);
    }

    #[Route('/ressources/{id}', name: 'api_ressource_detail', methods: ['GET'])]
    public function detail(int $id, RessourceRepository $repo): JsonResponse
    {
        $ressource = $repo->find($id);

        if (!$ressource) {
            return $this->json(['error' => 'Ressource non trouvée'], 404);
        }

        return $this->json([
            'id' => $ressource->getId(),
            'titre' => $ressource->getTitre(),
            'contenu' => $ressource->getContenu(),
            'createdAt' => $ressource->getCreatedAt()->format('d/m/Y'),
        ]);
    }
}
