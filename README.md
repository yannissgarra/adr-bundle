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