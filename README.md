# Total Cache plugin for Craft CMS

A plugin to clear, warm and bust Cache.

## Installation

To install Total Cache, follow these steps:

1. Download & unzip the file and place the `totalcache` directory into your `craft/plugins` directory
2.  -OR- do a `git clone https://github.com/bhashkar007/totalcache.git` directly into your `craft/plugins` folder.  You can then update it with `git pull`
3.  -OR- install with Composer via `composer require bhashkar007/totalcache`
4. Install plugin in the Craft Control Panel under Settings > Plugins
5. The plugin folder should be named `totalcache` for Craft to see it.  GitHub recently started appending `-master` (the branch name) to the name of the folder for zip file downloads.

Total Cache works on Craft 2.4.x and Craft 2.5.x.

## Total Cache Overview

Feature of Total Cache Plugin are:
- Have Three different URL to clear cache, warm cache & clear and warm cache together.
- Clearing cache clears both craft cache & FastCGI cache.
- Cache is cleared and then warmed automatically when something is added or modified.
- Can specify multiple FastCGI path.
- Add a key to run action links.
- Ability to specify sections to warm.

## Configuring Total Cache

Go to Plugin Setting to add :
- Key
- Sections to warm
- FastCGI path

## Using Total Cache

**Examples**
Simply create a GET or a POST request to the action URL.

**Template**
**To Clear Cache**
{{ siteUrl(craft.config.get('actionTrigger') ~ '/totalCache/clear', { key: key }) }}

**To Warm Cache**
{{ siteUrl(craft.config.get('actionTrigger') ~ '/totalCache/warm', { key: key }) }}

**To Clear and then Warm Cache**
{{ siteUrl(craft.config.get('actionTrigger') ~ '/totalCache/clearAndWarm', { key: key }) }}

**URLs**

**To Clear Cache**
{{ siteUrl }}actions/totalCache/clear?key={{ key }}

**To Warm Cache**
{{ siteUrl }}actions/totalCache/warm?key={{ key }}

**To Clear and then Warm Cache**
{{ siteUrl }}actions/totalCache/clearAndWarm?key={{ key }}

Brought to you by [Bhashkar Yadav](http://sidd3.com)
