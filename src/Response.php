<?php
namespace Braincase\Slim;

use Psr\Http\Message\StreamInterface;
use Slim\Http\Response as SlimResponse;
use Slim\Interfaces\Http\HeadersInterface;

class Response extends SlimResponse
{
	protected $template;

	// public function __construct($status = 200, HeadersInterface $headers = null, StreamInterface $body = null)
	public function __construct(TemplateInterface $template, $status = 200, HeadersInterface $headers = null, StreamInterface $body = null)
	{
		parent::__construct($status, $headers, $body);

		$this->template = $template;
	}

	public function withTemplate($templateFile, array $data)
	{
		$this->template->prepare($templateFile, $data);

		return $this;
	}

	public function render()
	{
		$this->getBody()->write($this->template->toString());

		return $this;
	}

	public function getTemplate()
	{
		return $this->template;
	}

	public function getJson()
	{
		return json_decode((string) $this->response->getBody(), true);
	}
}



