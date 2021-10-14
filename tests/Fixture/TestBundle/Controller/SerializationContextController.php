<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\AdrBundle\Test\Fixture\TestBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Webmunkeez\AdrBundle\Annotation\SerializationContext;
use Webmunkeez\AdrBundle\Test\Fixture\TestBundle\Entity\Story;

/**
 * @author Yannis Sgarra <hello@yannissgarra.com>
 */
final class SerializationContextController extends AbstractAction
{
    public const SERIALIZATION_CONTEXT_ATTRIBUTE_ROUTE_URI = '/serialization-context-attribute-controller';
    public const SERIALIZATION_CONTEXT_ANNOTATION_ROUTE_URI = '/serialization-context-annotation-controller';

    #[Route(self::SERIALIZATION_CONTEXT_ATTRIBUTE_ROUTE_URI)]
    #[SerializationContext(['groups' => 'group_one'])]
    public function templateAttribute(): Response
    {
        return $this->render(Story::initData());
    }

    /**
     * @Route("/serialization-context-annotation-controller")
     * @SerializationContext({"groups": "group_one"})
     */
    public function templateAnnotation(): Response
    {
        return $this->render(Story::initData());
    }
}
