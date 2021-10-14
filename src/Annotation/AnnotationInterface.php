<?php

/*
 * (c) Yannis Sgarra <hello@yannissgarra.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Webmunkeez\AdrBundle\Annotation;

interface AnnotationInterface
{
    /**
     * @return mixed
     */
    public function getValue();

    public function getAliasName(): string;
}
