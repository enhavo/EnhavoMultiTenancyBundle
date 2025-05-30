<?php

/*
 * This file is part of the enhavo package.
 *
 * (c) WE ARE INDEED GmbH
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Enhavo\Bundle\MultiTenancyBundle\Tenant;

use Doctrine\ORM\EntityManagerInterface;
use Enhavo\Bundle\MultiTenancyBundle\Provider\ProviderInterface;
use Enhavo\Bundle\MultiTenancyBundle\Resolver\ResolverInterface;

class TenantManager
{
    /** @var ResolverInterface */
    private $resolver;

    /** @var ProviderInterface */
    private $provider;

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * TenantManager constructor.
     */
    public function __construct(ResolverInterface $resolver, ProviderInterface $provider, EntityManagerInterface $entityManager)
    {
        $this->resolver = $resolver;
        $this->provider = $provider;
        $this->entityManager = $entityManager;
    }

    public function getTenant($key = null)
    {
        if ($key) {
            foreach ($this->provider->getTenants() as $tenant) {
                if ($tenant->getKey() == $key) {
                    return $tenant;
                }
            }

            return null;
        }

        return $this->resolver->getTenant();
    }

    public function getTenants()
    {
        return $this->provider->getTenants();
    }

    public function disableDoctrineFilter()
    {
        $this->entityManager->getFilters()->disable('tenant');
    }

    public function enableDoctrineFilter()
    {
        $this->entityManager->getFilters()->enable('tenant');
    }
}
