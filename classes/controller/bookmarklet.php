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
		if ( ! $this->owner)
		{
			$this->request->redirect($this->dashboard_url);
		}
		
		// Set the current page
		$this->active = 'bookmarklet';
		$this->sub_content = View::factory('bookmark');
		
		$site_url = urlencode(URL::base(TRUE, FALSE));
		$js_url = urlencode(URL::site("plugins/bookmarklet/media/js/bookmarklet.js", TRUE, TRUE));
		$this->sub_content->bookmarklet = "javascript:(function()%7Bswiftriver_site_url='".$site_url."';ISRIL_H='b7f4';ISRIL_SCRIPT=document.createElement('SCRIPT');ISRIL_SCRIPT.type='text/javascript';ISRIL_SCRIPT.src='".$js_url."';document.getElementsByTagName('head')%5B0%5D.appendChild(ISRIL_SCRIPT)%7D)();";
		
		
	}
	
	public function action_bookmarklet()
	{
		include_once Kohana::find_file('vendor', 'php-readability/Readability');
		$this->template->header->js .= Html::script("themes/default/media/js/drops.js");
		$this->template->content = View::factory('bookmarklet');
		$this->template->content->drop = NULL;
		$this->template->content->base_url = $this->request->url();
		$this->template->footer = NULL;
		$this->template->header->show_nav = FALSE;
		
		
		try 
		{
			$url = $this->request->query('url');
			$html = file_get_contents($url);

			if (function_exists('tidy_parse_string')) {
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

				$drop = Swiftriver_Dropletqueue::create_drop($drop);
				$drops_array = array($drop);
				Model_Droplet::populate_buckets($drops_array);
				$this->template->content->drop = json_encode($drops_array[0]);
			}
		}
		catch (Exception $e)
		{
			Kohana::$log->add(Log::ERROR, "Bookmarket: error extracting content. :error",
			    array(":error" => $e->getMessage()));
		}
	}
	
	/**
	 * XHR endpoint for fetching droplets
	 */
	public function action_drops()
	{
		$this->template = "";
		$this->auto_render = FALSE;

		switch ($this->request->method())
		{			
			case "PUT":
				// No anonymous actions
				if ($this->anonymous)
				{
					throw new HTTP_Exception_403();
				}
			
				$droplet_array = json_decode($this->request->body(), TRUE);
				$droplet_id = intval($this->request->param('id', 0));
				$droplet_orm = ORM::factory('droplet', $droplet_id);
				$droplet_orm->update_from_array($droplet_array, $this->user->id);
			break;
		}
	}

}