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
    public function upload(Request $request): JsonResponse
    {
        $file = $request->files->get('image');
        if (!$file) {
            return $this->json(['error' => 'No file uploaded'], 400);
        }

        $destination = $this->getParameter('kernel.project_dir') . '/public/uploads';
        $filename = uniqid() . '.' . $file->guessExtension();
        $file->move($destination, $filename);

        return $this->json(['success' => true, 'file' => '/uploads/' . $filename]);
    }
}
