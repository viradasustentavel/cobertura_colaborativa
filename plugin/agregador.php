<?php
/*
Plugin Name: Cobertura Colaborativa
Plugin URL: #
Description: Cobertura Virada Sustentável
Version: 1.0
Author: Virada Sustentável
Author URI: http://viradasustentavel.org.br
Text Domain: colab
Copyright: 2014, Virada Sustentável
*/

if (!defined('ABSPATH')) {
    die('Ops, você não deveria estar aqui');
}

$plugin_prefix = 'AGGREGATOR_';

define('COBCOLABORATIVA_INDEX_FILE', plugin_basename(__FILE__));
define('COBCOLABORATIVA_PLUGIN_NAME', dirname(COBCOLABORATIVA_INDEX_FILE));
define('COBCOLABORATIVA_PATH', WP_PLUGIN_DIR . '/' . COBCOLABORATIVA_PLUGIN_NAME);

require COBCOLABORATIVA_PATH . '/models/cobcolaborativa-base-model.php';
require COBCOLABORATIVA_PATH . '/models/cobcolaborativa-data.php';

require_once(COBCOLABORATIVA_PATH . '/controllers/cobcolaborativa-home.php');

require COBCOLABORATIVA_PATH . '/lib/Google/Client.php';
require COBCOLABORATIVA_PATH . '/lib/Google/Service/YouTube.php';
require COBCOLABORATIVA_PATH . '/lib/instagram/instagram.class.php';
require COBCOLABORATIVA_PATH . '/lib/twitter/codebird.php';

require COBCOLABORATIVA_PATH . '/extension/options_page.php';

class CobColaborativa {
    public $routes;
    public $query_vars;

    public function __construct() {
        $this->query_vars = array('plugin', 'controller', 'method');
    }

    public function output() {
        global $wp_query;

        if( !session_id() ) @session_start();

        $plugin = $wp_query->get('plugin');
        $controller = 'home';
        $method = $wp_query->get('method');

        if ($plugin && $plugin == COBCOLABORATIVA_PLUGIN_NAME) {
            $aggregator_controller = new ReflectionClass('CobColaborativa' . ucfirst($controller));
            $aggregator_controller = $aggregator_controller->newInstanceArgs();
            $aggregator_controller->$method();
        }
    }

    public function routes($routes) {
        global $wp_rewrite;

        $new_routes = array(
            'agregador/fetcher/instagram?$' => 'index.php?plugin=' . COBCOLABORATIVA_PLUGIN_NAME . '&controller=home&method=instagram',
            'agregador/fetcher/youtube?$' => 'index.php?plugin=' . COBCOLABORATIVA_PLUGIN_NAME . '&controller=home&method=youtube',
            'agregador/fetcher/twitter?$' => 'index.php?plugin=' . COBCOLABORATIVA_PLUGIN_NAME . '&controller=home&method=twitter',
            'agregador/fetcher/flickr?$' => 'index.php?plugin=' . COBCOLABORATIVA_PLUGIN_NAME . '&controller=home&method=flickr'
        );

        $this->routes = array_merge($new_routes, $routes);

        return $this->routes;
    }

    public function query_vars($vars) {
        return array_merge( $this->query_vars, $vars );
    }

    public function activation() {
        $cobcolaborativa = new CobColaborativaDataModel();
        $cobcolaborativa->create_table();

        global $wp_rewrite;
        add_filter('rewrite_rules_array', array($this, 'routes'));
        $wp_rewrite->flush_rules();
    }

    public function deactivation() {
        global $wp_rewrite;
        $wp_rewrite->flush_rules();
    }

    public function load_hooks() {
        add_filter('query_vars', array($this, 'query_vars'));
        add_filter('rewrite_rules_array', array($this, 'routes'));
        add_action( 'template_redirect', array($this, 'output') );
    }
}

$cobcolaborativa = new CobColaborativa();
$cobcolaborativa->load_hooks();

register_activation_hook(COBCOLABORATIVA_INDEX_FILE, array($cobcolaborativa, 'activation'));
register_deactivation_hook(COBCOLABORATIVA_INDEX_FILE, array($cobcolaborativa, 'deactivation')); ?>
