<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use JsonException;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @OA\Tag(name="Profiles")
 * @OA\Response(
 *     response="401",
 *     ref="#/components/responses/Unauthorized",
 * )
 * @OA\Response(
 *     response="500",
 *     ref="#/components/responses/GeneralError",
 * )
 */
#[Route("profiles")]
class ProfileController
{
    private UserRepository $userRepository;
    private SerializerInterface $serializer;

    public function __construct(
        UserRepository $userRepository,
        SerializerInterface $serializer,
    ) {
        $this->userRepository = $userRepository;
        $this->serializer = $serializer;
    }

    /**
     * @OA\Get(
     *     description="Get random profile",
     *     summary="Returns random user profile",
     *     @OA\Response(
     *          response="200",
     *          description="Success",
     *          @OA\JsonContent(
     *              ref=@Model(type=User::class, groups={"user_read"})
     *          ),
     *     ),
     *     @OA\Response(
     *          response="404",
     *          ref="#/components/responses/NotFound",
     *     )
     * )
     */
    #[Route('', name: "profile:rand", methods: ["GET"])]
    public function randomProfile(): JsonResponse {
        $user = $this->userRepository->getRandomProfile();

        if ($user === null) {
            throw new NotFoundHttpException();
        }

        return new JsonResponse(
            $this->serializer->serialize($user, 'json', ['groups' => 'user_read']),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }

    /**
     * @OA\Post(
     *     operationId="user:create",
     *     description="Create a new user",
     *     summary="Create a new user",
     *     @OA\Response(
     *          response="201",
     *          description="Created",
     *          @OA\Header(
     *              header="Location",
     *              @OA\Schema(type="string"),
     *              description="Location for new entity /users/{id}",
     *          ),
     *     ),
     * )
     * @throws JsonException
     */
    #[Route('/swipe', name: "profile:swipe", methods: ["POST"])]
    public function swipe(Request $request): JsonResponse {



        return new JsonResponse(
            $this->serializer->serialize($user, 'json', ['groups' => 'user_read']),
            JsonResponse::HTTP_OK,
            [],
            true
        );
    }
}
