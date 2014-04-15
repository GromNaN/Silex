<?php

/*
 * This file is part of the Silex framework.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Silex;

use Symfony\Component\Routing\RequestContext as SymfonyRequestContext;
use Symfony\Component\Routing\Matcher\UrlMatcherInterface;
use Symfony\Component\Routing\Matcher\RequestMatcherInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * Implements a lazy UrlMatcher.
 *
 * @author Igor Wiedler <igor@wiedler.ch>
 */
class LazyUrlMatcher implements RequestMatcherInterface
{
    private $factory;

    public function __construct(\Closure $factory)
    {
        $this->factory = $factory;
    }

    /**
     * Returns the corresponding UrlMatcherInterface instance.
     *
     * @return UrlMatcherInterface|RequestMatcherInterface
     */
    public function getUrlMatcher()
    {
        $urlMatcher = call_user_func($this->factory);
        if (!$urlMatcher instanceof UrlMatcherInterface && !$urlMatcher instanceof RequestMatcherInterface) {
            throw new \LogicException("Factory supplied to LazyUrlMatcher must return implementation of UrlMatcherInterface or RequestMatcherInterface.");
        }

        return $urlMatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function matchRequest(Request $request)
    {
        $matcher = $this->getUrlMatcher();

        // matching a request is more powerful than matching a URL path + context, so try that first
        if ($matcher instanceof RequestMatcherInterface) {
            return $matcher->matchRequest($request);
        }

        return $matcher->match($request->getPathInfo());
    }
}
