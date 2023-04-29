<?php

namespace App\Controller;

use App\Entity\Food;
use App\Form\FoodFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Utils\FoodRequestValidator;
use Twig\Environment;


class FoodController extends BaseController
{
    #[Route('/food', name: 'food_index', methods: ['GET'])]
    public function indexAction(Request                $request,
                                EntityManagerInterface $entityManager,
                                SerializerInterface    $serializer,
                                PaginatorInterface     $paginator,
                                ValidatorInterface     $validator): JsonResponse
    {
        //Request validation
        if ($errors = FoodRequestValidator::validate($request->query->all(), $validator)) {
            return $errors;
        };

        $food = $entityManager->getRepository(Food::class)
            ->getAllFilteredByQueryParameters($request);

        $groups = explode(',', $request->query->get('with'));

        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups(array_merge(['food'], $groups))
            ->toArray();

        $pagination = $paginator->paginate($food,
            $request->query->getInt('page', 1),
            $request->query->getInt('per_page', 10)
        );

        foreach ($pagination->getItems() as $object) {
            $object->setStatus($request->query->get('diff_time'));
        }

        $data = $serializer->serialize($pagination, 'json', $context);
        $data = json_decode($data);

        $this->translator->setLocale($request->query->get('lang'));
        $data = $this->translateData($data);

        $meta = $this->getMeta($pagination);

        $json = [
            'meta' => $meta,
            'data' => $data
        ];

        return new JsonResponse($json);
    }

    #[Route('/post', name: "post_form")]
    public function postAction(Environment $twig, Request $request, EntityManagerInterface $entityManager)
    {
        $food = new Food();
        $form = $this->createForm(FoodFormType::class, $food);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($food);
            $entityManager->flush();

            return new Response('Food ' . $food->getName() . ' successfully added!');
        }

        return new Response($twig->render('food/form.html.twig', [
            'food_form' => $form->createView()
        ]));
    }
}
