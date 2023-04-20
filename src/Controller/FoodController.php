<?php

namespace App\Controller;

use App\Entity\Food;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;

class FoodController extends BaseController
{
    #[Route('/food', name: 'food_index', methods: ['GET'])]
    public function indexAction(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $food = $entityManager->getRepository(Food::class)
            ->getAllFilteredByQueryParameters($request);

        $groups = explode(",", $request->query->get('with'));

        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups(array_merge(['food'], $groups))
            ->toArray();

        $json = $serializer->serialize($food, 'json', $context);
        return new JsonResponse(data: $json, json: true);
    }
}