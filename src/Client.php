<?php

/*
 * This file is part of the Indigo XML-RPC package.
 *
 * (c) Indigo Development Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Indigo\Xmlrpc;

use fXmlRpc\CallClientInterface;
use fXmlRpc\Parser\ParserInterface;
use fXmlRpc\Parser\XmlReaderParser;
use fXmlRpc\Serializer\SerializerInterface;
use fXmlRpc\Serializer\XmlWriterSerializer;
use fXmlRpc\Exception\HttpException;
use fXmlRpc\Exception\ResponseException;
use fXmlRpc\Exception\InvalidArgumentException;
use Ivory\HttpAdapter\HttpAdapterInterface;
use Ivory\HttpAdapter\HttpAdapterFactory;

class Client implements CallClientInterface
{
    /**
     * @var HttpAdapterInterface
     */
    private $httpAdapter;

    /**
     * @param string               $uri
     * @param HttpAdapterInterface $httpAdapter
     * @param ParserInterface      $parser
     * @param SerializerInterface  $serializer
     */
    public function __construct(
        $uri = null,
        HttpAdapterInterface $httpAdapter = null,
        ParserInterface $parser = null,
        SerializerInterface $serializer = null
    )
    {
        $this->uri = $uri;
        $this->httpAdapter = $httpAdapter ?: HttpAdapterFactory::guess();
        $this->parser = $parser ?: new XmlReaderParser;
        $this->serializer = $serializer ?: new XmlWriterSerializer;
    }

    /**
     * {@inheritdoc}
     * @throws Exception\ResponseException
     */
    public function call($methodName, array $params = [])
    {
        if (!is_string($methodName)) {
            throw InvalidArgumentException::expectedParameter(0, 'string', $methodName);
        }

        // forcing it here
        // custom headers can be created using ivory's message factory
        $headers = [
            'Content-Type' => 'text/xml; charset=UTF-8'
        ];

        $response = $this->httpAdapter->post($this->uri, $headers, $this->serializer->serialize($methodName, $params));

        if ($response->getStatusCode() !== 200) {
            throw HttpException::httpError($response->getReasonPhrase(), $response->getStatusCode());
        }

        $response = $this->parser->parse((string) $response->getBody(), $isFault);

        if ($isFault) {
            throw ResponseException::fault($response);
        }

        return $response;
    }
}
