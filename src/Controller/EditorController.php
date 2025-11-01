<?php

namespace App\Controller;

use App\Entity\Editor;
use App\Repository\EditorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[Route('/api/v1/editors')]
final class EditorController extends AbstractController
{
    /**
     * 🟢 Liste de tous les éditeurs
     * GET /api/v1/editors
     */
    #[Route('', name: 'get_editors', methods: ['GET'])]
    public function getEditors(EditorRepository $editorRepository): JsonResponse
    {
        $editors = $editorRepository->findAll();
        return $this->json($editors, Response::HTTP_OK);
    }

    /**
     * 🔵 Détails d’un éditeur par ID
     * GET /api/v1/editors/{id}
     */
    #[Route('/{id}', name: 'get_editor', requirements: ['id' => Requirement::DIGITS], methods: ['GET'])]
    public function getEditor(int $id, EditorRepository $editorRepository): JsonResponse
    {
        $editor = $editorRepository->find($id);

        if (!$editor) {
            return $this->json(['error' => 'Éditeur non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($editor, Response::HTTP_OK);
    }

    /**
     * 🟡 Créer un éditeur (ADMIN uniquement)
     * POST /api/v1/editors/create
     */
    #[IsGranted('ROLE_ADMIN', message: 'Seuls les administrateurs peuvent créer des éditeurs.')]
    #[Route('/create', name: 'create_editor', methods: ['POST'])]
    public function createEditor(
        Request $request,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        ValidatorInterface $validator,
        UrlGeneratorInterface $urlGenerator
    ): JsonResponse {
        $editor = $serializer->deserialize($request->getContent(), Editor::class, 'json');

        // Validation via Asserts
        $errors = $validator->validate($editor);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        $em->persist($editor);
        $em->flush();

        $location = $urlGenerator->generate('get_editor', ['id' => $editor->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return $this->json($editor, Response::HTTP_CREATED, ["Location" => $location]);
    }

    /**
     * 🟠 Mettre à jour un éditeur (ADMIN uniquement)
     * PUT /api/v1/editors/update/{id}
     */
    #[IsGranted('ROLE_ADMIN', message: 'Seuls les administrateurs peuvent modifier des éditeurs.')]
    #[Route('/update/{id}', name: 'update_editor', requirements: ['id' => Requirement::DIGITS], methods: ['PUT', 'PATCH'])]
    public function updateEditor(
        Request $request,
        Editor $currentEditor,
        SerializerInterface $serializer,
        EntityManagerInterface $em,
        ValidatorInterface $validator
    ): JsonResponse {
        $updatedEditor = $serializer->deserialize(
            $request->getContent(),
            Editor::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $currentEditor]
        );

        $errors = $validator->validate($updatedEditor);
        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $this->json(['errors' => $errorMessages], Response::HTTP_BAD_REQUEST);
        }

        $em->persist($updatedEditor);
        $em->flush();

        return $this->json(['message' => 'Éditeur mis à jour avec succès ✅'], Response::HTTP_OK);
    }

    /**
     * 🔴 Supprimer un éditeur (ADMIN uniquement)
     * DELETE /api/v1/editors/delete/{id}
     */
    #[IsGranted('ROLE_ADMIN', message: 'Seuls les administrateurs peuvent supprimer des éditeurs.')]
    #[Route('/delete/{id}', name: 'delete_editor', requirements: ['id' => Requirement::DIGITS], methods: ['DELETE'])]
    public function deleteEditor(Editor $editor, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($editor);
        $em->flush();

        return $this->json(['message' => 'Éditeur supprimé avec succès ✅'], Response::HTTP_OK);
    }
}
