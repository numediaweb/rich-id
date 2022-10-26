<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('roles', [$this, 'formatRoles']),
        ];
    }

    /**
     * Strips user role from admins.
     *
     * @return string
     */
    public function formatRoles(array $roles)
    {
        if (\in_array('ROLE_ADMIN', $roles)) {
            return ['ROLE_ADMIN'];
        }

        return $roles;
    }
}
