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
use Symfony\Contracts\Translation\TranslatorInterface;

class FoodController extends BaseController
{
    #[Route('/food', name: 'food_index', methods: ['GET'])]
    public function indexAction(Request                $request,
                                EntityManagerInterface $entityManager,
                                SerializerInterface    $serializer,
                                PaginatorInterface     $paginator,
                                TranslatorInterface    $translator): JsonResponse
    {
        //TODO: Add validators

        $food = $entityManager->getRepository(Food::class)
            ->getAllFilteredByQueryParameters($request);

        $lang = $request->query->get('lang');

        $translator->setLocale($lang);

        $groups = explode(",", $request->query->get('with'));

        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups(array_merge(['food'], $groups))
            ->toArray();

        $pagination = $this->getPaginator($request, $paginator, $food);

        $data = $serializer->serialize($pagination, 'json', $context);
        $data = json_decode($data);
        $data = $this->translateData($data, $translator);

        $meta = $this->getMeta($pagination);

        $json = [
            'meta' => $meta,
            'data' => $data
        ];

       return new JsonResponse($json);
    }


}
