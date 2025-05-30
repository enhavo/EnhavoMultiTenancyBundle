<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MultiTenancyBundle\Resolver;

use Enhavo\Bundle\MultiTenancyBundle\Exception\ResolveException;
use Enhavo\Bundle\MultiTenancyBundle\Model\TenantInterface;
use Enhavo\Bundle\MultiTenancyBundle\Provider\ProviderInterface;

class EnvResolver implements ResolverInterface
{
    /** @var ProviderInterface */
    private $provider;

    /** @var string */
    private $envName;

    /**
     * EnvResolver constructor.
     */
    public function __construct(ProviderInterface $provider, $envName = 'TENANCY')
    {
        $this->provider = $provider;
        $this->envName = $envName;
    }

    public function getTenant(): ?TenantInterface
    {
        $envValue = getenv($this->envName);
        if (!empty($envValue)) {
            $keys = [];
            foreach ($this->provider->getTenants() as $tenant) {
                $keys[] = $tenant->getKey();
                if ($tenant->getKey() === $envValue) {
                    return $tenant;
                }
            }

            throw new ResolveException(sprintf('Environment variable was defined with value "%s", but does not match any of this tenants [%s]', $envValue, implode(',', $keys)));
        }

        return null;
    }
}
