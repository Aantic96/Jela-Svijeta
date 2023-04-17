<?php

namespace App\Controller;

use App\Entity\Food;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class FoodController extends AbstractController
{
    #[Route('/food')]
    public function indexAction(EntityManagerInterface $entityManager): JsonResponse
    {
        $foodRepository = $entityManager->getRepository(Food::class);
        return $this->json($foodRepository->findAll());
    }
}