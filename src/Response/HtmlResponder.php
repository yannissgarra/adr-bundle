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
use Webmunkeez\ADRBundle\Attribute\Template;
use Webmunkeez\ADRBundle\Exception\RenderingException;
use Webmunkeez\ADRBundle\Exception\TemplateMissingException;

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
            && 'html' === $this->requestStack->getCurrentRequest()->getPreferredFormat();
    }

    public function render(?ResponseDataInterface $data = null): Response
    {
        if (null === $this->requestStack->getCurrentRequest()->attributes->get('_'.Template::getAliasName())) {
            throw new RenderingException('', 0, new TemplateMissingException());
        }

        $templatePath = $this->requestStack->getCurrentRequest()->attributes->get('_'.Template::getAliasName());
        $html = $this->twig->render($templatePath, ['data' => $data]);

        return new Response($html);
    }
}
