<?php
/**
 * Total Cache plugin for Craft CMS
 *
 * TotalCache Controller
 *
 * @author    Bhashkar Yadav
 * @copyright Copyright (c) 2018 Bhashkar Yadav
 * @link      http://sidd3.com
 * @package   TotalCache
 * @since     1.0.0
 */

namespace Craft;

class TotalCacheController extends BaseController
{

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     * @access protected
     */
    protected $allowAnonymous = array('actionWarm','actionClear','actionClearAndWarm');

    /**
     * Handle the action to clear the cache.
     */
    public function actionWarm()
    {
        
        $settings = $this->getSettings();

        $key = craft()->request->getParam('key');

        if (!$settings->key OR $key != $settings->key) {
            die('Unauthorized key');
        }

        craft()->totalCache->warmCache();

        if (craft()->request->getPost('redirect')) {
            $this->redirectToPostedUrl();
        }

        die('All URLs are warmed up successfully!');
    }

    public function actionClear()
    {
        if (!$plugin = craft()->plugins->getPlugin('totalCache')) {
            die('Could not find the plugin');
        }

        $settings = $plugin->getSettings();

        $key = craft()->request->getParam('key');

        if (!$settings->key OR $key != $settings->key) {
            die('Unauthorized key');
        }

        craft()->totalCache->clearFastCgi();
        craft()->templateCache->deleteAllCaches();

        if (craft()->request->getPost('redirect')) {
            $this->redirectToPostedUrl();
        }

        die('Your cache cleared successfully!');
    }

    public function actionClearAndWarm()
    {
        if (!$plugin = craft()->plugins->getPlugin('totalCache')) {
            die('Could not find the plugin');
        }

        $settings = $plugin->getSettings();

        $key = craft()->request->getParam('key');

        if (!$settings->key OR $key != $settings->key) {
            die('Unauthorized key');
        }

        craft()->templateCache->deleteAllCaches();
        craft()->totalCache->warmCache();

        if (craft()->request->getPost('redirect')) {
            $this->redirectToPostedUrl();
        }

        die('Your cache was cleared and then warmed successfully!');
    }

    public function getSettings()
    {
        if (!$plugin = craft()->plugins->getPlugin('totalcache')) {
            die('Could not find the plugin');
        }

        return $plugin->getSettings();
    }
}