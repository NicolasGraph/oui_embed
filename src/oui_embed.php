<?php

$plugin['name'] = 'oui_embed';

$plugin['allow_html_help'] = 0;

$plugin['version'] = '0.3.3';
$plugin['author'] = 'Nicolas Morand';
$plugin['author_uri'] = 'https://github.com/NicolasGraph';
$plugin['description'] = 'Embed everything';

$plugin['order'] = 5;

$plugin['type'] = 1;

// Plugin 'flags' signal the presence of optional capabilities to the core plugin loader.
// Use an appropriately OR-ed combination of these flags.
// The four high-order bits 0xf000 are available for this plugin's private use.
if (!defined('PLUGIN_HAS_PREFS')) define('PLUGIN_HAS_PREFS', 0x0001); // This plugin wants to receive "plugin_prefs.{$plugin['name']}" events
if (!defined('PLUGIN_LIFECYCLE_NOTIFY')) define('PLUGIN_LIFECYCLE_NOTIFY', 0x0002); // This plugin wants to receive "plugin_lifecycle.{$plugin['name']}" events

// $plugin['flags'] = PLUGIN_HAS_PREFS | PLUGIN_LIFECYCLE_NOTIFY;
$plugin['flags'] = PLUGIN_HAS_PREFS | PLUGIN_LIFECYCLE_NOTIFY;

// Plugin 'textpack' is optional. It provides i18n strings to be used in conjunction with gTxt().
$plugin['textpack'] = <<< EOT
#@public
#@language en-gb
oui_embed => Embed
oui_embed_providers_oembed_parameters => oembed parameters
oui_embed_providers_oembed_embedlykey => oembed.ly api key 
oui_embed_providers_oembed_iframelykey => Iframely api key
oui_embed_providers_html_maximages => HTML max images
oui_embed_providers_facebook_key => Facebook api key
oui_embed_providers_google_key => Google api Key
oui_embed_providers_soundcloud_key => SoundCloud api key
#@language fr-fr
oui_embed => Intégration
oui_embed_providers_oembed_parameters => Paramètres oembed
oui_embed_providers_oembed_embedlykey => Clé d'api oembed.ly 
oui_embed_providers_oembed_iframelykey => Clé d'api Iframely
oui_embed_providers_html_maximages => HTML max images
oui_embed_providers_facebook_key => Clé d'api Facebook
oui_embed_providers_google_key => Clé d'api Google
oui_embed_providers_soundcloud_key => Clé d'api SoundCloud
EOT;

if (!defined('txpinterface'))
    @include_once('zem_tpl.php');

