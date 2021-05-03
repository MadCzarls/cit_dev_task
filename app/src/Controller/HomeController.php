<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\Weather\CountryCity;
use App\Form\Weather\CountryCityType;
use App\Weather\ApiHandler;
use App\Weather\TemperatureCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(
        Request $request,
        ApiHandler $apiHandler,
        TemperatureCalculator $temperatureCalculator
    ): Response {
        $errors = [];
        $temperature = null;
        $countryCityDTO = new CountryCity();
        $form = $this->createForm(CountryCityType::class, $countryCityDTO);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $apiResults = $apiHandler->getResults($form->getData());

            if (empty($apiResults)) {
                $errors[] = 'No results, please try again later or contact system administrator';
            } else {
                foreach ($apiResults as $result) {
                    $temperatureCalculator->add($result);
                }

                $temperature = $temperatureCalculator->calculate();
            }

            //@TODO CacheHandler, db persisiting,
        }

        return $this->render(
            'home/index.html.twig',
            [
                'form' => $form->createView(),
                'temperature' => $temperature,
                'errors' => $errors,
            ]
        );
    }
}
