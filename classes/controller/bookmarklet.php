<?php defined('SYSPATH') OR die('No direct access allowed.');

/**
 * Bookmarklet Controller
 *
 * PHP version 5
 * LICENSE: This source file is subject to GPLv3 license 
 * that is available through the world-wide-web at the following URI:
 * http://www.gnu.org/copyleft/gpl.html
 * @author	   Ushahidi Team <team@ushahidi.com> 
 * @package	   SwiftRiver - http://github.com/ushahidi/Swiftriver_v2
 * @subpackage Controllers
 * @copyright  Ushahidi - http://www.ushahidi.com
 * @license	   http://www.gnu.org/copyleft/gpl.html GNU General Public License v3 (GPLv3) 
 */
class Controller_Bookmarklet extends Controller_User {
	
	public function action_index()
	{
		include_once Kohana::find_file('vendor', 'php-readability/Readability');
		$this->template->content = View::factory('bookmarklet');
		
		$url = $this->request->query('url');
		$html = file_get_contents($url);

		if (function_exists('tidy_parse_string')) {
			exit;
			$tidy = tidy_parse_string($html, array(), 'UTF8');
			$tidy->cleanRepair();
			$html = $tidy->value;
		}
		
		// give it to Readability
		$readability = new Readability($html, $url);
		$readability->debug = false;
		$readability->convertLinksToFootnotes = false;
		if ($result = $readability->init()) 
		{
			$content = $readability->getContent()->innerHTML;
			// if we've got Tidy, let's clean it up for output
			if (function_exists('tidy_parse_string')) {
				$tidy = tidy_parse_string($content, array('indent'=>true, 'show-body-only' => true), 'UTF8');
				$tidy->cleanRepair();
				$content = $tidy->value;
			}
			
			$drop = array(
				'channel' => 'web',
				'identity_orig_id' => parse_url($url, PHP_URL_HOST),
				'identity_username' => parse_url($url, PHP_URL_HOST),
				'identity_name' => parse_url($url, PHP_URL_HOST),
				'identity_avatar' => NULL,
				'droplet_orig_id' => md5($url),
				'droplet_type' => 'original',
				'droplet_title' => $readability->getTitle()->textContent,
				'droplet_content' => $content,
				'droplet_raw' => $content,
				'droplet_date_pub' => gmdate("Y-m-d H:i:s"),
				'links' => array(
					array(
						'url' => $url,
						'original_url' => TRUE
					)
				)
			);
			
			$this->template->content->drop = Swiftriver_Dropletqueue::create_drop($drop);
			$this->template->content->url = $url;
			$this->template->footer = NULL;
			$this->template->header->show_nav = FALSE;
		} 
		else 
		{
			echo 'Looks like we couldn\'t find the content. :(';
		}
	}

}