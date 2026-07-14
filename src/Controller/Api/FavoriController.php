<?php

namespace App\Controller\Api;

use App\Repository\RessourceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/api')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class FavoriController extends AbstractController
{
    #[Route('/favoris', name: 'api_favoris', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $user = $this->getUser();
        $data = [];

        foreach ($user->getFavoris() as $ressource) {
            $data[] = [
                'id' => $ressource->getId(),
                'titre' => $ressource->getTitre(),
                'contenu' => $ressource->getContenu(),
                'createdAt' => $ressource->getCreatedAt()->format('d/m/Y'),
            ];
        }

        return $this->json($data);
    }

    #[Route('/favoris/{id}', name: 'api_favori_ajouter', methods: ['POST'])]
    public function ajouter(int $id, RessourceRepository $repo, EntityManagerInterface $em): JsonResponse
    {
        $ressource = $repo->find($id);

        if (!$ressource) {
            return $this->json(['error' => 'Ressource non trouvée'], 404);
        }

        $user = $this->getUser();

        if (!$user->getFavoris()->contains($ressource)) {
            $user->addFavori($ressource);
            $em->flush();
        }

        return $this->json(['message' => 'Ajouté aux favoris']);
    }

    #[Route('/favoris/{id}', name: 'api_favori_retirer', methods: ['DELETE'])]
    public function retirer(int $id, RessourceRepository $repo, EntityManagerInterface $em): JsonResponse
    {
        $ressource = $repo->find($id);

        if (!$ressource) {
            return $this->json(['error' => 'Ressource non trouvée'], 404);
        }

        $user = $this->getUser();
        $user->removeFavori($ressource);
        $em->flush();

        return $this->json(['message' => 'Retiré des favoris']);
    }
}
