<?php

$plugin['name'] = 'oui_embed';

$plugin['allow_html_help'] = 0;

$plugin['version'] = '0.1.1';
$plugin['author'] = 'Nicolas Morand';
$plugin['author_uri'] = 'https://github.com/NicolasGraph';
$plugin['description'] = 'Embed stuff';

$plugin['order'] = 5;

$plugin['type'] = 1;

// Plugin 'flags' signal the presence of optional capabilities to the core plugin loader.
// Use an appropriately OR-ed combination of these flags.
// The four high-order bits 0xf000 are available for this plugin's private use.
// if (!defined('PLUGIN_HAS_PREFS')) define('PLUGIN_HAS_PREFS', 0x0001); // This plugin wants to receive "plugin_prefs.{$plugin['name']}" events
if (!defined('PLUGIN_LIFECYCLE_NOTIFY')) define('PLUGIN_LIFECYCLE_NOTIFY', 0x0002); // This plugin wants to receive "plugin_lifecycle.{$plugin['name']}" events

// $plugin['flags'] = PLUGIN_HAS_PREFS | PLUGIN_LIFECYCLE_NOTIFY;
$plugin['flags'] = PLUGIN_HAS_PREFS | PLUGIN_LIFECYCLE_NOTIFY;

if (!defined('txpinterface'))
    @include_once('zem_tpl.php');

if (0) {

?>
# --- BEGIN PLUGIN HELP ---
h1. oui_embed

*! In process; this plugin is not released yet…*

Easily embed stuff…

h2. Table of contents

* "Plugin requirements":#requirements
* "Installation":#installation
* "Tag":#tag
* "Example":#example
* "Styling":#styling
* "Author":#author
* "Licence":#licence

h2(#requirements). Plugin requirements

oui_instagram’s minimum requirements:

* Textpattern 4.5+
* "Embed":https://github.com/oscarotero/Embed

h2(#installation). Installation

# Download "Embed":https://github.com/oscarotero/Embed/releases by Oscar Otero, rename the src folder to embed and paste it in your *textpattern/vendors* folder;
# paste the content of the plugin file under the *Admin > Plugins*, upload it and install.

h2(#tag). Tag

h3. oui_embed

Single tag use to embed your stuff.

bc. <txp:oui_embed />

h4. Attributes 

h5. Required

* @url="…"@ - _Default: unset_ - The url from which you want to get any information.

h5. Optional

* @class="…"@ – _Default: oui_instagram_images_ - The css class to apply to the HTML tag assigned to @wraptag@.
* @info="…"@ – _Default: code_ - The information to retrieve from the url feed. Valid values are _title, description, url, type, tags, images, image, imageWidth, imageHeight, code, width, height, aspectRatio, authorName, authorUrl, providerName, providerUrl, providerIcons, providerIcon, publishedDate_ ("More informations":https://github.com/oscarotero/Embed).
* @label="…"@ – _Default: unset_ - The label used to entitled the generated content.
* @labeltag="…"@ - _Default: unset_ - The HTML tag used around the value assigned to @label@.
* @responsive="…"@ - _Default: unset_ - Uses a @div@ as wrapper and adds a @padding-top@ to it according to content ratio. You still need to "set the rest of the css rules":#styling.  
* @wraptag="…"@ - _Default: ul_ - The HTML tag to use around the generated content.

h2(#example). Example

bc. <txp:oui_embed url="https://www.youtube.com/watch?v=PP1xn5wHtxE" />

h2(#styling). Styling

h3. Responsive use

To make your embed content fit the width of its container and keep its ratio, use the @responsive@ attribute…

bc. <txp:oui_embed url="https://www.youtube.com/watch?v=PP1xn5wHtxE" responsive="1" />

…and add the following css rules to your stylesheet.

bc.. .oui_embed // or your wrap class // {
	position: relative;
	width: 100%;
	
	iframe { 
		position: absolute;
		top: 0;
		left: 0;
		width: 100%;
		height: 100%;
	}	
}

h2(#author). Author

"Nicolas Morand":https://github.com/NicolasGraph

h2(#licence). Licence

This plugin is distributed under "MIT license":https://opensource.org/licenses/MIT.

# --- END PLUGIN HELP ---
<?php
}

# --- BEGIN PLUGIN CODE ---

use Embed\Embed;

if (class_exists('\Textpattern\Tag\Registry')) {
    // Register Textpattern tags for TXP 4.6+.
    Txp::get('\Textpattern\Tag\Registry')
        ->register('oui_embed');
}

function oui_embed($atts) {
	global $txpcfg;

    extract(lAtts(array(
        'url'        => '',
        'info'       => 'code',
        'label'      => '',
        'labeltag'   => '',
        'wraptag'    => '',
        'class'      => '',
        'responsive' => ''
    ),$atts));

	$path = $txpcfg['txpath'] . "/vendors/embed/src/autoloader.php";
	include($path);

	$embed = Embed::create($url);
	$data = $embed->$info;

	$ratio = number_format($embed->aspectRatio).'%';

	if ($responsive) {
		$out = '<div class="oui_embed '.$class.'" style="padding-top:'.$ratio.'">'.$data.'</div>';
	} else {
		$out = ($wraptag) ? doTag($data, $wraptag, $class) : $data;
	};

	return $out;
}

# --- END PLUGIN CODE ---

?>