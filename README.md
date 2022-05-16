# WebmunkeezADRBundle

This bundle unleashes the __Action-Domain-Responder__ pattern on Symfony applications.

## Installation

Use Composer to install this bundle:

```console
$ composer require webmunkeez/adr-bundle
```

Add the bundle in your application kernel:

```php
// config/bundles.php

return [
    // ...
    Webmunkeez\ADRBundle\WebmunkeezADRBundle::class => ['all' => true],
    // ...
];
```

## Usage

### Actions

An __Action__ is just an invokable class that has to implement `\Webmunkeez\ADRBundle\Action\ActionInterface`:

```php
final class StoryDetailAction implements \Webmunkeez\ADRBundle\Action\ActionInterface
{
    public function __invoke(): Response
    {
        return $this->render($data);
    }
    
    public function render(array $data = []): Response
    {
        return new Response(...);
    }
}
```

But, it can be a more classic __Controller__ that implements the same interface:

```php
final class StoryController implements \Webmunkeez\ADRBundle\Action\ActionInterface
{
    public function detail(): Response
    {
        return $this->render($data);
    }
    
    public function render(array $data = []): Response
    {
        return new Response(...);
    }
}
```

(Each service that implements `ActionInterface` is automatically tagged `controller.service_arguments`)

### Responders

__Responders__ are services which take data and return it in a __Response__.  
It can be a response containing HTML or a JsonResponse, or whatever you want, as far as it is a `Symfony\Component\HttpFoundation\Response` instance.

In this bundle, there is a responder manager `\Webmunkeez\ADRBundle\Response\Responder` that you can inject into your actions (or controllers).

This responder manager takes all responders of your application (it uses a compiler pass to get all services tagged `webmunkeez_adr.responder` sorted by priority) and find the right one to render the response.

```php
final class StoryDetailAction implements \Webmunkeez\ADRBundle\Action\ActionInterface
{
    private \Webmunkeez\ADRBundle\Response\Responder $responder;
    
    public function __construct(\Webmunkeez\ADRBundle\Response\Responder $responder)
    {
        $this->responder = $responder
    }
    
    public function __invoke(): Response
    {
        return $this->render($data);
    }
    
    public function render(array $data = []): Response
    {
        return $this->responder->render($data);
    }
}
```

You can use `\Webmunkeez\ADRBundle\Response\ResponderAwareInterface` and `\Webmunkeez\ADRBundle\Response\ResponderAwareTrait` to automatically inject Responder:

```php
final class StoryDetailAction implements \Webmunkeez\ADRBundle\Action\ActionInterface, \Webmunkeez\ADRBundle\Response\ResponderAwareInterface
{
    use \Webmunkeez\ADRBundle\Response\ResponderAwareTrait;
    
    public function __invoke(): Response
    {
        return $this->render($data);
    }
    
    public function render(array $data = []): Response
    {
        return $this->responder->render($data);
    }
}
```

And you can use `\Webmunkeez\ADRBundle\Action\ActionTrait` to clean code:

```php
final class StoryDetailAction implements \Webmunkeez\ADRBundle\Action\ActionInterface, \Webmunkeez\ADRBundle\Response\ResponderAwareInterface
{
    use \Webmunkeez\ADRBundle\Response\ResponderAwareTrait;
    use \Webmunkeez\ADRBundle\Action\ActionTrait;
    
    public function __invoke(): Response
    {
        return $this->render($data);
    }
}
```

Or directly extend `\Webmunkeez\ADRBundle\Action\AbstractAction`:

```php
final class StoryDetailAction extends \Webmunkeez\ADRBundle\Action\AbstractAction
{
    public function __invoke(): Response
    {
        return $this->render($data);
    }
}
```

Responders are classes that implement `\Webmunkeez\ADRBundle\Response\ResponderInterface` (and so, they are automatically tagged `webmunkeez_adr.responder`):

