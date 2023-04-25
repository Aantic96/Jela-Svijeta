<?php

namespace App\Controller;

use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use function Webmozart\Assert\Tests\StaticAnalysis\integer;

class BaseController extends AbstractController
{

    protected function getPaginator(Request $request, PaginatorInterface $paginator, $entity)
    {
        $perPage = $request->query->get('per_page') ?: 10;
        $page = $request->query->get('page') ?: 1;

        return $paginator->paginate($entity, $request->query->getInt('page', $page), $perPage);
    }

    protected function getMeta($pagination): array
    {
        return [
            "currentPage" => $pagination->getCurrentPageNumber(),
            "totalItems" => $pagination->getTotalItemCount(),
            "itemsPerPage" => $pagination->getItemNumberPerPage(),
            "totalPages" => ceil($pagination->getTotalItemCount() / $pagination->getItemNumberPerPage())
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

    protected function translateData($data, $translator)
    {
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $data->$key = $translator->trans($value);
            } elseif (is_object($value) || is_array(($value))) {
                $this->translateData($value, $translator);
            }
        }
        return $data;
    }
}