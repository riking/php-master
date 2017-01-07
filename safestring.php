<?php
namespace kyork;
\kyork\libraryfile();

class HtmlSafeString {
	public $contents;
	public function __construct($str) {
		$this->contents = $str;
	}
	public function __tostring() {
		return $this->contents;
	}
}

/**
 * html performs html escaping on str and returns the string as a \kyork\HtmlSafeString.
 * Note that this function does NOT escape quotes - use \kyork\htmlattr for attribute values.
 * @param HtmlSafeString|string $str - string
 * @return HtmlSafeString - escaped string
 */
function html($str) {
	if ($str instanceof HtmlSafeString)
		return $str;
	return new HtmlSafeString(\htmlspecialchars($str, \ENT_NOQUOTES | \ENT_HTML5, 'UTF-8'));
}
/**
 * Alias for \kyork\html.
 * @param HtmlSafeString|string $str - string
 * @return HtmlSafeString - escaped string
 */
function h($str) { return \kyork\html($str); }
/**
 * html_safe marks the string as already being escaped HTML.
 * @param HtmlSafeString|string $str - string
 * @return HtmlSafeString - string marked as safe
 */
function html_safe($str) {
	if ($str instanceof HtmlSafeString)
		return $str;
	return new HtmlSafeString($str);
}
/**
 * htmlattr performs html escaping on str in the context of a HTML parameter and returns the string as a \kyork\HtmlSafeString.
 * @param HtmlSafeString|string $str - string
 * @return HtmlSafeString - escaped string
 */
function htmlattr($str) {
	if ($str instanceof HtmlSafeString)
		return $str;
	return new HtmlSafeString(\htmlspecialchars($str, \ENT_QUOTES | \ENT_HTML5, 'UTF-8'));
}
/**
 * Alias for \kyork\htmlattr.
 * @param HtmlSafeString|string $str - string
 * @return HtmlSafeString - escaped string
 */
function attr($str) { return \kyork\htmlattr($str); }

/**
 * @param string...|array $str_array An array of strings or HtmlSafeStrings.
 * @return HtmlSafeString escaped version of all strings joined together
 */
function htmljoin($str_array) {
    $ary = null;
    if (is_array($str_array))
        $ary = $str_array;
    else
        $ary = \func_get_args();

	$filterStrs = array_filter($ary, function($val) {
		if ($val instanceof HtmlSafeString)
			return $val;
		return \kyork\html($val);
	});
    return new HtmlSafeString(\implode("", $filterStrs));
}
