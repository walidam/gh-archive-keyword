<?php

namespace App\Application\UI\Http\ArgumentResolver;

use App\Application\UI\Http\Request\RequestInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RequestValueResolver implements ArgumentValueResolverInterface
{
    protected ValidatorInterface $validator;
    protected SerializerInterface $serializer;

    public function __construct(
        SerializerInterface $serializer,
        ValidatorInterface $validator
    ) {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        if (!$argument->getType() ||
            !in_array(RequestInterface::class, class_implements($argument->getType()), true)) {
            return false;
        }

        return true;
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $value = $this->createRequest($request, $argument->getType());
        $this->validateRequest($value);

        yield $value;
    }

    protected function createRequest(Request $request, string $class): RequestInterface
    {
        if (in_array($request->getMethod(), ['POST', 'PUT', 'DELETE', 'PATCH'])) {
            $method = 'deserialize';
            $data = $request->getContent();
        } else {
            $method = 'denormalize';
            $data = array_merge(
                $request->request->all(),
                $request->query->all()
            );
        }

        return $this->serializer->{$method}(
            $data,
            $class,
            'json',
            [
                AbstractNormalizer::ALLOW_EXTRA_ATTRIBUTES => false,
                AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
            ]
        );
    }

    /**
     * @throws BadRequestHttpException
     */
    protected function validateRequest(RequestInterface $request)
    {
        $violations = $this->validator->validate($request);

        // Check asserts.
        if ($violations->count()) {
            throw new BadRequestHttpException((string) $violations->get(0)->getMessage());
        }
    }
}
