<?php
namespace Braincase\Slim;

use Psr\Http\Message\StreamInterface;
use Slim\Http\Response as SlimResponse;
use Slim\Interfaces\Http\HeadersInterface;

class Response extends SlimResponse
{
	use TemplateEngineTrait;

	// public function __construct($status = 200, HeadersInterface $headers = null, StreamInterface $body = null)
	public function __construct($container, $templatePath)
	{
		$headers = new \Slim\Http\Headers(['Content-Type' => 'text/html; charset=UTF-8']);
		parent::__construct(200, $headers);

		$this->initTemplateEngine($container, $templatePath);
	}

	public function render()
	{
		$this->getBody()->write($this->template->toString());

		return $this;
	}

	public function getJson()
	{
		return json_decode((string) $this->response->getBody(), true);
	}
}


