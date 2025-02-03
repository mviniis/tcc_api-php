<?php

use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
	->withRouting(
		web: __DIR__ . '/../routes/web.php',
		api: __DIR__ . '/../routes/api.php',
		apiPrefix: 'api',
		commands: __DIR__ . '/../routes/console.php',
		health: '/up',
	)
	->withMiddleware(function (Middleware $middleware) {
		//
	})
	->withExceptions(function (Exceptions $exceptions) {
		// RETORNA JSON QUANDO OS ERROS FOREM EXIBIDOS NA API
		$exceptions->shouldRenderJsonWhen(function(Request $request, Throwable $exception) {
			return $request->is('api/*');
		});
	})->create();
