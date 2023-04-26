<?php

namespace App\Utils;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints\Collection;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FoodRequestValidator
{
    public static function validate(array $requestParams, ValidatorInterface $validator): JsonResponse|null
    {
        $errors = $validator->validate(self::parseQuery($requestParams), self::getConstraints());

        if (count($errors) > 0) {
            $errorStr = (string)$errors;
            return new JsonResponse($errorStr);
        }

        return null;
    }

    public static function getConstraints(): Collection
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

    public static function parseQuery(array $params): array
    {
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