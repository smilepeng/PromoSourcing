<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
* @name MY_Pagination.php
* @version 1.0
* @author Joost van Veen www.accentinteractive.nl
* @created: Sun Jul 27 16:27:26 GMT 2008 16:27:26
*
* Extends CI's pagination class (http://codeigniter.com/user_guide/libraries/pagination.html)
* It sets some variables for configuration of the pagination class dynamically,
* depending on the URI, so we don't have to substract the offset from the URI,
* or set $config['base_url'] and $config['uri_segment'] manually in the controller
*
* Here is what is set by this extension class:
* 1. $this->offset - the current offset
* 2. $this->uri_segment - the URI segment to be used for pagination
* 3. $this->base_url - the base url to be used for pagination
* (where $this refers to the pagination class)
*
* The way this works is simple:
* If there we use pagination, it must ALWAYS follow the following syntax and be
* located at the END of the URI:
* PAGINATION_SELECTOR/offset
*
* The PAGINATION_SELECTOR is a special string which we know will ONLY be in the
* URI when paging is set. Let's say the PAGINATION_SELECTOR is 'Page' (since most
* coders never use any capitals in the URI, most of the times any string with
* a single capital character in it will suffice).
*
* Example use (in controller):
* // Initialize pagination
* $config['total_rows'] = $this->db->count_all_results('my_table');
* $config['per_page'] = 10; // You'd best set this in a config file, but hey
* $this->pagination->initialize($config);
* $this->data['pagination'] = $this->pagination->create_links();
*
* // Retrieve paginated results, using the dynamically determined offset
* $this->db->limit($config['per_page'], $this->pagination->offset);
* $query = $this->db->get('my_table');
*
*/
class MY_Pagination extends CI_Pagination {

    var $offset = 0;
    var $pagination_selector = 'page';

	function __construct()
	{
		parent::__construct();
		
		log_message('debug', "MY_Pagination Class Initialized");

		$this->_set_pagination_offset();
		
		
	}

    /**
     * Set dynamic pagination variables in $CI->data['pagvars']
     *
     */
    function _set_pagination_offset()
    {

        // Instantiate the CI super object so we have access to the uri class
        $CI =& get_instance();

		// parse uri
		preg_match('/\/'.$this->pagination_selector.'(\/)?([0-9]+)?$/i', $CI->uri->uri_string(), $matches);

        // Store pagination offset if it is set
        if ($matches) {

			// set uri based on matches
			$uri = substr($CI->uri->uri_string(), 0, strrpos($CI->uri->uri_string(), $matches[0]));

            // Get the segment offset for the pagination selector
            $segments = $CI->uri->segment_array();

            // Loop through segments to retrieve pagination offset
            foreach ($segments as $key => $value) {

                // Find the pagination_selector and work from there
                if ($value == $this->pagination_selector) {

                    // Store pagination offset
                    $this->offset = $CI->uri->segment($key + 1);

                    // Store pagination segment
                    $this->uri_segment = $key + 1;

                    // Set base url for paging. This only works if the
                    // pagination_selector and paging offset are AT THE END of
                    // the URI!
                    //$pos = strrpos($uri, $this->pagination_selector);
                    $this->base_url = $CI->config->item('base_url') . $uri . '/' . $this->pagination_selector;
                }

            }

        }
        else { // Pagination selector was not found in URI string. So offset is 0
            $this->offset = 0;
            $this->uri_segment = 0;
            $this->base_url = $CI->config->item('base_url') . $CI->uri->uri_string() . '/' . $this->pagination_selector;

        }

    }
	 
