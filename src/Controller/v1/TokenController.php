<?php

namespace App\Controller\v1;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;

class TokenController extends AbstractController
{
    /**
     * @Route("/login_check", methods={"POST"})
     *
     * @OA\RequestBody(request="body", description="Dane w formacie JSON.", required=true,
     *     @OA\JsonContent(
     *          @OA\Property(property="username", type="string"),
     *          @OA\Property(property="password", type="string")
     *     )
     * )
     */
    public function login(): Response
    {
        throw new \Exception();
    }
}
