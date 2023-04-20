<?php

namespace App\Controller;

use App\Entity\Food;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;
use Knp\Component\Pager\Paginator;

class FoodController extends BaseController
{
    #[Route('/food', name: 'food_index', methods: ['GET'])]
    public function indexAction(Request                $request,
                                EntityManagerInterface $entityManager,
                                SerializerInterface    $serializer,
                                PaginatorInterface     $paginator): JsonResponse
    {
        $food = $entityManager->getRepository(Food::class)
            ->getAllFilteredByQueryParameters($request);

        $perPage = $request->query->get('per_page') ?: 10;
        $page = $request->query->get('page') ?: 1;

        $pagination = $paginator->paginate(
            $food, $request->query->getInt('page', $page), $perPage
        );

        $groups = explode(",", $request->query->get('with'));

        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups(array_merge(['food'], $groups))
            ->toArray();

        $data = $serializer->serialize($pagination, 'json', $context);

        $json = [
            'meta' => [
                "currentPage" => $pagination->getCurrentPageNumber(),
                "totalItems" => $pagination->getTotalItemCount(),
                "itemsPerPage" => $pagination->getItemNumberPerPage(),
                "totalPages" => ceil($pagination->getTotalItemCount() / $pagination->getItemNumberPerPage())
            ],
            'data' => json_decode($data)
        ];

        return new JsonResponse(data: $json);
    }
}