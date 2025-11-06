<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[Route('/api/categories')]
class CategoryController extends AbstractController
{
    /* Lister toutes les catégories */
    #[Route('/list', name: 'category_list', methods: ['GET'])]
    public function list(CategoryRepository $categoryRepository): JsonResponse
    {
        $categories = $categoryRepository->findAll();

        $data = array_map(fn(Category $cat) => [
            'id' => $cat->getId(),
            'name' => $cat->getName(),
        ], $categories);

        return $this->json($data);
    }

    /*  Afficher une catégorie par ID */
    #[Route('/show/{id}', name: 'category_show', methods: ['GET'])]
    public function show(Category $category): JsonResponse
    {
        return $this->json([
            'id' => $category->getId(),
            'name' => $category->getName(),
        ]);
    }

    
    /*  Créer une catégorie (ADMIN uniquement)*/
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/create', name: 'category_create', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $em,
        ValidatorInterface $validator
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['name'])) {
            return $this->json(['error' => 'Le champ "name" est requis.'], Response::HTTP_BAD_REQUEST);
        }

        $category = new Category();
        $category->setName($data['name']);

        
        $errors = $validator->validate($category);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        $em->persist($category);
        $em->flush();

        return $this->json([
            'message' => 'Catégorie créée avec succès ',
            'id' => $category->getId(),
        ], Response::HTTP_CREATED);
    }

    /*  Mettre à jour une catégorie (ADMIN uniquement)*/
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/update/{id}', name: 'category_update', methods: ['PUT', 'PATCH'])]
    public function update(
        Category $category,
        Request $request,
        EntityManagerInterface $em,
        ValidatorInterface $validator
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (isset($data['name'])) {
            $category->setName($data['name']);
        }


        $errors = $validator->validate($category);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        $em->flush();

        return $this->json(['message' => 'Catégorie mise à jour ']);
    }

    /* Supprimer une catégorie (ADMIN uniquement)*/
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/delete/{id}', name: 'category_delete', methods: ['DELETE'])]
    public function delete(Category $category, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($category);
        $em->flush();

        return $this->json(['message' => 'Catégorie supprimée ']);
    }
}