	/**
	 * Generate the pagination links
	 *
	 * @access	public
	 * @return	string
	 */
	function create_java_links()
	{
		$this->full_tag_open		= '<div class="pagination"><ul>';
		$this->full_tag_close		= '</ul></div>';
		$this->first_tag_open		= '<li>';
		$this->first_tag_close		= '</li>';
		$this->last_tag_open		= '<li>';
		$this->last_tag_close		= '</li>';
		$this->cur_tag_open			= '<li><span class="active">';
		$this->cur_tag_close		= '</span></li>';
		$this->next_tag_open		= '<li>';
		$this->next_tag_close		= '</li>';
		$this->prev_tag_open		= '<li>';
		$this->prev_tag_close		= '</li>';
		$this->num_tag_open			= '<li>';
		$this->num_tag_close		= '</li>';
		// If our item count or per-page total is zero there is no need to continue.
		if ($this->total_rows == 0 OR $this->per_page == 0)
		{
			return '';
		}

		// Calculate the total number of pages
		$num_pages = ceil($this->total_rows / $this->per_page);

		// Is there only one page? Hm... nothing more to do here then.
		if ($num_pages == 1)
		{
			return '';
		}

		// Set the base page index for starting page number
		if ($this->use_page_numbers)
		{
			$base_page = 1;
		}
		else
		{
			$base_page = 0;
		}

		// Determine the current page number.
		$CI =& get_instance();
		
		// Prep the current page - no funny business!
		$this->cur_page = (int) $this->cur_page;
				
				
		// Set current page to 1 if using page numbers instead of offset
		if ($this->use_page_numbers AND $this->cur_page == 0)
		{
			$this->cur_page = $base_page;
		}

		$this->num_links = (int)$this->num_links;

		if ($this->num_links < 1)
		{
			show_error('Your number of links must be a positive number.');
		}

		if ( ! is_numeric($this->cur_page))
		{
			$this->cur_page = $base_page;
		}

		// Is the page number beyond the result range?
		// If so we show the last page
		if ($this->use_page_numbers)
		{
			if ($this->cur_page > $num_pages)
			{
				$this->cur_page = $num_pages;
			}
		}
		else
		{
			if ($this->cur_page > $this->total_rows)
			{
				$this->cur_page = ($num_pages - 1) * $this->per_page;
			}
		}

		$uri_page_number = $this->cur_page;
		
		if ( ! $this->use_page_numbers)
		{
			$this->cur_page = floor(($this->cur_page/$this->per_page) + 1);
		}

		// Calculate the start and end numbers. These determine
		// which number to start and end the digit links with
		$start = (($this->cur_page - $this->num_links) > 0) ? $this->cur_page - ($this->num_links - 1) : 1;
		$end   = (($this->cur_page + $this->num_links) < $num_pages) ? $this->cur_page + $this->num_links : $num_pages;


		// And here we go...
		$output = '';

		// Render the "First" link
		if  ($this->first_link !== FALSE AND $this->cur_page > ($this->num_links + 1))
		{
			$first_url = ($this->first_url == '') ? sprintf($this->base_url, 1) : $this->first_url;
			$output .= $this->first_tag_open.'<a '.$this->anchor_class.'href="'.$first_url.'">'.$this->first_link.'</a>'.$this->first_tag_close;
		}

		// Render the "previous" link
		if  ($this->prev_link !== FALSE AND $this->cur_page != 1)
		{
			if ($this->use_page_numbers)
			{
				$i = $uri_page_number - 1;
			}
			else
			{
				$i = $uri_page_number - $this->per_page;
			}

			if ($i == 0 && $this->first_url != '')
			{
				$output .= $this->prev_tag_open.'<a '.$this->anchor_class.'href="'.$this->first_url.'">'.$this->prev_link.'</a>'.$this->prev_tag_close;
			}
			else
			{
				$i = ($i == 0) ? '' : $this->prefix.$i.$this->suffix;
				$output .= $this->prev_tag_open.'<a '.$this->anchor_class.'href="'.sprintf($this->base_url,$i).'">'.$this->prev_link.'</a>'.$this->prev_tag_close;
			}

		}

		// Render the pages
		if ($this->display_pages !== FALSE)
		{
			// Write the digit links
			for ($loop = $start -1; $loop <= $end; $loop++)
			{
				if ($this->use_page_numbers)
				{
					$i = $loop;
				}
				else
				{
					$i = ($loop * $this->per_page) - $this->per_page;
				}

				if ($i >= $base_page)
				{
					if ($this->cur_page == $loop)
					{
						$output .= $this->cur_tag_open.'<a '.$this->anchor_class.'>'.$loop.'</a>'.$this->cur_tag_close; // Current page
					}
					else
					{
						$n = ($i == $base_page) ? '' : $i;

						if ($n == '' && $this->first_url != '')
						{
							$output .= $this->num_tag_open.'<a '.$this->anchor_class.'href="'.$this->first_url.'">'.$loop.'</a>'.$this->num_tag_close;
						}
						else
						{
							$n = ($n == '') ? '' : $this->prefix.$n.$this->suffix;

							$output .= $this->num_tag_open.'<a '.$this->anchor_class.'href="'.sprintf($this->base_url,$n).'">'.$loop.'</a>'.$this->num_tag_close;
						}
					}
				}
			}
		}

		// Render the "next" link
		if ($this->next_link !== FALSE AND $this->cur_page < $num_pages)
		{
			if ($this->use_page_numbers)
			{
				$i = $this->cur_page + 1;
			}
			else
			{
				$i = ($this->cur_page * $this->per_page);
			}

			$output .= $this->next_tag_open.'<a '.$this->anchor_class.'href="'. sprintf($this->base_url, $i).'">'.$this->next_link.'</a>'.$this->next_tag_close;
		}
   
		// Render the "Last" link
		if ($this->last_link !== FALSE AND ($this->cur_page + $this->num_links) < $num_pages)
		{
			if ($this->use_page_numbers)
			{
				$i = $num_pages;
			}
			else
			{
				$i = (($num_pages * $this->per_page) - $this->per_page);
			}
			$output .= $this->last_tag_open.'<a '.$this->anchor_class.'href="'.sprintf($this->base_url, $i).'">'.$this->last_link.'</a>'.$this->last_tag_close;
		}

		// Kill double slashes.  Note: Sometimes we can end up with a double slash
		// in the penultimate link so we'll kill all double slashes.
		$output = preg_replace("#([^:])//+#", "\\1/", $output);

		
		// Add the wrapper HTML if exists
		$output = $this->full_tag_open.$output.$this->full_tag_close;
		
		return $output;
	}
}
?>