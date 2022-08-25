<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Response;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Webmunkeez\ADRBundle\Response\ResponderInterface;
use Webmunkeez\ADRBundle\Response\ResponseDataInterface;
use Webmunkeez\ADRBundle\Test\Fixture\TestBundle\Controller\CustomResponderAction;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class CustomResponder implements ResponderInterface
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

    public function render(?ResponseDataInterface $data = null): Response
    {
        $html = $this->twig->render($this->requestStack->getCurrentRequest()->attributes->get('_template_path'), ['data' => $data, 'customResponder' => true]);

        return new Response($html);
    }
}
