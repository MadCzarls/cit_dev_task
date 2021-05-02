<?php

declare(strict_types=1);

namespace App\RequestApi\Builder;

use App\Enum\RequestHttpMethodEnum;
use App\Exception\RequestParameterException;
use App\RequestApi\Http\Header;
use App\RequestApi\Http\Request;
use App\RequestApi\Http\RequestInterface;

use function filter_var;
use function sprintf;

use const FILTER_VALIDATE_URL;

class RequestBuilder implements RequestBuilderInterface
{
    protected ?RequestHttpMethodEnum $method = null;
    /**@var Header[] */
    protected array $headers = [];
    protected ?string $url = null;
    protected ?string $body = null;

    public function setHttpMethod(RequestHttpMethodEnum $method): self
    {
        $this->method = $method;

        return $this;
    }

    public function addHeader(string $name, string $value): self
    {
        $header = new Header($name, $value);
        $this->headers[] = $header;

        return $this;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    // @TODO remove if unnecesary
//    public function setBody(?string $body): self
//    {
//        $this->body = $body;
//
//        return $this;
//    }

    protected function validate(): void
    {
        if (empty($this->getHttpMethod())) {
            throw new RequestParameterException('Missing request parameter: httpMethod');
        }
// @TODO remove if unnecesary
//        if ((string) $this->getHttpMethod() === RequestHttpMethodEnum::GET && ! empty($this->getBody())) {
//            throw new RequestParameterException('Body cannot be set for request with method GET');
//        }

        if (empty($this->getUrl())) {
            throw new RequestParameterException('Missing request parameter: url');
        }

        if (!filter_var($this->getUrl(), FILTER_VALIDATE_URL)) {
            throw new RequestParameterException(sprintf("'%s' is not a valid URL", $this->getUrl()));
        }
    }

    protected function getHttpMethod(): ?RequestHttpMethodEnum
    {
        return $this->method;
    }

    /**
     * @return Header[]
     */
    protected function getHeaders(): array
    {
        return $this->headers;
    }
// @TODO remove if unnecesary
//    protected function getBody(): ?string
//    {
//        return $this->body;
//    }

    protected function getUrl(): ?string
    {
        return $this->url;
    }

    public function build(): RequestInterface
    {
        $this->validate();

        return new Request($this->getHttpMethod(), $this->getUrl(), $this->getHeaders());
    }
}
