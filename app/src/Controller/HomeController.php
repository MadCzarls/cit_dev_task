<?php

declare(strict_types=1);

namespace App\Controller;

use App\DTO\Weather\CountryCity;
use App\Factory\OpenWeatherMap\Factory;
use App\Form\Weather\CountryCityType;
use App\RequestApi\Builder\RequestBuilder;
use App\RequestApi\Client\ClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Throwable;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(Request $request, Factory $openWeatherMapFactory, ClientInterface $client): Response
    {
        $errors = [];
        $countryCityDTO = new CountryCity();
        $form = $this->createForm(CountryCityType::class, $countryCityDTO);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $apiRequestBuilder = new RequestBuilder();

            $director = $openWeatherMapFactory->createRequestHandler();
            $director->setBuilder($apiRequestBuilder);

            try {
                $director->requestForCountryAndCity($form->getData());
                $requestApi = $apiRequestBuilder->build();
            } catch (Throwable $exception) {
                $errors[] = $exception->getMessage();
                //@TODO response error occured, add flash, reload page,
//                return new Response('@TODO');
            }

            try {
                $response = $client->execute($requestApi);
            } catch (Throwable $exception) {
                $errors[] = $exception->getMessage();
                //@TODO response error occured, log it, aggregate it., move to another API
            }

            try {
                $result = $openWeatherMapFactory->createResponseHandler();
            } catch (Throwable $exception) {
                $errors[] = $exception->getMessage();
                //@TODO response error occured, log it, aggregate it., move to another API
            }

            //@TODO another API request, temp calculator (avg), caching, db persisiting,
            // move everything to service, refactor, etc
        }

        return $this->render(
            'home/index.html.twig',
            [
                'form' => $form->createView(),
                'errors' => $errors,
            ]
        );
    }
}
