<?php

namespace App\Controller;

use App\Repository\RessourceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class FavoriController extends AbstractController
{
    #[Route('/favoris', name: 'app_favoris')]
    #[IsGranted('ROLE_USER')]
    public function index(): Response
    {
        $user = $this->getUser();

        return $this->render('favori/index.html.twig', [
            'favoris' => $user->getFavoris(),
        ]);
    }

    #[Route('/favoris/ajouter/{id}', name: 'app_favori_ajouter')]
    #[IsGranted('ROLE_USER')]
    public function ajouter(int $id, RessourceRepository $repo, EntityManagerInterface $em): Response
    {
        $ressource = $repo->find($id);
        $user = $this->getUser();

        if (!$user->getFavoris()->contains($ressource)) {
            $user->addFavori($ressource);
            $em->flush();
        }

        return $this->redirectToRoute('app_ressource_detail', ['id' => $id]);
    }

    #[Route('/favoris/retirer/{id}', name: 'app_favori_retirer')]
    #[IsGranted('ROLE_USER')]
    public function retirer(int $id, RessourceRepository $repo, EntityManagerInterface $em): Response
    {
        $ressource = $repo->find($id);
        $user = $this->getUser();

        $user->removeFavori($ressource);
        $em->flush();

        return $this->redirectToRoute('app_ressource_detail', ['id' => $id]);
    }
}
