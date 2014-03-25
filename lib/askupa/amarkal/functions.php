<?php

namespace TWITTERFEED;

/**
 * Is full version
 * return $yes if this is the full version
 * return $no otherwise
 */
if(!function_exists(_is_full_version)) {
	function _is_full_version($yes, $no) {
		return (PLUGIN_VERSION_TYPE === 'Full' ? $yes : $no);
	}
}