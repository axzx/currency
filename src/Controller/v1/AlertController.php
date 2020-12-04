<?php

namespace App\Controller\v1;

use App\Repository\UserCurrencyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Annotations as OA;
use Nelmio\ApiDocBundle\Annotation\Security;

/**
 * @Route("/alert")
 */
class AlertController extends AbstractController
{
    /**
     * @Route("/disable/{uuid}/{code}", methods={"GET"})
     *
     * @OA\Response(response=200, description="Potwierdzenie usunięcia.")
     * @OA\Response(response=400, description="Błędne dane wejściowe.")
     * @OA\Parameter(name="uuid", in="path", description="Publiczny identyfikator użytkownika.", @OA\Schema(type="string"))
     * @OA\Parameter(name="code", in="path", description="Kod waluty.", @OA\Schema(type="string"))
     *
     * @Security(name="Bearer")
     */
    public function disable(string $uuid, string $code, UserCurrencyRepository $repository): Response
    {
        $userCurrency = $repository->getForDisableAlert($uuid, $code);
        if (!$userCurrency) {
            return $this->json([], Response::HTTP_BAD_REQUEST);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($userCurrency);
        $em->flush();

        return $this->json([], Response::HTTP_OK);
    }
}
