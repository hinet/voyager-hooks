<?php

namespace Hinet\VoyagerHooks;

use Illuminate\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use Hinet\Hooks\HooksServiceProvider;
use Hinet\Voyager\Models\Menu;
use Hinet\Voyager\Models\MenuItem;
use Hinet\Voyager\Models\Permission;
use Hinet\Voyager\Models\Role;

class VoyagerHooksServiceProvider extends ServiceProvider
{
    /**
     * Register the application services.
     */
    public function register()
    {
        // Register the HooksServiceProvider
        $this->app->register(HooksServiceProvider::class);

        // Load views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'voyager-hooks');
    }

    /**
     * Bootstrap the application services.
     *
     * @param \Illuminate\Events\Dispatcher $events
     */
    public function boot(Dispatcher $events)
    {
        if (config('voyager-hooks.add-route', true)) {
            $events->listen('voyager.admin.routing', [$this, 'addHookRoute']);
        }

        if (config('voyager-hooks.add-menu', true)) {
            $events->listen('voyager.menu.display', [$this, 'addHookMenuItem']);
        }

        if (config('voyager-hooks.add-permission', true)) {
            $events->listen('voyager.permissions.loaded', [$this, 'addPermission']);
        }
    }

    public function addHookRoute($router)
    {
        $namespacePrefix = '\\Hinet\\VoyagerHooks\\Controllers\\';

        $router->get('hooks', ['uses' => $namespacePrefix.'HooksController@index', 'as' => 'hooks']);
        $router->get('hooks/{name}/enable', ['uses' => $namespacePrefix.'HooksController@enable', 'as' => 'hooks.enable']);
        $router->get('hooks/{name}/disable', ['uses' => $namespacePrefix.'HooksController@disable', 'as' => 'hooks.disable']);
        $router->get('hooks/{name}/update', ['uses' => $namespacePrefix.'HooksController@update', 'as' => 'hooks.update']);
        $router->post('hooks', ['uses' => $namespacePrefix.'HooksController@install', 'as' => 'hooks.install']);
        $router->delete('hooks/{name}', ['uses' => $namespacePrefix.'HooksController@uninstall', 'as' => 'hooks.uninstall']);
    }

    public function addHookMenuItem(Menu $menu)
    {
        if ($menu->name == 'admin') {
            $url = route('voyager.hooks', [], false);

            $menuItem = $menu->items->where('url', $url)->first();

            if (is_null($menuItem)) {
                $menu->items->add(MenuItem::create([
                    'menu_id'    => $menu->id,
                    'url'        => $url,
                    'title'      => 'Hooks',
                    'target'     => '_self',
                    'icon_class' => 'voyager-hook',
                    'color'      => null,
                    'parent_id'  => null,
                    'order'      => 99,
                ]));

                $this->ensurePermissionExist();
            }
        }
    }

    protected function ensurePermissionExist()
    {
        $permission = Permission::firstOrNew([
            'key'        => 'browse_hooks',
            'table_name' => 'admin',
        ]);

        if (!$permission->exists) {
            $permission->save();

            $role = Role::where('name', 'admin')->first();
            if (!is_null($role)) {
                $role->permissions()->attach($permission);
            }
        }
    }
}
