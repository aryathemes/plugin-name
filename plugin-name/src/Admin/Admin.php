<?php
/**
 * @package PluginName\Admin
 */

namespace PluginName\Admin;

use PluginName\Loader as Plugin;
use PluginName\Admin\Page\Settings;

/**
 * Admin class.
 *
 * @since 1.0.0
 */
class Admin
{
    /**
     * @since 1.0.0
     * @var string
     */
    private $plugin_file = null;

    /**
     * @since 1.0.0
     * @var string
     */
    private $plugin_slug = null;

    /**
     * Singleton instance
     *
     * @since 1.0.0
     * @var Admin
     */
    private static $instance;

    /**
     * Constructor.
     *
     * @since 1.0.0
     */
    private function __construct( Plugin $plugin )
    {
        $this->plugin_file = $plugin->getFile();
        $this->plugin_slug = $plugin->getSlug();

        add_action( 'admin_init', [ $this, 'action' ] );
        add_action( 'admin_menu', [ $this, 'menu' ] );
    }

    /**
     * The singleton method.
     *
     * @since 1.0.0
     *
     * @return Admin
     */
    public static function init( Plugin $plugin ): Admin
    {
        if ( ! isset( self::$instance ) ) {
            self::$instance = new Admin( $plugin );
        }

        return self::$instance;
    }

    /**
     * Adds Settings link to plugins area.
     *
     * @since 1.0.0
     */
    public function action()
    {
        add_filter( 'plugin_action_links', function( $actions, $plugin_file, $plugin_data, $context ) {

            if ( $this->plugin_file !== $plugin_file ) {
                return $actions;
            }

            $new_actions = [];

            $settings = add_query_arg( [
                'page' => sprintf( '%s/settings.php', $this->plugin_slug )
            ], network_admin_url( 'admin.php' ) );

            $new_actions['settings'] = sprintf( '<a href="%s">%s</a>', esc_url( $settings ), esc_html__( 'Settings', 'plugin-name' ) );

            $about = add_query_arg( [
                'page' => sprintf( '%s/about.php', $this->plugin_slug )
            ], network_admin_url( 'admin.php' ) );

            $new_actions['about'] = sprintf( '<a href="%s">%s</a>', esc_url( $about ), esc_html__( 'About', 'plugin-name' ) );

            return array_merge( $actions, $new_actions );
        }, 10, 4 );
    }

    /**
     * Adds the admin menus.
     *
     * @since 1.0.0
     */
    public function menu()
    {
        global $menu;

        $menu[35] = [
            0 => '',
            1 => 'read',
            2 => 'separator35',
            3 => '',
            4 => 'wp-menu-separator'
        ];

        $settings_slug = sprintf( '%s/settings.php', $this->plugin_slug );
        
        $settings = add_menu_page(
            esc_html__( 'Plugin Name Settings', 'plugin-name' ),
            esc_html__( 'Plugin Name', 'plugin-name' ),
            'manage_options',
            $settings_slug,
            [ 'PluginName\Admin\Page\Settings', 'render' ],
            'dashicons-smiley',
            35
        );

        add_action( "load-$settings", [ 'PluginName\Admin\Page\Settings', 'help' ] );

        $about_slug = sprintf( '%s/about.php', $this->plugin_slug );

        $about = add_submenu_page(
            $settings_slug,
            esc_html__( 'About Plugin Name', 'plugin-name' ),
            esc_html__( 'About', 'plugin-name' ),
            'manage_options',
            $about_slug,
            [ 'PluginName\Admin\Page\About', 'render' ]
        );
    }
}