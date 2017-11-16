<?php

namespace Braincase\Slim\Tests;

use Braincase\Slim\Response;
use Braincase\Slim\Template;
use PHPUnit\Framework\TestCase;
use \Slim\Http\Environment;

class TemplateTest extends TestCase
{
	public function setup()
	{
		$app = new \Slim\App();
		$container = $app->getContainer();

		// This is how Slim sets up the default Response object,
		// we just replicate the set up using our custom Response object
		// and inject it into the container
		$container['response'] = function($container) {
			$headers = new \Slim\Http\Headers(['Content-Type' => 'text/html; charset=UTF-8']);
			$template = new Template($container, __DIR__ . '/../resources/views/');
			$response = new Response($template, 200, $headers);

			return $response->withProtocolVersion($container->get('settings')['httpVersion']);
		};

		// create a route
		$app->get('/test', function($request, $response) {
			$response->withTemplate('default.php', [
				'firstName' => 'Jake',
				'lastName' => 'See'
			]);
		});

        // create mock environment for app to run within
		$container['environment'] = Environment::mock([
            'SCRIPT_NAME' => '/index.php',
            'REQUEST_URI' => '/test',
            'REQUEST_METHOD' => 'GET',
        ]);

        $this->app = $app;
	}

	public function testTemplateHelpers()
	{
		$response = $this->app->run(true);

		$template = $response->getTemplate();

		$this->assertEquals('http://localhost/resources/img/default.png', $template->src('resources/img/default.png'));
	}
}