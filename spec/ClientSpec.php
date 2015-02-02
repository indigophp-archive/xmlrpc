<?php

namespace spec\Indigo\Xmlrpc;

use Ivory\HttpAdapter\HttpAdapterInterface;
use Psr\Http\Message\IncomingResponseInterface as Response;
use Psr\Http\Message\StreamableInterface as Stream;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ClientSpec extends ObjectBehavior
{
    function let(HttpAdapterInterface $httpAdapter)
    {
        $this->beConstructedWith('xml_rpc_test', $httpAdapter);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Indigo\Xmlrpc\Client');
        $this->shouldImplement('fXmlRpc\CallClientInterface');
    }

    function it_should_allow_to_call(HttpAdapterInterface $httpAdapter, Response $response, Stream $body)
    {
        $body->__toString()->willReturn('<?xml version=\'1.0\'?><methodResponse><params><param><value><string>value</string></value></param></params></methodResponse>');
        $response->getBody()->willReturn($body);
        $response->getStatusCode()->willReturn(200);
        $httpAdapter->post('xml_rpc_test', Argument::type('array'), Argument::type('string'))->willReturn($response);

        $this->call('system.echo')->shouldReturn('value');
    }

    function it_should_throw_an_exception_when_the_method_name_is_not_string()
    {
        $this->shouldThrow('fXmlRpc\Exception\InvalidArgumentException')->duringCall(null);
    }

    function it_should_throw_an_exception_when_response_is_fault(HttpAdapterInterface $httpAdapter, Response $response, Stream $body)
    {
        $body->__toString()->willReturn('<?xml version=\'1.0\'?><methodResponse><fault><value><struct><member><name>faultCode</name><value><int>1</int></value></member><member><name>faultString</name><value><string>INVALID</string></value></member></struct></value></fault></methodResponse>');
        $response->getBody()->willReturn($body);
        $response->getStatusCode()->willReturn(200);
        $httpAdapter->post('xml_rpc_test', Argument::type('array'), Argument::type('string'))->willReturn($response);

        $this->shouldThrow('fXmlRpc\Exception\ResponseException')->duringCall('system.invalid');
    }

    function it_should_throw_an_exception_when_http_response_is_not_ok(HttpAdapterInterface $httpAdapter, Response $response)
    {
        $response->getStatusCode()->willReturn(404);
        $response->getReasonPhrase()->willReturn('Not Found');
        $httpAdapter->post('xml_rpc_test', Argument::type('array'), Argument::type('string'))->willReturn($response);

        $this->shouldThrow('fXmlRpc\Exception\HttpException')->duringCall('system.invalid');
    }
}
