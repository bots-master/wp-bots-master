<?php

namespace WebXID\BotsMaster\Admin;

use WebXID\BotsMaster\Controllers\Admin\AdminControllerInterface;
use WebXID\EDMo\AbstractClass\BasicDataContainer;

/**
 *
 */
class MenuRegistrer extends BasicDataContainer
{
    protected $parent_menu_slug;
    protected $page_title; // mandatory
    protected $menu_title; // mandatory
    protected $sub_menu_title;
    protected $capability; // mandatory
    protected $slug; // mandatory
    protected $position;
    protected $icon_url;
    /** @var AdminControllerInterface */
    protected $request_hendler;

    #region Actions

    public function register()
    {
        $this->validate();

        /** @var AdminControllerInterface $request_hendler_instance */
        $request_hendler_instance = $this->request_hendler
            ? (
                is_string($this->request_hendler)
                    ? $this->request_hendler::make()
                    : $this->request_hendler
            )
            : null
        ;

        if ($this->parent_menu_slug) {
            $settings_page = add_submenu_page(
                $this->parent_menu_slug,
                $this->page_title,
                $this->menu_title ?: $this->sub_menu_title,
                $this->capability,
                $this->slug,
                $this->request_hendler
                    ? [$request_hendler_instance, 'loadPage']
                    : false,
                $this->position ?? null
            );
        } else {
            $settings_page = add_menu_page(
                $this->page_title,
                $this->menu_title,
                $this->capability,
                $this->slug,
                $this->request_hendler && !$this->sub_menu_title
                    ? [$request_hendler_instance, 'loadPage']
                    : false,
                $this->icon_url ?? '',
                $this->position
            );

            if ($this->sub_menu_title) {
                MenuRegistrer::childTo($this->slug)
                    ->pageTitle($this->page_title)
                    ->menuTitle($this->sub_menu_title)
                    ->capability($this->capability)
                    ->slug($this->slug)
                    ->requestHendler($this->request_hendler)
                    ->register();

                return;
            }
        }

        add_action( 'load-' . $settings_page, function () use ($request_hendler_instance)
        {
            if (!$this->request_hendler) {
                return;
            }

            if ( ! empty( $_POST ) ) {
                call_user_func([$request_hendler_instance, 'postRequest']);

                return;
            }

            if ( ! empty( $_GET ) ) {
                call_user_func([$request_hendler_instance, 'getRequest']);

                return;
            }
        });
    }

    #endregion

    #region Setters

    /**
     * @param string $parent_menu_slug
     *
     * @return $this
     */
    public static function childTo(string $parent_menu_slug)
    {
        return static::make([
            'parent_menu_slug' => $parent_menu_slug,
        ]);
    }

    /**
     * @param string $page_title
     *
     * @return $this
     */
    public function pageTitle(string $title)
    {
        $this->page_title = $title;

        return $this;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function menuTitle(string $title)
    {
        $this->menu_title = $title;

        return $this;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function subMenuTitle(string $title)
    {
        $this->sub_menu_title = $title;

        return $this;
    }

    /**
     * A permission, which a user has to have to see the menu item
     * @see https://wordpress.org/support/article/roles-and-capabilities/#administrator
     *
     * @param string $capability
     *
     * @return $this
     */
    public function capability(string $capability)
    {
        $this->capability = $capability;

        return $this;
    }

    /**
     * @param string $slug
     *
     * @return $this
     */
    public function slug(string $slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @param int $position
     *
     * @return $this
     */
    public function position(int $position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * @param int $icon_url
     *
     * @return $this
     */
    public function iconUrl(string $icon_url)
    {
        $this->icon_url = $icon_url;

        return $this;
    }

    /**
     * @param string|AdminControllerInterface $hendler_class
     *
     * @return $this
     */
    public function requestHendler($hendler_class)
    {
        if (!is_string($hendler_class) && !$hendler_class instanceof AdminControllerInterface) {
            throw new \InvalidArgumentException('Invalid $hendler_class');
        }

        $this->request_hendler = $hendler_class;

        return $this;
    }

    #endregion

    #region Helpers

    /**
     *
     */
    private function validate()
    {
        if (!$this->page_title) {
            throw new \LogicException('`page_title` is mandatory');
        }

        if (!$this->menu_title) {
            throw new \LogicException('`menu_title` is mandatory');
        }

        if (!$this->capability) {
            throw new \LogicException('`capability` is mandatory');
        }

        if (!$this->slug) {
            throw new \LogicException('`slug` is mandatory');
        }

        if ($this->parent_menu_slug && $this->icon_url) {
            throw new \LogicException('submenu item does not have any icon');
        }
    }

    #endregion
}
