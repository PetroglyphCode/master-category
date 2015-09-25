<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(PATH_MOD.'channel/mod.channel.php');

class Master_category_ext extends Channel {
	
	 var $name       = 'Master Category';
    var $version        = '1.0';
    var $description    = 'Compares entries against a master category & filters out non-matches.';
    var $settings_exist = 'n';
    var $docs_url       = '';
    var $settings        = array();
	
	//
	
	 function __construct($settings='')
    {	
    	$this->EE = &get_instance();
    	$this->settings = $settings;
    	
           }

    public function master_entries($obj, $query_result)
    {
    	
    	$master_category  = NULL;
    	
        // we need to be sure that our entries are a part of this category
 
        $master_category = $this->EE->TMPL->fetch_param('master');
        
        $matched_results = Array();
        		
			
		if (strlen($master_category)> 0 && ($master_category != NULL)){
		
		//Namespace query result objects as their entry_id, for much much easier removal.

			$results = Array();
			
			foreach ($query_result as $result_obj)
	        {
		        
		        $results[$result_obj['entry_id']] = $result_obj;
	        }
	
			$count=1;
			
			foreach ($query_result as $entry) 
			{
									
					
					foreach  ($obj->categories[$entry['entry_id']] as $category ){
						
						$match = 0;
						
						// Look at the category ID to see if it's the Master we are seeking
						
						
						if($category[0] == $master_category){
							
							//Match!
						
							$match = 1;
							
							break;
						}
						
					}
					if ($match ==0) {
						//no match" - remove it from the result array
					
							unset($results[$entry['entry_id']]);
						}
					
			}
						
			//re-loop $results to non-namespaced format for EE's pleasure
			
			foreach ($results as $result){
				
				$matched_results[] = $result;
				
			}
			
			return $matched_results; 
			
		} else { //No Master Category involvement
			
			return $query_result;       
			
		}
     
    }
	/**
	 * Activate Extension
	 *
	 * This function enters the extension into the exp_extensions table
	 *
	 * @see https://ellislab.com/codeigniter/user-guide/database/index.html for
	 * more information on the db class.
	 *
	 * @return void
	 */
	function activate_extension()
	{
	    $this->settings = array(
	        'max_link_length'   => 18,
	        'truncate_cp_links' => 'no',
	        'use_in_forum'      => 'no'
	    );
	
	
	    $data = array(
	        'class'     => __CLASS__,
	        'method'    => 'master_entries',
	        'hook'      => 'channel_entries_query_result',
	        'settings'  => '',
	        'priority'  => 10,
	        'version'   => $this->version,
	        'enabled'   => 'y'
	    );
	
	    ee()->db->insert('extensions', $data);
	}
	/**
	 * Update Extension
	 *
	 * This function performs any necessary db updates when the extension
	 * page is visited
	 *
	 * @return  mixed   void on update / false if none
	 */
	 
	function update_extension($current = '')
	{
	    if ($current == '' OR $current == $this->version)
	    {
	        return FALSE;
	    }
	
	    if ($current < '1.0')
	    {
	        // Update to version 1.0
	    }
	
	    ee()->db->where('class', __CLASS__);
	    ee()->db->update(
	                'extensions',
	                array('version' => $this->version)
	    );
	}
	/**
	 * Disable Extension
	 *
	 * This method removes information from the exp_extensions table
	 *
	 * @return void
	 */
	function disable_extension()
	{
	    ee()->db->where('class', __CLASS__);
	    ee()->db->delete('extensions');
	}

   }
