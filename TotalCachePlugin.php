<?php
/**
 * Total Cache plugin for Craft CMS
 *
 * A plugin to clear, warm and bust Cache.
 *
 * @author    Bhashkar Yadav
 * @copyright Copyright (c) 2018 Bhashkar Yadav
 * @link      http://sidd3.com
 * @package   TotalCache
 * @since     1.0.0
 */

namespace Craft;

class TotalCachePlugin extends BasePlugin
{
    public function getName()
    {
         return Craft::t('Total Cache');
    }

    public function getDescription()
    {
        return Craft::t('A plugin to clear, warm and bust Cache.');
    }

    public function getDocumentationUrl()
    {
        return 'https://github.com/bhashkar007/totalcache/blob/master/README.md';
    }

    public function getReleaseFeedUrl()
    {
        return 'https://raw.githubusercontent.com/bhashkar007/totalcache/master/releases.json';
    }

    public function getVersion()
    {
        return '1.0.0';
    }

    public function getSchemaVersion()
    {
        return '1.0.0';
    }

    public function getDeveloper()
    {
        return 'Bhashkar Yadav';
    }

    public function getDeveloperUrl()
    {
        return 'http://sidd3.com';
    }

    public function hasCpSection()
    {
        return false;
    }

    protected function defineSettings()
    {
        return array(
            'enabledSections'       => AttributeType::Mixed,
            'cachePath' => array(
                AttributeType::String,
                'required' => true
            ),
            'key' => array(
                AttributeType::String,
                'required' => true
            ),
            'parallelRequests' => array(
                AttributeType::Number,
                'required' => true,
                'default' => 20,
            ),
        );
    }

    public function getSettingsHtml()
    {
        $editableSections = array();
        $sections = array();
        $allSections = craft()->sections->getAllSections('name');
        
        foreach ($allSections as $section)
        {
            $editableSections[$section->handle] = array('section' => $section);
            $sections[] = $section;

            // Find total entries
            $criteria = craft()->elements->getCriteria(ElementType::Entry);
            $criteria->section = $section->handle;
            $count = $criteria->count();

            $sectionCount[$section->handle] = $count;
        }

        return craft()->templates->render('totalcache/_settings', array(
            'settings' => $this->getSettings(),
            'sections' => $sections,
            'sectionCount' => $sectionCount,
        ));
    }

    public function registerCachePaths()
    {
        $cachePaths = array();
        $settings = $this->getSettings();
        if (!empty($settings)) {
            if (!empty($settings->cachePath)) {
                $cacheDirs = explode(',', $settings->cachePath);
                foreach ($cacheDirs as $cacheDir) {
                    $cacheDir = trim($cacheDir);
                    $cachePaths = array_merge(
                        $cachePaths,
                        [
                            $cacheDir => Craft::t('FastCGI Cache'). ' '.$cacheDir,
                        ]
                    );
                }
            }
        }

        return $cachePaths;
    }

    public function init()
    {
        parent::init();
        /* Clear cache whenever an element is saved */
        craft()->on('elements.onSaveElement', function (Event $event) {
            /** @var BaseElementModel $element */
            $element = $event->params['element'];
            $isNewElement = $event->params['isNewElement'];
            $bustCache = true;
            // Only bust the cache if the element is ENABLED or LIVE
            if (($element->getStatus() != BaseElementModel::ENABLED) && ($element->getStatus() != EntryModel::LIVE)) {
                $bustCache = false;
            }
            // Only bust the cache if it's not certain excluded element types
            $elemType = $element->getElementType();
            if (($elemType == 'SproutSeo_Redirect') || ($elemType == 'PushNotifications_Device')) {
                $bustCache = false;
            }

            if ($bustCache) {
                TotalCachePlugin::log(
                    "Cache busted due to saving: " . $elemType . " - " . $element->getTitle(),
                    LogLevel::Info,
                    true
                );
                craft()->totalCache->clearAll();
                craft()->totalCache->warmCache();

            }
        });
    }


}