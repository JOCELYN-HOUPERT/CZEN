<?php

namespace App\Controller\Api;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

#[Route('/api')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class ProfilController extends AbstractController
{
    #[Route('/profil', name: 'api_profil', methods: ['GET'])]
    public function index(): JsonResponse
    {
        $user = $this->getUser();

        return $this->json([
            'id' => $user->getId(),
            'nom' => $user->getNom(),
            'email' => $user->getEmail(),
            'createdAt' => $user->getCreatedAt()->format('d/m/Y'),
        ]);
    }

    #[Route('/profil', name: 'api_profil_supprimer', methods: ['DELETE'])]
    public function supprimer(
        EntityManagerInterface $em,
        TokenStorageInterface $tokenStorage
    ): JsonResponse {
        $user = $this->getUser();
        $tokenStorage->setToken(null);
        $em->remove($user);
        $em->flush();

        return $this->json(['message' => 'Compte supprimé avec succès']);
    }

    #[Route('/profil/password', name: 'api_profil_password', methods: ['PUT'])]
    public function changePassword(
        \Symfony\Component\HttpFoundation\Request $request,
        EntityManagerInterface $em,
        \Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface $hasher
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (empty($data['ancien_password']) || empty($data['nouveau_password'])) {
            return $this->json(['error' => 'Ancien et nouveau mot de passe requis'], 400);
        }

        $user = $this->getUser();

        if (!$hasher->isPasswordValid($user, $data['ancien_password'])) {
            return $this->json(['error' => 'Ancien mot de passe incorrect'], 401);
        }

        if (strlen($data['nouveau_password']) < 6) {
            return $this->json(['error' => 'Le nouveau mot de passe doit faire au moins 6 caractères'], 400);
        }

        $user->setPassword($hasher->hashPassword($user, $data['nouveau_password']));
        $em->flush();

        return $this->json(['message' => 'Mot de passe modifié avec succès']);
    }
}
