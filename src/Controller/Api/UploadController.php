<?php

// src/Controller/Api/UploadController.php
namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api')]
class UploadController extends AbstractController
{
    #[Route('/upload', name: 'api_upload', methods: ['POST'])]
    public function upload(Request $request, MessageBusInterface $bus): JsonResponse
    {
        $file = $request->files->get('image');
        $to = $request->get('to', 'user@example.com');

        if (!$file) {
            return $this->json(['error' => 'Aucune image envoyée'], 400);
        }

        $destination = $this->getParameter('kernel.project_dir') . '/public/uploads';
        $filename = uniqid() . '.' . $file->guessExtension();
        $file->move($destination, $filename);

        $bus->dispatch(new SendEmailMessage(
            $to,
            'Image reçue',
            '<p>Voici votre image envoyée depuis l’API.</p>',
            $destination . '/' . $filename
        ));

        return $this->json([
            'message' => 'Image uploadée et email envoyé',
            'file_url' => '/uploads/' . $filename
        ]);
    }
}
