<?php

namespace App\Controller;

use App\Entity\VideoGame;
use App\Repository\VideoGameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

final class VideoGameController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->json([
            'message' => 'Bienvenue sur mon API Symfony ! ',
        ]);
    }

    #[Route('/videogame', name: 'app_videogame')]
    public function test(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/VideoGameController.php',
        ]);
    }


    #[Route('/api/v1/videogames', name: 'videogames', methods:['GET'])]
    public function getVideogames(VideogameRepository $videogameRepository, SerializerInterface $serializer, Request $request, TagAwareCacheInterface $cachePool): JsonResponse
    {
        $page = $request->get('page', 1);
        $limit = $request->get('limit', 3);

        $cacheIdentifier = "getAllVideogames-" . $page . "-" . $limit;

        $videogameList = $cachePool->get($cacheIdentifier,
            function (ItemInterface $item) use ($videogameRepository, $page, $limit) {
                $item->tag("videogameCache");
                return $videogameRepository->findAllWithPagination($page, $limit);
            }
        );

        return $this->json($videogameList, Response::HTTP_OK, [], ['groups' => 'getVideogame']);
    }

    #[Route('/api/videogame/{id}', name: 'videogame', requirements: ['id' => Requirement::DIGITS], methods: ['GET'])]
    public function getVideogame(int $id, VideoGameRepository $VideogameRepository): JsonResponse {

        $videogame = $VideogameRepository->find($id);
        if ($videogame) {
            return $this->json($videogame, Response::HTTP_OK, [], ['groups' => 'getVideogame']);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('/api/v1/videogame', name: 'createVideogame', methods: ['POST'])]
    public function createVideogame(Request $request, SerializerInterface $serializer, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator): JsonResponse {

        $videogame = $serializer->deserialize($request->getContent(), VideoGame::class, 'json');
        $em->persist($videogame);
        $em->flush();

        $location = $urlGenerator->generate(
            'videogame',
            ['id' => $videogame->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        
       
        return $this->json($videogame, Response::HTTP_CREATED, ["Location" => $location], ['groups' => 'getVideogame']);
    }

    #[Route('/api/v1/videogame/{id}', name: 'updateVideogame', methods: ['PUT'])]
    public function updateVideogame(Request $request, SerializerInterface $serializer, VideoGame $currentVideogame, EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator): JsonResponse {


        $updatedVideogame = $serializer->deserialize($request->getContent(), VideoGame::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $currentVideogame]);
        $em->persist($updatedVideogame);
        $em->flush();

        $location = $urlGenerator->generate(
            'videogame',
            ['id' => $updatedVideogame->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        
        return $this->json(['status' => 'success'], Response::HTTP_OK, ["Location" => $location]);
    }

    #[Route('/api/videogame/{id}', name: 'deleteVideogame', methods: ['DELETE'])]
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'êtes pas autorisé à supprimer cet élément')]
    public function deleteVideogame(Videogame $videogame, EntityManagerInterface $em, TagAwareCacheInterface $cachePool): JsonResponse
    {
        $cachePool->invalidateTags(["videogameCache"]);
        $em->remove($videogame);
        $em->flush();

        return $this->json(['status' => 'success'], Response::HTTP_OK);
    }

}
