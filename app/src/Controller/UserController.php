<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use JsonException;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @OA\Tag(name="User")
 * @OA\Response(
 *     response="401",
 *     ref="#/components/responses/Unauthorized",
 * )
 * @OA\Response(
 *     response="500",
 *     ref="#/components/responses/GeneralError",
 * )
 */
#[Route("users")]
class UserController
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
     *     operationId="user:get",
     *     description="Get user by id",
     *     summary="Returns user by id #user:get",
     *     @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          schema=@OA\Schema(type="integer"),
     *          description="User id",
     *     ),
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
    #[Route('/{id}', name: "user:get", requirements: ["id" => "\d+"], methods: ["GET"])]
    public function getOne(
        User $user
    ): JsonResponse {
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
    #[Route('', name: "user:create", methods: ["POST"])]
    public function create(): JsonResponse
    {
        $user = new User();
        $user->setAge(random_int(18, 70));
        $user->setgender(random_int(0, 1));
        $user->setEmail(uniqid('', true) . '@test.com');
        $user->setUsername($this->generateRandomString());
        $user->setPlainPassword($this->generateRandomString(6));

        $this->userRepository->create($user);

        return new JsonResponse(
            [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'password' => $user->getPlainPassword(),
                'age' => $user->getAge(),
                'gender' => $user->getGender(),
            ],
            JsonResponse::HTTP_CREATED,
        );
    }

    private function generateRandomString($length = 10): string
    {
        return substr(
            str_shuffle(
                str_repeat(
                    $x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
                    (int) ceil($length / strlen($x))
                )
            ),
            1,
            $length
        );
    }
}