if (0) {

?>
# --- BEGIN PLUGIN HELP ---
h1. oui_embed

"Embed":https://github.com/oscarotero/Embed everything…

_Get information from any web page (using oembed, opengraph, twitter-cards, scrapping the html, etc). It's compatible with any web service (youtube, vimeo, flickr, instagram, etc) and has adapters to some sites like (archive.org, github, facebook, etc)._

h2. Table of contents

* "Plugin requirements":#requirements
* "Installation":#installation
* "Tags":#tags
** "oui_embed":#oui_embed
** "oui_embed_info":#oui_embed_info
* "Examples":#examples
** "Single tag use":#single
** "Conatiner tag use":#container
* "Styling":#styling
* "Author":#author
* "Licence":#licence

h2(#requirements). Plugin requirements

oui_embed’s minimum requirements:

* Textpattern 4.5+
* "Embed":https://github.com/oscarotero/Embed

h2(#installation). Installation

# Download "Embed":https://github.com/oscarotero/Embed/releases by Oscar Otero, rename the _src_ folder to _embed_ and paste it in your *textpattern/vendors* folder;
# paste the content of the plugin file under the *Admin > Plugins*, upload it and install.

h2(#tags). Tags

h3(#oui_embed). oui_embed

Single or container tag use to embed your stuff.

bc. <txp:oui_embed url="…" />

h4. Attributes 

h5. Required

* @url="…"@ - _Default: unset_ - The url from which you want to get any information.

h5. Recommended

* @cache_time="…"@ — _Default: 0_ - Duration of the cache in seconds; during this time the result of the tags request will be stored in the Txp cache folder and read from there, avoiding too many external requests.

h5. Optional

h6. Single tag use

* @class="…"@ – _Default: unset_ - The css class to apply to the HTML tag assigned to @wraptag@.
* @type="…"@ – _Default: code_ - The information to retrieve from the url feed. Valid values are _title, description, url, type, tags, images, image, imageWidth, imageHeight, code, width, height, aspectRatio, authorName, authorUrl, providerName, providerUrl, providerIcons, providerIcon, publishedDate_ ("More informations":https://github.com/oscarotero/Embed).
* @label="…"@ – _Default: unset_ - The label used to entitled the generated content.
* @labeltag="…"@ - _Default: unset_ - The HTML tag used around the value assigned to @label@.
* @responsive="…"@ - _Default: unset_ - Uses a @div@ as wrapper if the @info@ attribute value is _code_ and adds a @padding-top@ to it according to content ratio. You still need to "set the rest of the css rules":#styling. Useful for video embed.  
* @wraptag="…"@ - _Default: ul_ - The HTML tag to use around the generated content.

h6. Container tag use

* @class="…"@ – _Default: unset_ - The css class to apply to the HTML tag assigned to @wraptag@.
* @label="…"@ – _Default: unset_ - The label used to entitled the generated content.
* @labeltag="…"@ - _Default: unset_ - The HTML tag used around the value assigned to @label@.
* @wraptag="…"@ - _Default: ul_ - The HTML tag to use around the generated content.

h5. Special

* @hash_key="…"@ - _Default: 194820_ - A number used to hash the 32-character reference assigned to your query and to generate a key for your cache file (you shouldn't need to change that).

h3(#oui_embed_info). oui_embed_info

Single tag to use in a @oui_embed@ container tag.

bc. <txp:oui_embed url="…">
    <txp:oui_embed_info info="…" />
</txp:oui_embed>

h4. Attributes 

See @oui_embed@ optional attributes for single tag use.

h2(#example). Example

h3(#single). Single tag use

bc. <txp:oui_embed url="https://youtu.be/aVARdqevPfI" />

returns: 

bc. <iframe width="480" height="270" src="https://www.youtube.com/embed/aVARdqevPfI?feature=oembed" frameborder="0" allowfullscreen></iframe>

h3(#container). Container tag use

bc.. <txp:oui_embed url="https://vimeo.com/157562955" label="Embed" labeltag="h1">
    <txp:oui_embed_info type="code" responsive="1" label="Video" labeltag="h2"   />
    <txp:oui_embed_info type="title" label="Title" labeltag="h2" wraptag="p" />
</txp:oui_embed>

p. returns:

bc.. <h1>Embed</h1>

<h2>Video</h2>
<div class="oui_embed " style="padding-top:56.25%">
    <iframe src="https://player.vimeo.com/video/157562955" width="1920" height="1080" frameborder="0" title="The Uses of Envy" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
</div>

<h2>Title</h2>
<p>The Uses of Envy</p>

h2(#styling). Styling

h3. Responsive use

To make your embed content fit the width of its container and keep its ratio, use the @responsive@ attribute…

bc. <txp:oui_embed url="…" responsive="1" />

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
        ->register('oui_embed')
        ->register('oui_embed_info');
}

if (txpinterface === 'admin') {
	add_privs('prefs.oui_embed', '1');
	add_privs('plugin_prefs.oui_embed', '1');
	register_callback('oui_embed_welcome', 'plugin_lifecycle.oui_embed');
	register_callback('oui_embed_install', 'prefs', null, 1);
}

function oui_embed_welcome($evt, $stp)
{
	switch ($stp) {
		case 'installed':
		case 'enabled':
			oui_embed_install();
			break;
		case 'deleted':
			if (function_exists('remove_pref')) {
				// 4.6 API
				remove_pref(null, 'oui_embed');
			} else {
				safe_delete('txp_prefs', "event='oui_embed'");
			}
			safe_delete('txp_lang', "name LIKE 'oui\_embed%'");
			break;
	}
}

function oui_embed_install()
{
	if (get_pref('oui_embed_providers_oembed_parameters', null) === null) {
		set_pref('oui_embed_providers_oembed_parameters', '', 'oui_embed', PREF_ADVANCED, 'text_input', 20);
	}
	if (get_pref('oui_embed_providers_oembed_embedlykey', null) === null) {
		set_pref('oui_embed_providers_oembed_embedlykey', '', 'oui_embed', PREF_ADVANCED, 'text_input', 20);
	}
	if (get_pref('oui_embed_providers_oembed_iframelykey', null) === null) {
		set_pref('oui_embed_providers_oembed_iframelykey', '', 'oui_embed', PREF_ADVANCED, 'text_input', 20);
	}
	if (get_pref('oui_embed_providers_html_maximages', null) === null) {
		set_pref('oui_embed_providers_html_maximages', '', 'oui_embed', PREF_ADVANCED, 'text_input', 20);
	}
	if (get_pref('oui_embed_providers_facebook_key', null) === null) {
		set_pref('oui_embed_providers_facebook_key', '', 'oui_embed', PREF_ADVANCED, 'text_input', 20);
	}
	if (get_pref('oui_embed_providers_google_key', null) === null) {
		set_pref('oui_embed_providers_google_key', '', 'oui_embed', PREF_ADVANCED, 'text_input', 20);
	}
	if (get_pref('oui_embed_providers_soundcloud_key', null) === null) {
		set_pref('oui_embed_providers_soundcloud_key', '', 'oui_embed', PREF_ADVANCED, 'text_input', 20);
	}
}

function oui_embed($atts, $thing=null) {
    global $prefs, $embed;

    extract(lAtts(array(
        'url'        => '',
        'type'       => 'code',
        'label'      => '',
        'labeltag'   => '',
        'wraptag'    => '',
        'class'      => '',
        'responsive' => '',
        'cache_time' => '0',
        'hash_key'   => '194820'
    ),$atts));

    // Prepare cache variables
    $keybase = md5($url.$type.$label.$labeltag.$wraptag.$class.$responsive.$thing);
    $hash = str_split($hash_key);
    $cachekey='';
    foreach ($hash as $hashskip) {
        $cachekey .= $keybase[$hashskip];
    }
    $cachedate = get_pref('cacheset');
    $cachefile = find_temp_dir().DS.'oui_embed_data_'.$cachekey;
    $cacheexists = file_exists($cachefile) ? true : false;

    $needcache = (($cache_time > 0) && ((!$cacheexists) || (time() - $cachedate) > $cache_time)) ? true : false;
    $readcache = (($cache_time > 0) && ($cacheexists)) ? true : false;

    // Cache_time is not set, or a new cache file is needed; throw a new request
    if ($needcache || $cache_time == 0) {
        
        $config = [
		    'providers' => [
		        'oembed' => [
		            'parameters'  => [get_pref('oui_embed_providers_oembed_parameters')],
		            'embedlyKey'  => get_pref('oui_embed_providers_oembed_embedlykey'),
		            'iframelyKey' => get_pref('oui_embed_providers_oembed_iframelykey')
		        ],
		        'html' => [
		            'maxImages' => get_pref('oui_embed_providers_html_maximages')
		        ],		        
		        'facebook' => [
		            'key' => get_pref('oui_embed_providers_facebook_key')
		        ],
		        'google' => [
		            'key' => get_pref('oui_embed_providers_google_key')
		        ],
		        'soundcloud' => [
		            'key' => get_pref('oui_embed_providers_soundcloud_key')
		        ]
		    ]
		];
        
        $embed = Embed::create($url, $config);
    
        // Container tag use
        if ($thing === null) {

            $data = $embed->$type;

            if (($type === 'code') && $responsive) {
                // Add padding-top if responsive attribute is set
                $ratio = number_format($embed->aspectRatio, 2, '.', '').'%';
                $out = (($label) ? doLabel($label, $labeltag) : '').\n
                      .'<div class="oui_embed '.$class.'" style="padding-top:'.$ratio.'">'.\n
                      .'    '.$data.\n
                      .'</div>'.\n;
            } else {
                $out = (($label) ? doLabel($label, $labeltag) : '').\n
                      .(($wraptag) ? doTag($data, $wraptag, $class) : $data);
            };

        // Single tag use
        } else {
            $data = parse($thing);
            $out = (($label) ? doLabel($label, $labeltag) : '').\n
                  .(($wraptag) ? doTag($data, $wraptag, $class) : $data);
        }
    }
    
    // Cache file is needed
    if ($needcache) {
        // Remove old cache files
        $oldcaches = glob($cachefile);
        if (!empty($oldcaches)) {
            foreach($oldcaches as $todel) {
                unlink($todel);
            }
        }
        // Time stamp and write the new cache files and return
        set_pref('cacheset', time(), 'oui_embed', PREF_HIDDEN, 'text_input'); 
        $rh = fopen($cachefile,'w+');
        fwrite($rh,$out);
        fclose($rh);
    }

    // Cache is on and file is found, get it!
    if ($readcache) {            
        $cache_out = file_get_contents($cachefile);
        return $cache_out;
    // No cache file :(       
    } else {            
        return $out;
    }
}

function oui_embed_info($atts) {
    global $embed;

    extract(lAtts(array(
        'type'       => '',
        'label'      => '',
        'labeltag'   => '',
        'wraptag'    => '',
        'class'      => '',
        'responsive' => ''
    ),$atts));

    $data = $embed->$type;    

    if (($type === 'code') && $responsive) {
        // Add padding-top if responsive attribute is set
        $ratio = number_format($embed->aspectRatio, 2, '.', '').'%';
        $out = (($label) ? doLabel($label, $labeltag) : '').\n
              .'<div class="oui_embed '.$class.'" style="padding-top:'.$ratio.'">'.\n
              .'    '.$data.\n
              .'</div>'.\n;
    } else {
        $out = (($label) ? doLabel($label, $labeltag) : '').\n
              .(($wraptag) ? doTag($data, $wraptag, $class) : $data);
    };

    return $out;
}

# --- END PLUGIN CODE ---

?>