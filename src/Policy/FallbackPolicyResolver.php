<?php
declare(strict_types=1);

namespace App\Policy;

use Authorization\Policy\ResolverInterface;
use Authorization\Policy\Exception\MissingPolicyException;

class FallbackPolicyResolver implements ResolverInterface
{
    protected ResolverInterface $innerResolver;

    public function __construct(ResolverInterface $innerResolver)
    {
        $this->innerResolver = $innerResolver;
    }

    /**
     * Resolve policy or fallback to FallbackPolicy
     */
    public function getPolicy(mixed $resource): mixed
    {
        try {
            return $this->innerResolver->getPolicy($resource);
        } catch (MissingPolicyException $e) {
            return new FallbackPolicy();
        }
    }
}
