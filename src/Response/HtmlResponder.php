<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\ADRBundle\Response;

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class HtmlResponder implements ResponderInterface
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
        return null !== $this->requestStack->getCurrentRequest()
            && 'html' === $this->requestStack->getCurrentRequest()->getPreferredFormat()
            && null !== $this->requestStack->getCurrentRequest()->attributes->get('_template_path');
    }

    public function render(array $data = []): Response
    {
        $templatePath = $this->requestStack->getCurrentRequest()->attributes->get('_template_path');
        $html = $this->twig->render($templatePath, $data);

        return new Response($html);
    }
}
