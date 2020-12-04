<?php

namespace App\Controller\v1;

use App\Manager\UserManager;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Security;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/register", methods={"POST"})
     *
     * @OA\Response(response=200, description="Potwierdzenie założenia konta.")
     * @OA\Response(response=400, description="Błędne dane wejściowe.")
     * @OA\RequestBody(request="body", description="Dane w formacie JSON.", required=true,
     *     @OA\JsonContent(
     *          @OA\Property(property="email", type="string"),
     *          @OA\Property(property="firstname", type="string"),
     *          @OA\Property(property="lastname", type="string"),
     *          @OA\Property(property="phone", type="string"),
     *          @OA\Property(property="birthday", type="string"),
     *          @OA\Property(property="currencies", type="array",
     *              @OA\Items(
     *                  required={"id", "min", "max"},
     *                  @OA\Property(property="id", type="integer"),
     *                  @OA\Property(property="min", type="number", format="float"),
     *                  @OA\Property(property="max", type="number", format="float")
     *              )
     *          )
     *     )
     * )
     *
     * @Security(name="Bearer")
     */
    public function register(
        Request $request,
        ValidatorInterface $validator,
        UserManager $manager
    ): Response {
        $input = json_decode($request->getContent(), true);

        $user = $manager->create((array) $input);

        if (count($errors = $validator->validate($user))) {
            return $this->json($errors, Response::HTTP_BAD_REQUEST);
        }

        $result = $manager->register($user);

        if (!$result) {
            return $this->json([], Response::HTTP_SERVICE_UNAVAILABLE);
        }

        return $this->json($user, Response::HTTP_OK, [], ['groups' => ['register']]);
    }

    /**
     * @Route("/confirm/{token}", methods={"GET"})
     *
     * @OA\Response(response=200, description="Potwierdzenie rejestracji.")
     * @OA\Response(response=400, description="Błędne dane wejściowe.")
     * @OA\Parameter(name="token", in="path", description="Token użytkownika.", @OA\Schema(type="string"))
     *
     * @Security(name="Bearer")
     */
    public function confirm($token, UserRepository $repository, UserManager $manager): Response
    {
        $user = $repository->getForConfirm($token);
        if (!$user) {
            return $this->json([], Response::HTTP_BAD_REQUEST);
        }

        $manager->confirm($user);

        return $this->json([], Response::HTTP_OK);
    }
}
