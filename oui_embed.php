<?php

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

            if (is_array($data)) {
            	implode('', $data);
            }

            if (($type === 'code') && $responsive) {
                // Add padding-top if responsive attribute is set
                $ratio = number_format($embed->aspectRatio, 2, '.', '').'%';
                $out = (($label) ? doLabel($label, $labeltag) : '').\n
                      .'<div class="oui_embed '.$class.'" style="padding-top:'.$ratio.'">'.\n
                      .'    '.$data.\n
                      .'</div>'.\n;
            } else {
                $out = (($label) ? doLabel($label, $labeltag) : '').\n
                      .(($wraptag) ? doWrap($data, $wraptag, $class) : $data);
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
        $cache = fopen($cachefile,'w+');
        fwrite($cache,$out);
        fclose($cache);
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
