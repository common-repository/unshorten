<?php /*

**************************************************************************

Plugin Name:  UnShorten
Plugin URI:   http://www.jonrogers.co.uk/unshorten-wordpress-plugin/
Version:      0.1
Description:  UnShorten urls from services like bit.ly so your readers can see the full link - this is especially useful if you put tweets on your blog!
Author:       Jon Rogers
Author URI:   http://www.jonrogers.co.uk/

**************************************************************************/

/*	Copyright 2009 Jon Rogers. Based on code by Mark Jaquith.

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

function unShorten($matches){
	$link_url = $matches[2].'//'.$matches[3];
	if ( $matches[5] == $link_url ) {
		$response = wp_remote_retrieve_body( wp_remote_get( "http://therealurl.appspot.com?format=json&url=".urlencode( $matches[5] ) ) );
		if ($response = json_decode( $response, true )) {
			if ( $response['url'] != "" ) 
				$matches[5] = $response['url'];
		} 
	}
	return '<a href="' . $matches[2] . '//' . $matches[3] . '"' . $matches[1] . $matches[4] . '>' . $matches[5] . '</a>';
}

function find_links($text) {
	$pattern = '/<a (.*?)href="(.*?)\/\/(.*?)"(.*?)>(.*?)<\/a>/i';
	$text = preg_replace_callback($pattern,'unShorten',$text);

	return $text;
}

// filters have high priority to make sure that any markup plugins like Textile or Markdown have already created the HTML links
add_filter('the_content', 'find_links', 999);
add_filter('the_excerpt', 'find_links', 999);

?>
