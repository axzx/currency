<?php

namespace App\Controller\v1;

use App\Repository\CurrencyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Security;

/**
 * @Route("/currency")
 */
class CurrencyController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     *
     * @OA\Response(response=200, description="Lista walut")
     *
     * @Security(name="Bearer")
     */
    public function index(CurrencyRepository $repository): Response
    {
        return $this->json(
            [$repository->findAll()],
            Response::HTTP_OK,
            [],
            ['groups' => ['currency']]
        );
    }
}
