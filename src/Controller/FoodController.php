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
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\Constraints\Collection;

class FoodController extends BaseController
{
    #[Route('/food', name: 'food_index', methods: ['GET'])]
    public function indexAction(Request                $request,
                                EntityManagerInterface $entityManager,
                                SerializerInterface    $serializer,
                                PaginatorInterface     $paginator,
                                TranslatorInterface    $translator,
                                ValidatorInterface     $validator): JsonResponse
    {

        $errors = $validator->validate($this->parseQuery($request), $this->getConstraints());

        if (count($errors) > 0) {
            $errorStr = (string)$errors;
            return new JsonResponse($errorStr);
        }

        $food = $entityManager->getRepository(Food::class)
            ->getAllFilteredByQueryParameters($request);


        $translator->setLocale($request->query->get('lang'));

        $groups = explode(",", $request->query->get('with'));

        $context = (new ObjectNormalizerContextBuilder())
            ->withGroups(array_merge(['food'], $groups))
            ->toArray();

        $pagination = $this->getPaginator($request, $paginator, $food);

        foreach ($pagination->getItems() as $object)
        {
            $object->setStatus($request->query->get('diff_time'));
        }

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

    public function getConstraints(): Collection
    {
        return new Assert\Collection([

            'lang' => new Assert\NotNull(),

            'diff_time' => new Assert\Optional([
                new Assert\Type('integer'),
                new Assert\Positive()
            ]),

            'page' => new Assert\Optional([
                new Assert\Type('integer'),
                new Assert\Positive()
            ]),

            'per_page' => new Assert\Optional([
                new Assert\Type('integer'),
                new Assert\Positive()
            ]),

            'tags' => new Assert\Optional([
                new Assert\Type('array'),
                new Assert\All([new Assert\Type('integer')])
            ]),

            'with' => new Assert\Optional([
                new Assert\Type('array'),
                new Assert\All([
                    new Assert\Choice(['category', 'ingredient', 'tag'])])
            ]),

            'category' => new Assert\Optional([
                new Assert\Callback(function ($value, ExecutionContextInterface $context) {
                    if (!is_numeric($value) && $value != 'NULL' && $value != '!NULL') {
                        $context->buildViolation('Category must be an int, NULL or !NULL')
                            ->addViolation();
                    }
                })
            ])
        ]);
    }

    public function parseQuery(Request $request): array
    {
        $params = $request->query->all();

        foreach ($params as $key => $value) {
            if ($key == 'page' || $key == 'per_page' || $key == 'diff_time') {
                $params[$key] = (integer)$value;
            }
            if ($key == 'with') {
                if (str_contains($value, ',')) {
                    $params[$key] = explode(',', $value);
                } else {
                    $params[$key] = [$value];
                }
            }
            if ($key == 'tags') {
                if (str_contains($value, ',')) {
                    $params[$key] = array_map('intval', explode(',', $value));
                } else {
                    if (ctype_digit($value)) {
                        $params[$key] = [(integer)$value];
                    }
                }
            }
            if ($key == 'category') {
                if ($value !== "NULL" && $value !== "!NULL") {
                    if (preg_match("/^\d+$/", $value)) {
                        $params[$key] = (integer)$value;
                    }
                }
            }
        }
        return $params;
    }
}
