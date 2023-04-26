<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;

class BaseController extends AbstractController
{
    protected $translator;

    public function setTranslator(TranslatorInterface $translator): void
    {
        $this->translator = $translator;
    }

    protected function getMeta($pagination): array
    {
        return [
            'currentPage' => $pagination->getCurrentPageNumber(),
            'totalItems' => $pagination->getTotalItemCount(),
            'itemsPerPage' => $pagination->getItemNumberPerPage(),
            'totalPages' => ceil($pagination->getTotalItemCount() / $pagination->getItemNumberPerPage())
        ];
    }

    //Extra, just wanted to try out key translation
    protected function translateMeta(array $meta, $translator): array
    {
        $translatedMeta = [];
        foreach ($meta as $key => $value) {
            $translatedMeta[$translator->trans($key)] = $value;
        }
        return $translatedMeta;
    }

    protected function translateData($data): mixed
    {
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $data->$key = $this->translator->trans($value);
            } elseif (is_object($value) || is_array(($value))) {
                $this->translateData($value);
            }
        }
        return $data;
    }

}