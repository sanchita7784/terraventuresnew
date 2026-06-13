<?php
namespace App;
class Menu
{
    public static function get($resource)
    {
        $menu = [];

        $menu[] = Home::get();

        $menu[] = Transaction::get();
        
        $menu[] = System::get();

        self::check_for_active($menu, $resource);

        // d($menu); exit;

        return $menu;
    }

    private static function check_for_active(&$menus, $resource)
    {
        $auth = new Auth();
        $allowed_links = $auth->getAllAllowPermission();
        foreach($menus as $k => $menu)
        {
            if (isset($menu['links']))
            {
                foreach($menu['links'] as $k2 => $submenu)
                {
                    if (isset($submenu['links']))
                    {
                        foreach($submenu['links'] as $k3 => $child_menu)
                        {
                            if (in_array($child_menu['r'], $allowed_links))
                            {
                                if ($resource == $child_menu['r'])
                                {
                                    $menus[$k]['links'][$k2]['links'][$k3]['active'] = true;
                                }
                            }
                            else
                            {
                                unset($menus[$k]['links'][$k2]['links'][$k3]);
                            }
                        }

                        if (empty($menus[$k]['links'][$k2]['links']))
                        {
                            unset($menus[$k]['links'][$k2]);
                        }
                    }
                    else
                    {
                        if (in_array($submenu['r'], $allowed_links))
                        {
                            if ($resource == $submenu['r'])
                            {
                                $menus[$k]['links'][$k2]['active'] = true;
                            }
                        }
                        else
                        {
                            unset($menus[$k]['links'][$k2]);
                        }
                    }
                }

                if (empty($menus[$k]['links']))
                {
                    unset($menus[$k]);
                }
            }
            else
            {
                if (in_array($menu['r'], $allowed_links))
                {
                    if ($resource == $menu['r'])
                    {
                        $menus[$k]['active'] = true;
                    }
                }
                else
                {
                    unset($menus[$k]);
                }
            }
        }
    }
}

class Home
{
    public static function get()
    {
        return [
            "r" => "dashboard",
            "title" => "Dashboard",
            "icon" => "fas fa-tachometer-alt"
        ];
    }
}

class System
{
    public static function get()
    {
        $links = [
            "title" => "System",
            "icon" => "fas fa-cogs",
            "links" => []
        ];

        $links['links'][] = self::user();
        $links['links'][] = self::role();
        $links['links'][] = self::payment_gateway();        
        $links['links'][] = [
            "title" => "Setting",
            "r" => "setting",
            "icon" => "fas fa-key"
        ];

        return $links;
    }

    public static function user()
    {
        $links = [
            "title" => "User",
            "icon" => "fas fa-users",
            "links" => []
        ];

        $links['links'][] = [
            "title" => "Summary",
            "icon" => "fas fa-th-list",
            "r" => "user/summary"
        ];

        $links['links'][] = [
            "title" => "Add",
            "icon" => "fas fa-plus-circle",
            "r" => "user/save"
        ];

        return $links;
    }

    public static function role()
    {
        $links = [
            "title" => "Role",
            "icon" => "fas fa-users",
            "links" => []
        ];

        $links['links'][] = [
            "title" => "Summary",
            "icon" => "fas fa-th-list",
            "r" => "role/summary"
        ];

        $links['links'][] = [
            "title" => "Add",
            "icon" => "fas fa-plus-circle",
            "r" => "role/save"
        ];

        $links['links'][] = [
            "title" => "Set Permission",
            "icon" => " fas fa-list",
            "r" => "role/set_permission"
        ];

        return $links;
    }

    public static function payment_gateway()
    {
        $links = [
            "title" => "Payment Gateway",
            "icon" => "fas fa-money-check-alt",
            "links" => []
        ];

        $links['links'][] = [
            "title" => "Summary",
            "icon" => "fas fa-th-list",
            "r" => "payment_gateway/summary"
        ];

        $links['links'][] = [
            "title" => "Add",
            "icon" => "fas fa-plus-circle",
            "r" => "payment_gateway/save"
        ];

        return $links;
    }
}

class Transaction
{
    public static function get()
    {
        $links = [
            "title" => "Transaction",
            "icon" => "fas fa-money-check-alt",
            "links" => []
        ];

        $links['links'][] = [
            "title" => "Summary",
            "icon" => "fas fa-th-list",
            "r" => "transaction/summary"
        ];

        $links['links'][] = [
            "title" => "My Summary",
            "icon" => "fas fa-th-list",
            "r" => "transaction/my_summary"
        ];

        $links['links'][] = [
            "title" => "Create Payment",
            "icon" => "fas fa-plus-circle",
            "r" => "transaction/transfer"
        ];

        return $links;
    }
}
