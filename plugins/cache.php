<?php
// Copyright 2018 Jookia <166291@gmail.com>
// Cache plugin is licensed under MIT but includes GPL3 functions

/**
	Low-level cache API
	This API accesses a key/value directory tree.

	Keys are sha256 hashes.
	Hashes are sorted in to directories by their first two characters,
	with their filenames being the rest of the hash.
	No files modified earlier than the 'last_updated' file are used.

	There's 5 primitive functions:

	- cache_hash: This function hashes a key for use by other functions.
	- cache_filepath: Generates the full file path for cached data.
	- cache_read: Reads data from the filesystem, identified by a hash.
	- cache_write: Writes data to the filesystem, identified by a hash.
	- cache_expire: Update last_updated to mark the cache as stale.

	There's 1 global variable:
	- cache_lastupdated: The time of last_updated.

	Before running these functions, make sure this file and its
	parent directory exist: PLX_ROOT."0_sources/last_updated.txt"
	The parent directory must also have write permissions.
	Otherwise caching won't happen.
*/

function cache_hash($key) {
	return hash("sha256", serialize($key));
}

function cache_filepath($hash) {
	$hashHead = substr($hash, 0, 2);
	$hashRest = substr($hash, 2);
	return PLX_ROOT."tmp/plxcache/" . $hashHead . "/" . $hashRest;
}

function cache_read($hash) {
	global $cache_lastupdated;
	$path = cache_filepath($hash);
	$cached_mtime = @filemtime($path);
	if($cached_mtime AND $cache_lastupdated < $cached_mtime)
		return file_get_contents(cache_filepath($hash));
	else
		return FALSE;
}

function cache_write($hash, $data) {
	$path = cache_filepath($hash);
	mkdir(dirname($path));
	return (file_put_contents($path . ".tmp", $data) AND
	        rename($path . ".tmp", $path));
}

function cache_expire() {
	header("PluXml-Cache-Status: Expired");
	touch(PLX_ROOT."0_sources/last_updated.txt");
}

$cache_lastupdated = @filemtime(PLX_ROOT."0_sources/last_updated.txt");

/**
	PluXml caching hooks
	These index.php hooks intercept and add caching to PluXml.

	The cache is added by hooking before PluXml does anything.
	The only thing done before is initializing PHP sessions.
	When there's no cached page, PluXml runs as normal.
	Before sending HTML to the client the HTML is cached.

	Three headers are added to aid debugging:

	PluXml-Cache-Active: If caching is enabled and why not.
	PluXml-Cache-Status: If the cached was loaded or created.
	PluXml-Cache-Hash: The cache hash of the cached page.

	This code emulates portions of PluXml when it doesn't run.
	This is to ensure that dynamic features (cookies, CAPCHA) work.
	Update this code when changing PluXml code or adding features.
**/

// Global variables shared between cache_starthook and cache_endhook
$cache_active = TRUE;
$cache_pagehash = FALSE;
$validLangsPath = PLX_ROOT."tmp/plxcache/valid_langs";

function cache_starthook() {
	global $cache_active;
	global $cache_pagehash;
	global $cache_lastupdated;

	if(!$cache_lastupdated) {
		// Can't work the cache without last_updated
		$cache_active = FALSE;
		header("PluXml-Cache-Active: No, unable to read last_updated");
		return;
	} else if(!empty($_POST)) {
		// Don't cache POST requests
		$cache_active = FALSE;
		header("PluXml-Cache-Active: No, POST request");
		return;
	} else if(isset($_SESSION['msgcom']) AND !empty($_SESSION['msgcom'])) {
		// Don't cache if there's a CAPCHA error
		$cache_active = FALSE;
		header("PluXml-Cache-Active: No, msgcom set");
		return;
	} else {
		$cache_active = TRUE;
		header("PluXml-Cache-Active: Yes");
	}

	// Avoid sending the page if it hasn't been updated
	if($cache_lastupdated <= strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
		header('HTTP/1.0 304 Not Modified');
		exit;
	}

	$query_cleaned = str_replace('^index.php?', '', $_SERVER['QUERY_STRING']);
	$query_cleaned = ltrim($query_cleaned, '/');
	$query_parts = explode('/', $query_cleaned);

	global $validLangsPath;
	$validLangs = explode(',', file_get_contents($validLangsPath));
	$default_lang = "en";

	/*
	  Language detection works like this:
	  1. Choose the language in the URL
	  2. Otherwise choose the language in the user's session
	  3. Otherwise choose the plxMyMultiLingue cookie
	  4. Otherwise choose the default language
	  5. Set the user's cookie to the language for 30 days
	  6. If the chosen language isn't valid, choose the default
	  (Step 5 and 6 would be reversed, but plxMyMultiLingue accidentally
	  sets its default_lang to the language chosen in step 5 by
	  calling its parent constructor with the wrong argument.)
	  The session contains the user's last selected language.
	  The cookie contains the language of the user's last visited page.
	*/
	if(preg_match('/^[a-zA-Z]{2}$/', $query_parts[0], $capture))
		$language = $query_parts[0];
	else if($_SESSION["lang"])
		$language = $_SESSION["lang"];
	else if($_COOKIE["plxMyMultiLingue"])
		$language = $_COOKIE["plxMyMultiLingue"];
	else
		$language = $default_lang;
	$cookie_language = $language;
	if($validLangs[0] != "" AND !in_array($language, $validLangs))
		$language = $default_lang;

	/*
	  HD comic displaying work like this:
	  1. User's session is marked as low-resolution
	  2. PluXml displays comics in low-resolution to user
	  3. User goes to comic URL ending with &option=hd
	  4. PluXml marks user's session cookie as hi-resolution
	  5. PluXml displays comics in high-resolution to user
	  Going back to low-resolution works similiarly.
	  Comics are cached now, so we have to handle this logic.
	*/
	if($_GET['option'] == 'hd')
		$_SESSION['SessionMemory'] = "KeepHD";
	else if($_GET['option'] == 'low')
		$_SESSION['SessionMemory'] = "RemoveHD";
	$HD = ($_SESSION['SessionMemory'] == "KeepHD");

	// HD is a cache key now, so remove redundant &option= from the page URL
	// This saves duplicating cache entries for each comic
	$query_cleaned = str_replace('&option=hd',  '', $query_cleaned);
	$query_cleaned = str_replace('&option=low', '', $query_cleaned);

	$cache_pagekey = array(
		"query" => $query_cleaned,
		"lang"  => $language,
		"hd"    => $HD,
		"host"  => getRacine(),
		"script" => $_SERVER["SCRIPT_NAME"]
	);
	$cache_pagehash = cache_hash($cache_pagekey);
	$cache_html = cache_read($cache_pagehash);

	if($cache_html) {
		if (strpos($_SERVER["SCRIPT_NAME"], "feed.php"))
			header('Content-Type: application/rss+xml; charset='.PLX_CHARSET);
		else
			header('Content-Type: text/html; charset='.PLX_CHARSET);
		header("Last-Modified: " . gmdate('D, d M Y H:i:s T', $cache_lastupdated));
		header("PluXml-Cache-Status: Hit");
		header("PluXml-Cache-Hash: " . $cache_pagehash);
		setcookie("plxMyMultiLingue", $cookie_language, time()+3600*24*30);
		// Handle some CAPCHA logic since PluXML no longer does it
		$fixed_html = capcha_updateHTML($cache_html);
		print($fixed_html);
		exit;
	}
}

