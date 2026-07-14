<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[IsGranted('ROLE_USER')]
class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'app_profil')]
    public function index(): Response
    {
        return $this->render('profil/index.html.twig', [
            'user' => $this->getUser(),
        ]);
    }

    #[Route('/profil/supprimer', name: 'app_profil_supprimer', methods: ['POST'])]
    public function supprimer(
        Request $request,
        EntityManagerInterface $em,
        TokenStorageInterface $tokenStorage
    ): Response {
        if ($this->isCsrfTokenValid('supprimer_compte', $request->request->get('_token'))) {
            $user = $this->getUser();
            $tokenStorage->setToken(null);
            $request->getSession()->invalidate();
            $em->remove($user);
            $em->flush();

            $this->addFlash('success', 'Votre compte a été supprimé.');
            return $this->redirectToRoute('app_home');
        }

        return $this->redirectToRoute('app_profil');
    }
}
