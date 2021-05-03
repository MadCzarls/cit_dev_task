<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\Weather\CountryCity;
use App\Exception\TemperatureNotCalculatedException;
use App\Form\Weather\CountryCityType;
use App\Weather\TemperatureHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(
        Request $request,
        TemperatureHandler $temperatureHandler
    ): Response {
        $temperature = null;
        $countryCityDTO = new CountryCity();
        $form = $this->createForm(CountryCityType::class, $countryCityDTO);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $temperature = $temperatureHandler->getTemperature($form->getData());
            } catch (TemperatureNotCalculatedException $exception) {
                $this->addFlash('error', 'Temperature could not be calculated, please contact system administrator');
            }

            //@TODO db persisiting
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
