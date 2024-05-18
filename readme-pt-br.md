# O que é esse projeto?

Esse foi um projeto para construir um Service Container, que serviu de aprendizado para um uso futuro no meu framework (YuiPHP)[https://github.com/yuiphp]

# O que é um Service Container?

Imagine o Service Container como uma grande caixa, onde você pode guardar(bind) objetos, e futuramente quando você precisar deles, você pode pegar(resolve) eles de dentro da caixa.

Um exemplo básico

```php HomeController.php
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

Na classe HomeController, eu tenho um método index, que recebe dois parâmetros, UserRepositoryInterface e NewsLetter, e no construtor eu tenho dois parâmetros, UserRepositoryInterface e Auth, imagine o trabalho que seria instanciar essas classes manualmente, e passar como parâmetros para o método index, e para o construtor da classe HomeController, é aí que entra o Service Container, ele faz todo esse trabalho para você.

Aqui no Router.php eu tenho um método chamado makeInstance, que é responsável por instanciar o controller e chamar o método, e ele faz isso usando o Service Container.

```php Router.php
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

## Mas como o Service Container faz isso?

O ServiceContainer espera que você passe uma classe com seu namespace (Ou seja ClassName::class) e ele vai tentar instanciar essa classe, e resolver as dependências dela, ou seja, se a classe que você passou no parâmetro do construtor, tiver dependências, ele vai tentar instanciar essas dependências, e assim por diante, até que todas as dependências sejam resolvidas.

```php Container.php

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

```

```php ResolveContainer.php
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
		$reflaction = new ReflectionClass($key); //The reflection works as a mirror of the class

		$constructor = $reflaction->getConstructor(); // Get the constructor of the class
		if (!$constructor) { // If the class does not have a constructor return a new instance of the class
			return new $key;
		}

		return $reflaction->newInstanceArgs( // Return a new instance of the class with its dependencies resolved
			$this->parameters($constructor, $container)
		);
	}
}
```

## Como eu uso o Service Container?

```php index.php
require_once 'vendor/autoload.php';

use Core\Container;

$container = new Container(new Core\ResolveContainer);

$container->bind(UserRepositoryInterface::class, fn() => new UserRepository);

$container->bind(Auth::class, fn() => new Auth);

$container->bind('Chave', 'Valor')

$router = new Router($container);

```

## Simplificando

O Service Container é uma ferramenta ótima, para quem quer desacoplar as dependências de suas classes, e deixar o código mais limpo e organizado, e o melhor de tudo, é que você não precisa instanciar manualmente as classes, e passar como parâmetros para os métodos, o Service Container faz todo esse trabalho para você, alguns cases de examplos que você pode usar o Service Container:

- Controllers
- Repositories
- Models
- Services
- Helpers

E muito mais, o Service Container é uma ferramenta poderosa, e que pode ser usada em diversos casos, e o melhor de tudo, é que você pode personalizar ele da forma que você quiser, e adicionar novas funcionalidades, como por exemplo, adicionar um método para resolver dependências de classes que não estão no construtor, mas sim em métodos, ou até mesmo adicionar um método para resolver dependências de classes que estão em um array, enfim o céu é o limite.

Ele é utilizado no Laravel, foi lá que eu tive meu primeiro contato com ele, e desde então eu venho estudando e aprendendo mais sobre ele, e como ele pode ser útil em diversos casos, e como ele pode facilitar a nossa vida como desenvolvedores.

Espero que esse projeto tenha sido útil para você, e que você possa aprender algo novo com ele, e quem sabe até mesmo implementar ele em seu projeto, e ver como ele pode ser útil.

Como dica fica o uso do PHP-DI, que é um container de injeção de dependência, que é muito poderoso, e que pode ser usado em diversos casos, e que pode facilitar a nossa vida como desenvolvedores, e que pode ser uma ótima alternativa ao Service Container que eu mostrei aqui. Olhe a branch PHP-DI