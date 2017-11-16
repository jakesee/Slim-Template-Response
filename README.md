# Slim-Template-Response
A [Slim Framework](https://github.com/slimphp/Slim) add-on that allows deferred parsing and rendering of template so that template data can be examined or modified.

## Install
    composer require jakesee/slim-template-response 1.0.0

## Detailed Explanation
Due to the way [Slim Framework](https://github.com/slimphp/Slim) is designed, the templating engines suggested by Slim Framework all write directly to the ResponseInterface stream directly in the controller, as such, there is no way to examine the template data variables or modify them after the controller exits, for example, during the outgoing middleware pathway. For this reason, this Slim-Template-Response adds a simple way to hold the template variables until the very end before finally writing the data to the stream.

Without modifying how Slim works, we can use the following to defer the writing:

    $app->respond($app->run(true)->render());

instead of 

    $app->run();

### Middleware
As a result, you can modify the template variables in any middleware after the controller exits:

	class MyMiddleware
	{
		function __invoke($request, $response, $next)
		{
			// incoming loop
			...

			// call the next middleware
			$response = $next($request, $response);

			// controller is called eventually and returns:
			// return $response->withTemplate(...);

			// outgoing loop, modify template data
			$response->getTemplate()->data['total'] = 50;

			return $response;
		}
	}
  
### Unit Testing
In unit testing you can now also test the results directly by examining the data variables

	function testDisplayTotal()
	{
		// setup ...

		// Run Slim App
		$response = $app($request, $response);

		// Get the template data
		$data = $response->getTemplate()->data;

		$this->assertEquals(50, $data['total']);
	}

## Usage
This addon consists of a Response class (which is extended from the Slim Response class) and a Template class. Simply inject the Response class into the dependency container so that Slim App will use our extended Response class.

	$app = new \Slim\App(); // default Slim Response is initialized by this constructor
	$container = $app->getContainer();

	// We just replace the Response object by injecting our extended Response into the container
	$container['response'] = function($container) {
	
		// This is how Slim sets up the default Response object in vendor/slim/slim/Slim/DefaultServicesProvide.php.
		// We simply replicate it here as closely as possible.
		$headers = new \Slim\Http\Headers(['Content-Type' => 'text/html; charset=UTF-8']);
		$template = new \Braincase\Slim\Template($container, __DIR__ . '/../resources/views/');
		$response = new \Braincase\Slim\Response($template, 200, $headers);

		return $response->withProtocolVersion($container->get('settings')['httpVersion']);
	};

	// create a route
	$app->get('/test', function($request, $response) {
		$response->withTemplate('default.php', [
			'firstName' => 'Jake',
			'lastName' => 'See'
		]);
	});
	
The Template class uses plain PHP for templating and does not have advance features like Twig or Blade, but it is possible to use your own Template class as long as your Template class implements the TemplateInterface.

### Compatibility
This works with Slim Framework 3 at this time of writing. There is a Slim Framework 4 upcoming and I have not tested against the newer versions.