```php
final class XmlResponder implements \Webmunkeez\ADRBundle\Response\ResponderInterface
{
    private RequestStack $requestStack;
    private SerializerInterface $serializer;

    public function __construct(RequestStack $requestStack, SerializerInterface $serializer)
    {
        $this->requestStack = $requestStack;
        $this->serializer = $serializer;
    }

    public function supports(): bool
    {
        return 'xml' === $this->requestStack->getCurrentRequest()->getPreferredFormat();
    }

    public function render(array $data = []): Response
    {
        $xml = $this->serializer->serialize($data, 'xml');

        $response = new Response($xml);
        $response->headers->set('Content-Type', 'text/xml');

        return $response;
    }
}
```

As you can see, there are two methods: `supports` that defines conditions to "activate" the responder and `render` to make the response.  

#### Core responders

There are two core responders provided:

##### HtmlResponder

`\Webmunkeez\ADRBundle\Response\HtmlResponder` that uses __Twig__ for render html with a twig template. To indicate template, you have to use `\Webmunkeez\ADRBundle\Attribute\Template`:

```php
use Webmunkeez\ADRBundle\Attribute\Template;

#[Template('story/detail.html.twig')]
final class StoryDetailAction implements \Webmunkeez\ADRBundle\Action\ActionInterface
{
    ...
}
```

This responder is active if the request contains `HTTP_ACCEPT text/html` header (warning: a twig template is needed for this responder, otherwise it will throw an `\Webmunkeez\ADRBundle\Exception\RenderException` exception).  
It has a `priority: -20`.

##### JsonResponder

`\Webmunkeez\ADRBundle\Response\JsonResponder` that uses __Serializer__ for render json (you can indicate serialization context with `\Webmunkeez\ADRBundle\Attribute\SerializationContext`):

```php
use Webmunkeez\ADRBundle\Attribute\SerializationContext;

#[SerializationContext(['groups' => 'group_one'])]
final class StoryDetailAction implements \Webmunkeez\ADRBundle\Action\ActionInterface
{
    ...
}
```

This responder is active if the request contains `HTTP_ACCEPT application/json` header.  
It has a `priority: -10`.

#### Custom responders

You can write your own reponders like in my previous `XmlResponder` example, by implementing `\Webmunkeez\ADRBundle\Response\ResponderInterface`.

Services implementing this interface are automatically tagged `webmunkeez_adr.responder` with `priority: 0`, and you can change it (in your `service.yaml` or by static `getDefaultPriority` method ; see [https://symfony.com/doc/current/service_container/tags.html#tagged-services-with-priority](https://symfony.com/doc/current/service_container/tags.html#tagged-services-with-priority)).

You can define "generic" responders like html, json, xml and so on. But you can also define more specifics, by checking `$request->attributes->get('_controller')` to make a responder only for a specific action:

```php
final class CustomResponder implements \Webmunkeez\ADRBundle\Response\ResponderInterface
{
    private RequestStack $requestStack;
    private Environment $twig;

    public function __construct(RequestStack $requestStack, Environment $twig)
    {
        $this->requestStack = $requestStack;
        $this->twig = $twig;
    }

    public function supports(): bool
    {
        $controller = $this->requestStack->getCurrentRequest()->attributes->get('_controller');
        $actionClass = false !== strpos($controller, '::') ? substr($controller, 0, strpos($controller, '::')) : $controller;

        return CustomResponderAction::class === $actionClass;
    }

    public function render(array $data = []): Response
    {
        $data = array_merge($data, ['customResponder' => true]);

        $html = $this->twig->render($this->requestStack->getCurrentRequest()->attributes->get('_template_path'), $data);

        return new Response($html);
    }
}
```

### Param Converter

ParamConverters are a way to populate objects and inject them as controller method arguments. The `RequestDataParamConverter` converter comes with ADRBundle and makes it possible to deserialize the request data (route params, query params, json request body) into an object.

```php
#[Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter\ParamConverter('story', converter: Webmunkeez\ADRBundle\Request\ParamConverter\RequestDataParamConverter::CONVERTER)]
final class StoryDetailAction implements \Webmunkeez\ADRBundle\Action\ActionInterface
{
    public function __invoke(Story $story): Response
    {
        ...
    }
}
```