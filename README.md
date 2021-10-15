# WebmunkeezAdrBundle

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
    Webmunkeez\AdrBundle\WebmunkeezAdrBundle::class => ['all' => true],
    // ...
];
```

## Usage

### Actions

An __Action__ is just an invokable class that has to implement `\Webmunkeez\AdrBundle\Action\ActionInterface`:

```php
final class StoryDetailAction implements \Webmunkeez\AdrBundle\Action\ActionInterface
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
final class StoryController implements \Webmunkeez\AdrBundle\Action\ActionInterface
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

In this bundle, there is a responder manager `\Webmunkeez\AdrBundle\Response\Responder` that you can inject into your actions (or controllers).

This responder manager takes all responders of your application (it uses a compiler pass to get all services tagged `webmunkeez.responder` sorted by priority) and find the right one to render the response.

```php
final class StoryDetailAction implements \Webmunkeez\AdrBundle\Action\ActionInterface
{
    private \Webmunkeez\AdrBundle\Response\Responder $responder;
    
    public function __construct(\Webmunkeez\AdrBundle\Response\Responder $responder)
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

You can use `\Webmunkeez\AdrBundle\Response\ResponderAwareInterface` and `\Webmunkeez\AdrBundle\Response\ResponderAwareTrait` to automatically inject Responder:

```php
final class StoryDetailAction implements \Webmunkeez\AdrBundle\Action\ActionInterface, \Webmunkeez\AdrBundle\Response\ResponderAwareInterface
{
    use \Webmunkeez\AdrBundle\Response\ResponderAwareTrait;
    
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

And you can use `\Webmunkeez\AdrBundle\Action\ActionTrait` to clean code:

```php
final class StoryDetailAction implements \Webmunkeez\AdrBundle\Action\ActionInterface, \Webmunkeez\AdrBundle\Response\ResponderAwareInterface
{
    use \Webmunkeez\AdrBundle\Response\ResponderAwareTrait;
    use \Webmunkeez\AdrBundle\Action\ActionTrait;
    
    public function __invoke(): Response
    {
        return $this->render($data);
    }
}
```

Responders are classes that implement `\Webmunkeez\AdrBundle\Response\ResponderInterface` (and so, they are automatically tagged `webmunkeez.responder`):

```php
final class XmlResponder implements \Webmunkeez\AdrBundle\Response\ResponderInterface
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

`\Webmunkeez\AdrBundle\Response\HtmlResponder` that uses __Twig__ for render html with a twig template. To indicate template, you have to use `\Webmunkeez\AdrBundle\Annotation\Template` (as doctrine annotation or php8 attribute):

```php
// php8 attribute

#[Template('story/detail.html.twig')]
final class StoryDetailAction implements \Webmunkeez\AdrBundle\Action\ActionInterface
{
    ...
}

// doctrine annotation

/**
 * @Template("story/detail.html.twig")
 */
final class StoryDetailAction implements \Webmunkeez\AdrBundle\Action\ActionInterface
{
    ...
}
```

This responder is active if the request contains `HTTP_ACCEPT text/html` header and if there is the Template annotation.  
It has a `priority: -20`.

##### JsonResponder

`\Webmunkeez\AdrBundle\Response\JsonResponder` that uses __Serializer__ for render json (you can indicate serialization context with `\Webmunkeez\AdrBundle\Annotation\SerializationContext` (as doctrine annotation or php8 attribute)):

```php
// php8 attribute

#[SerializationContext(['groups' => 'group_one'])]
final class StoryDetailAction implements \Webmunkeez\AdrBundle\Action\ActionInterface
{
    ...
}

// doctrine annotation

/**
 * @SerializationContext({"groups": "group_one"})
 */
final class StoryDetailAction implements \Webmunkeez\AdrBundle\Action\ActionInterface
{
    ...
}
```

This responder is active if the request contains `HTTP_ACCEPT application/json` header.  
It has a `priority: -10`.

#### Custom responders

You can write your own reponders like in my previous `XmlResponder` example, by implementing `\Webmunkeez\AdrBundle\Response\ResponderInterface`.

Services implementing this interface are automatically tagged `webmunkeez.responder` with `priority: 0`, and you can change it (in your `service.yaml` or by static `getDefaultPriority` method ; see [https://symfony.com/doc/current/service_container/tags.html#tagged-services-with-priority](https://symfony.com/doc/current/service_container/tags.html#tagged-services-with-priority)).

You can define "generic" responders like html, json, xml and so on. But you can also define more specifics, by checking `$request->attributes->get('_controller')` to make a responder only for a specific action:

```php
final class CustomResponder implements \Webmunkeez\AdrBundle\Response\ResponderInterface
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