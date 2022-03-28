<?php

namespace App\Application\UI\Http\Controller;

use App\Application\UI\Http\Command\UpdateEvent\UpdateEventCommand;
use App\Application\UI\Http\Exception\NotFoundEntityException;
use App\Application\UI\Http\Exception\ServiceUnavailableException;
use App\Application\UI\Http\Request\UpdateEventRequest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractBusController
{
    /**
     * @Route(path="/api/event/{id}/update", name="api_commit_update", methods={"PUT"})
     */
    public function update(UpdateEventRequest $request, int $id): Response
    {
        try {
            $command = new UpdateEventCommand($id, $request->getComment());
            $this->handle($command);

            return new Response(null, Response::HTTP_NO_CONTENT);
        } catch (ServiceUnavailableException $exception) {
            return new Response(null, Response::HTTP_SERVICE_UNAVAILABLE);
        } catch (NotFoundEntityException $exception) {
            return new JsonResponse(
                ['message' => $exception->getMessage()],
                Response::HTTP_NOT_FOUND
            );
        }
    }
}
