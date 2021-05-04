<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\Weather\CountryCity;
use App\Exception\TemperatureNotCalculatedException;
use App\Form\Weather\CountryCityType;
use App\Repository\TemperatureResultRepository;
use App\Weather\TemperatureHandler;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(
        Request $request,
        LoggerInterface $logger,
        TemperatureHandler $temperatureHandler,
        TemperatureResultRepository $repository
    ): Response {
        $temperature = null;
        $countryCityDTO = new CountryCity();
        $form = $this->createForm(CountryCityType::class, $countryCityDTO);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $result = $temperatureHandler->getTemperature($form->getData());
            } catch (TemperatureNotCalculatedException $exception) {
                $this->addFlash(
                    'error',
                    'Temperature could not be calculated, please contact system administrator'
                );

                return $this->redirectToRoute('home');
            }

            try {
                $repository->insert(
                    $result->getCountry(),
                    $result->getCity(),
                    $result->getTemperature(),
                    $result->isFromCache()
                );
            } catch (ORMException | OptimisticLockException $exception) {
                $logger->error($exception->getMessage());
            }

            $temperature = $result->getTemperature();
        }

        return $this->render(
            'home/index.html.twig',
            [
                'form' => $form->createView(),
                'temperature' => $temperature,
            ]
        );
    }
}
