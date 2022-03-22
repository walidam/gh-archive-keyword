<?php

namespace App\Application\Controller;

use App\Application\Query\SearchEvent\SearchEventQuery;
use App\Application\Request\SearchRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractBusController
{
    /**
     * @Route(path="/api/search", name="api_search", methods={"GET"})
     */
    public function search(SearchRequest $request): JsonResponse
    {
        $query = new SearchEventQuery($request->getDate(), $request->getKeyword());
        try {
            $data = $this->ask($query);

            return new JsonResponse($data);
        } catch (\Exception $exception) {
            return new JsonResponse($exception->getMessage());
        }
    }
}
