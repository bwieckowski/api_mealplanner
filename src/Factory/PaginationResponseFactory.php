<?php

namespace App\Factory;

use Symfony\Component\HttpFoundation\Response;

class PaginationResponseFactory implements ResponseFactoryInterface
{
    public function create($pagination,$code = 200)
    {
        $result = [
            'current_page' => $pagination->getCurrentPageNumber(),
            'items' => $pagination->getItems(),
            'page_count' => ceil($pagination->getTotalItemCount()/$pagination->getItemNumberPerPage()),
            'items_per_page' => $pagination->getItemNumberPerPage(),
            'total_item_count' => $pagination->getTotalItemCount()
        ];

        $data = json_encode($result);
        return new Response($data, 200, [
            'Content-Type' => 'application/json'
        ]);
    }
}