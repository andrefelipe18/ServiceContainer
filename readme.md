# What is this project?
This was a project to build a Service Container, which served as a learning experience for future use in my framework (YuiPHP)[https://github.com/yuiphp].

## What is a Service Container?
Imagine the Service Container as a large box, where you can store (bind) objects, and later when you need them, you can retrieve (resolve) them from inside the box.

A basic example:

```php

class HomeController
{	
	public function __construct(
		private UserRepositoryInterface $userRepository,
		private Auth $auth
	){}

	public function index(
		UserRepositoryInterface $userRepository,
		NewsLetter $newsLetter
	)
	{
		dd(
		$userRepository->find(1),
		 'NewsLetter Resolving in __construct params' . $this->auth->auth(), 
		 'NewsLetter Resolving in method params' . $newsLetter->send()
		);
	}
}
```

In the HomeController class, I have a method index, which receives two parameters, UserRepositoryInterface and NewsLetter, and in the constructor I have two parameters, UserRepositoryInterface and Auth. Imagine the work it would be to manually instantiate these classes and pass them as parameters to the index method and to the constructor of the HomeController class; this is where the Service Container comes in handy. It does all this work for you.

Here in Router.php, I have a method called makeInstance, which is responsible for instantiating the controller and calling the method, and it does this using the Service Container.

```php

class Router
{
	public function __construct(
		private Container $container
	){}

	private string $controller;
	private string $method;
	public function create(array $routes)
	{
		foreach ($routes as $uri => $route) {
			if($uri === $_SERVER['REQUEST_URI']) { //If the URI matches the current request URI
				$this->controller = $route[0];
				$this->method = $route[1];
			}
		}
		$this->makeInstance();
	}

	private function makeInstance()
	{
		if(class_exists($this->controller)) {
			$controller = $this->container->get($this->controller); //Get the controller from the container

			$method = new ReflectionMethod($controller, $this->method); //Get the method from the controller

			if(method_exists($controller, $this->method)) {
				return $controller->{$this->method}(
					...$this->container->resolveContainer->parameters($method, $this->container) //Resolve the parameters for the method
				);
			}
		}
	}
}
```

## But how does the Service Container do this?

The Service Container expects you to pass a class with its namespace (i.e., ClassName::class) and it will try to instantiate this class and resolve its dependencies. That is, if the class you passed in the constructor parameter has dependencies, it will try to instantiate these dependencies, and so on, until all dependencies are resolved.

```php

class Container implements ContainerInterface
{
	private array $bindings = [];

	public function __construct(
		public readonly ResolveContainer $resolveContainer
	) {}

	public function bind(
		string $key, //The key to bind
		mixed $value //The value to return when the key is resolved
	) {
		$this->bindings[$key] = $value;
	}

	public function get(
		string $key
	): mixed {
		if (isset($this->bindings[$key])) {
			$bind = $this->bindings[$key];
			if ($bind instanceof Closure) {
				return $bind();
			}

			return $bind;
		}

		if (class_exists($key)) {
			return $this->resolveContainer->instance($key, $this);
		}

		return null;
	}
}

class ResolveContainer
{
	public function parameters($method, Container $container)
	{
		return array_map( // Map the parameters of the method
			fn ($param) => $container->get($param->getType()->getName()), // Get the type of the parameter and resolve it from the container
			$method->getParameters()
		);		
	}

	public function instance(string $key, Container $container)
	{
		$reflection = new ReflectionClass($key); //The reflection works as a mirror of the class

		$constructor = $reflection->getConstructor(); // Get the constructor of the class
		if (!$constructor) { // If the class does not have a constructor return a new instance of the class
			return new $key;
		}

		return $reflection->newInstanceArgs( // Return a new instance of the class with its dependencies resolved
			$this->parameters($constructor, $container)
		);
	}
}
```

## How do I use the Service Container?

```php

require_once 'vendor/autoload.php';

use Core\Container;

$container = new Container(new Core\ResolveContainer);

$container->bind(UserRepositoryInterface::class, fn() => new UserRepository);

$container->bind(Auth::class, fn() => new Auth);

$container->bind('Key', 'Value');

$router = new Router($container);
```

## Simplifying

The Service Container is a great tool for those who want to decouple the dependencies of their classes, and keep the code cleaner and more organized. Best of all, you don't need to manually instantiate classes and pass them as parameters to methods; the Service Container does all this work for you. Some example cases where you can use the Service Container:

- Controllers
- Repositories
- Models
- Services
- Helpers

And much more. The Service Container is a powerful tool that can be used in various scenarios, and best of all, you can customize it however you want and add new functionalities. For example, you can add a method to resolve dependencies of classes that are not in the constructor but in methods, or even add a method to resolve dependencies of classes that are in an array. The sky's the limit.

It is used in Laravel; that's where I had my first contact with it. Since then, I have been studying and learning more about it and how it can be useful in various scenarios and how it can make our lives as developers easier.

I hope this project has been helpful to you and that you can learn something new from it and maybe even implement it in your project to see how useful it can be.

As a tip, consider using PHP-DI, which is a powerful dependency injection container that can be used in various scenarios and can make our lives as developers easier. It can be a great alternative to the Service Container that I showed here.