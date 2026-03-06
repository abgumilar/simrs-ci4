<?php

if (!function_exists('has_permission')) {
    /**
     * Check if current user has specific permission
     */
    function has_permission(string $permission): bool
    {
        $session = session();
        $userPerms = $session->get('permissions') ?? [];
        
        // Development bypass or Admin role
        if ($session->get('role') === 'Admin') {
            return true;
        }

        return in_array($permission, $userPerms);
    }
}

if (!function_exists('get_menus')) {
    /**
     * Get authorized menus for an environment
     */
    function get_menus(string $environment)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('menus');
        $builder->where('environment', $environment);
        $builder->where('is_active', true);
        $builder->orderBy('sequence', 'ASC');
        
        $allMenus = $builder->get()->getResultArray();
        
        $authorizedMenus = [];
        foreach ($allMenus as $menu) {
            if (empty($menu['permission']) || has_permission($menu['permission'])) {
                $authorizedMenus[] = $menu;
            }
        }
        
        return $authorizedMenus;
    }
}
