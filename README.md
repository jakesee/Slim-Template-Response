# Slim-Template-Response
A Slim Framework add-on that allows deferred parsing and rendering of template so that template data can be examined or modified.

## Install
    composer require jakesee/slim-template-response 1.0.0

## Detailed Explanation
Due to the way Slim Framework is designed, the templating engines suggested by Slim Framework all write directly to the ResponseInterface stream directly in the controller, as such, there is no way to examine the template data variables or modify them after the controller exits, for example, during the outgoing middleware pathway. For this reason, this Slim-Template-Response adds a simple way to hold the template variables until the very end before finally writing the data to the stream.

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

	function testGetUserProfile()
	{
		// setup ...

		// Run Slim App
		$response = $app($request, $response);

		// Get the template data
		$data = $response->getTemplate()->data;

		$this->assertEquals(50, $data['total']);
	}
