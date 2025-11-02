<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[Route('/api/users')]
class UserController extends AbstractController
{
    /*  Lister tous les utilisateurs */

    #[Route('/list', name: 'user_list', methods: ['GET'])]
    public function list(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();

        $data = array_map(fn(User $user) => [
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
        ], $users);

        return $this->json($data);
    }

    /*  Afficher un utilisateur par ID */

    #[Route('/show/{id}', name: 'user_show', methods: ['GET'])]
    public function show(User $user): JsonResponse
    {
        return $this->json([
            'id' => $user->getId(),
            'email' => $user->getEmail(),
            'roles' => $user->getRoles(),
        ]);
    }

    /*  Créer un utilisateur (ADMIN uniquement)*/

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/create', name: 'user_create', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['email'], $data['password'])) {
            return $this->json(['error' => 'Email et mot de passe requis.'], Response::HTTP_BAD_REQUEST);
        }

        $user = new User();
        $user->setEmail($data['email']);
        $user->setRoles($data['roles'] ?? ['ROLE_USER']);
        $user->setPassword($passwordHasher->hashPassword($user, $data['password']));

        $em->persist($user);
        $em->flush();

        return $this->json([
            'message' => 'Utilisateur créé avec succès ',
            'id' => $user->getId(),
        ], Response::HTTP_CREATED);
    }

    /**
     *  Modifier un utilisateur (ADMIN uniquement)
     * PUT /api/users/update/{id}
     */
    // #[IsGranted('ROLE_ADMIN')]
    // #[Route('/update/{id}', name: 'user_update', methods: ['PUT', 'PATCH'])]
    // public function update(
    //     User $user,
    //     Request $request,
    //     EntityManagerInterface $em,
    //     UserPasswordHasherInterface $passwordHasher
    // ): JsonResponse {
    //     $data = json_decode($request->getContent(), true);

    //     if (isset($data['email'])) {
    //         $user->setEmail($data['email']);
    //     }

    //     if (isset($data['password'])) {
    //         $user->setPassword($passwordHasher->hashPassword($user, $data['password']));
    //     }

    //     if (isset($data['roles'])) {
    //         $user->setRoles($data['roles']);
    //     }

    //     $em->flush();

    //     return $this->json(['message' => 'Utilisateur mis à jour ']);
    // }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/update/{id}', name: 'user_update', methods: ['PUT', 'PATCH'])]
    public function update(User $user,  Request $request,  EntityManagerInterface $em,  UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return $this->json(['error' => 'Format JSON invalide.'], Response::HTTP_BAD_REQUEST);
        }

        
        if (isset($data['email'])) {
            if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                return $this->json(['error' => 'Adresse email invalide.'], Response::HTTP_BAD_REQUEST);
            }
            $user->setEmail($data['email']);
        }

       
        if (isset($data['password']) && !empty($data['password'])) {
            $hashedPassword = $passwordHasher->hashPassword($user, $data['password']);
            $user->setPassword($hashedPassword);
        }

       
        if (isset($data['roles'])) {
            if (!is_array($data['roles'])) {
                return $this->json(['error' => 'Le champ roles doit être un tableau.'], Response::HTTP_BAD_REQUEST);
            }
            $user->setRoles($data['roles']);
        }

      
        if (isset($data['subscription_to_newsletter'])) {
            $subscriptionValue = filter_var($data['subscription_to_newsletter'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
            if ($subscriptionValue === null) {
                return $this->json(['error' => 'Le champ subscription_to_newsletter doit être un booléen.'], Response::HTTP_BAD_REQUEST);
            }
            $user->setSubscriptionToNewsletter($subscriptionValue);
        }

    
        $em->flush();

        return $this->json([
            'message' => 'Utilisateur mis à jour avec succès ',
            'user' => [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'roles' => $user->getRoles(),
                'subscription_to_newsletter' => $user->isSubscriptionToNewsletter(),
            ]
        ]);
    }


    /* Supprimer un utilisateur (ADMIN uniquement)*/

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/delete/{id}', name: 'user_delete', methods: ['DELETE'])]
    public function delete(User $user, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($user);
        $em->flush();

        return $this->json(['message' => 'Utilisateur supprimé ']);
    }
}
