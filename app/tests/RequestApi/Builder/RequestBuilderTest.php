<?php

declare(strict_types=1);

namespace App\Tests\RequestApi\Builder;

use App\Enum\RequestHttpMethodEnum;
use App\RequestApi\Builder\RequestBuilder;
use App\RequestApi\Builder\RequestBuilderInterface;
use App\RequestApi\Http\Header;
use PHPUnit\Framework\TestCase;

class RequestBuilderTest extends TestCase
{
    /**
     * @param mixed[] $builderParams
     *
     * @dataProvider buildDataProvider
     */
    public function testBuild(
        array $builderParams,
        string $expectedMethod,
        string $expectedUrl,
        Header $expectedHeader
    ): void {
        $builder = new RequestBuilder();

        $this->setBuilderData($builder, $builderParams);

        $request = $builder->build();
        $this->assertEquals($expectedMethod, $request->getHttpMethodEnum()->getValue());
        $this->assertEquals($expectedUrl, $request->getUrl());

        $header = $request->getHeaders()[0];
        $this->assertEquals($expectedHeader->getName(), $header->getName());
        $this->assertEquals($expectedHeader->getValue(), $header->getValue());
    }

    /**
     * @return mixed[]
     */
    public function buildDataProvider(): array
    {
        return [
            'correct request with method GET' => [
                [
                    'httpMethod' => RequestHttpMethodEnum::from(RequestHttpMethodEnum::GET),
                    'url' => 'https://internet.com',
                    'headers' => [
                        [
                            'name' => 'Connection',
                            'value' => 'keep-alive',
                        ],
                    ],
                ],
                'GET',
                'https://internet.com',
                new Header('Connection', 'keep-alive'),
            ],
            'correct request with method POST' => [
                [
                    'httpMethod' => RequestHttpMethodEnum::from(RequestHttpMethodEnum::POST),
                    'url' => 'https://world.web',
                    'headers' => [
                        [
                            'name' => 'Content-Language',
                            'value' => 'en-US',
                        ],
                    ],
                    'body' => 'param1=valueParam1&param2=valueParam2',
                ],
                'POST',
                'https://world.web',
                new Header('Content-Language', 'en-US'),
            ],
        ];
    }

    /**
     * @param mixed[] $builderParams
     *
     * @dataProvider validationThrowsExceptionDataProvider
     */
    public function testValidationThrowsException(array $builderParams, string $exceptionMessage): void
    {
        $builder = new RequestBuilder();

        $this->setBuilderData($builder, $builderParams);

        $this->expectExceptionMessage($exceptionMessage);

        $builder->build();
    }

    /**
     * @return mixed[]
     */
    public function validationThrowsExceptionDataProvider(): array
    {
        return [
            'url not set' => [
                [
                    'httpMethod' => RequestHttpMethodEnum::from(RequestHttpMethodEnum::GET),
                    'headers' => [
                        [
                            'name' => 'Connection',
                            'value' => 'keep-alive',
                        ],
                    ],
                ],
                'Missing request parameter: url',
            ],
            'missing http method' => [
                [
                    'url' => 'https://internet.com',
                    'headers' => [
                        [
                            'name' => 'Connection',
                            'value' => 'keep-alive',
                        ],
                    ],
                ],
                'Missing request parameter: httpMethod',
            ],
            'invalid url' => [
                [
                    'httpMethod' => RequestHttpMethodEnum::from(RequestHttpMethodEnum::POST),
                    'url' => 'invalid url',
                    'headers' => [
                        [
                            'name' => 'Connection',
                            'value' => 'keep-alive',
                        ],
                    ],
                ],
                "'invalid url' is not a valid URL",
            ],
        ];
    }

    /**
     * @param mixed[] $builderParams
     */
    private function setBuilderData(RequestBuilderInterface $builder, array $builderParams): void
    {
        if (isset($builderParams['httpMethod'])) {
            $builder->setHttpMethod($builderParams['httpMethod']);
        }

        if (isset($builderParams['url'])) {
            $builder->setUrl($builderParams['url']);
        }

        if (!isset($builderParams['headers'])) {
            return;
        }

        foreach ($builderParams['headers'] as $headerData) {
            $builder->addHeader($headerData['name'], $headerData['value']);
        }
    }
}