function cache_endhook($output) {
	global $cache_active;
	global $cache_pagehash;
	if($cache_active AND cache_write($cache_pagehash, $output)) {
		header("PluXml-Cache-Status: Create");
		header("PluXml-Cache-Hash: " . $cache_pagehash);
		// Update the valid languages for the cache lookup to use
		global $validLangsPath;
		$plxMotor = plxMotor::getInstance();
		$langPlugin = $plxMotor->plxPlugins->aPlugins['plxMyMultiLingue'];

		if(defined('PLX_MYMULTILINGUE')) {
			$validLangs = PLX_MYMULTILINGUE;
		} else {
			$validLangs = array($plxPlugin->default_lang);
		}

		file_put_contents($validLangsPath + ".tmp", $validLangs);
		rename($validLangsPath + ".tmp", $validLangsPath);
	}
}

/**
	plxMyCapchaImage emulation code
	This code fixes cached CAPCHA tokens to match user sessions

	capcha_getCode is copied verbatim from plxMyCapchaImage/plxMyCapchaImage.php
	capcha_updateHTML includes a lot of code from the same file.
	The original author of this code is Stéphane F.
	It's unclear what the license of the code is, but I assume GPL3.
**/

function capcha_updateHTML($html) {
	// Matching tags on pages containing user input is a bad idea
	// But I assume PluXml sanitized its output
	// And who really cares if a CAPCHA token is leaked?
	$tag_text = '<input type="hidden" name="capcha_token" value="';
	$tag_len = strlen($tag_text);
	$tag_pos = strpos($html, $tag_text);
	if($tag_pos) {
		$_SESSION['capcha_token'] = sha1(uniqid(rand(), true));
		$_SESSION['capcha'] = capcha_getCode(4);
		$token_len = strlen($_SESSION['capcha_token']);
		$fixed_html  = substr($html, 0, $tag_pos + $tag_len);
		$fixed_html .= $_SESSION['capcha_token'];
		$fixed_html .= substr($html, $tag_pos + $tag_len + $token_len);
		return $fixed_html;
	} else {
		return $html;
	}
}

function capcha_getCode($length) {
	$chars = '1234568abcdefghjkpstuvxyz'; // Certains caractères ont été enlevés car ils prêtent à confusion
	$rand_str = '';
	for ($i=0; $i<$length; $i++) {
		$rand_str .= $chars{ mt_rand( 0, strlen($chars)-1 ) };
	}
	return strtolower($rand_str);
}

/**
	pluXml site root emulation code
	This code fixes the problem of serving multiple hosts with the same cache

	getRacine is copied mostly verbatim from core/lib/class.plx.utils.php
	By using this as a key the website won't serve content for HTTP on HTTPS.

	The original author of this code is Florent MONTHEL and Stéphane F.
	It's unclear what the license of the code is, but I assume GPL3.
**/

function getRacine() {
        $protocol = (!empty($_SERVER['HTTPS']) AND strtolower($_SERVER['HTTPS']) == 'on') || (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) AND strtolower($_SERVER['HTTP_X_FORWARDED_PROTO']) == 'https' )?        'https://' : "http://";
        $servername = $_SERVER['HTTP_HOST'];
        $serverport = (preg_match('/:[0-9]+/', $servername) OR $_SERVER['SERVER_PORT'])=='80' ? '' : ':'.$_SERVER['SERVER_PORT'];
        $dirname = preg_replace('/\/(core|plugins)\/(.*)/', '', dirname($_SERVER['SCRIPT_NAME']));
        $racine = rtrim($protocol.$servername.$serverport.$dirname, '/\\').'/';
        return $racine;
}

?>
