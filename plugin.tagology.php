<?php

define ('TAGOLOGY_POST_TYPE', 'savorypost');
define ('TAGOLOGY_QUERY_VAR', 'svry');
define ('TAGOLOGY_LIVESEARCH_VAR', 'q');
define ('TAGOLOG_METAKEY_HASH', '_SAVORY_HASH');
define ('TAGOLOGY_METAKEY_URL', '_SAVORY_URL');
define ('TAGOLOGY_POST_TYPE_SLUG', 'url');
define ('TAGOLOGY_COAUTHOR_TAXONOMY', 'author');

// BEGIN auto-generated code

/*
Plugin Name: Tagology Plugin
Plugin URI: http://cuppster.com
Description: Wordpress Plugin to support Delicious-like tagging of URLs
Version: 0.1.1364
Author: Jason Cupp
Author URI: http://cuppster.com
License: Creative Commons Attribution 3.0 Unported License
*/

define('WP_TAGOLOGY_OPTS', 'wp_tagology');

if (!defined('PLUGIN_URL_tagology'))
	define('PLUGIN_URL_tagology', WP_PLUGIN_URL . '/tagology');
define('PLUGIN_PATH_tagology', WP_PLUGIN_DIR . '/tagology');

// create an instance of the plugin
global $tagology_plugin;
if (!$tagology_plugin)
	$tagology_plugin = new WpTagologyPlugin();

/*
* WpTagologyPlugin Class
*/
class WpTagologyPlugin {

	public $plugin_version = '0.1.1364';
	/*
	* constructor
	*/
	function WpTagologyPlugin() {
		if (method_exists($this, 'create_action'))
      //add_action('after_setup_theme', array(&$this, 'setup_theme_action'));
      $this->create_action();
		if (method_exists($this, 'custom_any_init'))
      add_action('init', array(&$this, 'custom_any_init'));
		add_action('init', array(&$this, is_admin() ? 'wp_init_admin' : 'wp_init_client'));
	}
	
	/*
	 * option name for storing data
	 */
	function get_optkey() {
		return 'WP_TAGOLOGY_OPTS';
	}
	
	/*
	* wp init admin action
	*/
	function wp_init_admin() {
		add_action('admin_menu', array(&$this, 'add_pages'));
		if (method_exists($this, 'custom_admin_init'))
			$this->custom_admin_init();
	}
	
	/*
	* wp init action
	*/
	function wp_init_client() {		
		/* no client filters*/

		if (method_exists($this, 'custom_client_init'))
			$this->custom_client_init();
	}


	/* 
	* get plugin options
	*/
	function get_options() {
		$set_options = array(
			'delete_bookmarks' => '',
			'import_bookmark_file' => '',
			'import_recent_bookmarks' => '',
			'is_multiuser' => '',
			'bookmarklet_text' => '',

		);
		$options = get_option(WP_TAGOLOGY_OPTS);	
		if (!empty($options)) {
			foreach ($options as $key => $option)
				$set_options[$key] = $option;
		}else{
			update_option(WP_TAGOLOGY_OPTS, $set_options);
		}
		return $set_options;
	}		
	
	/*
	* add config page
	*/
	function add_pages() {
		$page = add_options_page('Tagology', 'Tagology', 'install_plugins', basename(__FILE__), array(&$this, 'print_admin'));
	}	
	
	
	/*
	* print the plugin admin page
	*/
	function print_admin() {
    if (method_exists($this, 'before_print_admin'))
      $this->before_print_admin();
		$options = $this->get_options();
		if (isset($_POST['action']) && 'update' == $_POST['action']) {
			$nonce = $_REQUEST['_wpnonce'];
			if (!wp_verify_nonce($nonce, 'update-options'))
				die ("Can't do that!");		
				$options['is_multiuser'] = (isset($_POST['is_multiuser']))?true:false;
				$options['bookmarklet_text'] = stripslashes($_POST['bookmarklet_text']);
				
      update_option(WP_TAGOLOGY_OPTS, $options);
?>
<div class="updated"><p><strong><?php _e('Settings Updated.', 'tagology');?></strong></p></div>
<?php
		} 
?>
<div class="wrap">
<h2>Tagology Plugin v.<?php echo $this->plugin_version; ?></h2>
<?php do_action( 'tagology_settings_messages' ); ?>
<form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
<?php wp_nonce_field('update-options'); ?>
<h3><?php _e('Basic Options', 'tagology');?></h3>
<p><?php _e('Select the options which suit your site.', 'tagology');?></p>
<table class="form-table"><tbody>	
<tr valign="top">
	<th scope="row"><label for="delete_bookmarks">Delete All Bookmarks</label></th>
	<td>
				<label for="delete_bookmarks">
		<input type="checkbox" id="delete_bookmarks" name="delete_bookmarks" value="1"/></label><br />
	</td>
</tr>		
<tr valign="top">
	<th scope="row"><label for="import_bookmark_file">Import Bookmark File</label></th>
	<td>
		<input type="file" size="80" id="import_bookmark_file" name="import_bookmark_file"/><br />
	</td>
</tr>			
<tr valign="top">
	<th scope="row"><label for="import_recent_bookmarks">Import Recent Bookmarks</label></th>
	<td>
		<input type="text" size="80" id="import_recent_bookmarks" name="import_recent_bookmarks" value="<?php echo esc_attr($options['import_recent_bookmarks']); ?>"/><br />
	</td>
</tr>			
<tr valign="top">
	<th scope="row"><label for="is_multiuser">Multi-User Mode?</label></th>
	<td>
				<label for="is_multiuser">
		<input <?php echo $options['is_multiuser'] ? 'checked=checked' : '' ?> type="checkbox" id="is_multiuser" name="is_multiuser" value="1"/></label><br />
	</td>
</tr>		
<tr valign="top">
	<th scope="row"><label for="bookmarklet_text">Bookmarklet Text</label></th>
	<td>
		<input type="text" size="80" id="bookmarklet_text" name="bookmarklet_text" value="<?php echo esc_attr($options['bookmarklet_text']); ?>"/><br />
	</td>
</tr>			

</tbody></table>
<input type="hidden" name="action" value="update" />
<p class="submit">
	<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>
</form>
<?php
	}
	

// END auto-generated code


  /*
   * custom admin init
   */
  function custom_admin_init() {    
    add_action('tagology_settings_messages', array(&$this, 'save_settings'));
    add_filter('rewrite_rules_array', array(&$this, 'url_rewrite_filter'));
  }

  /*
   * custom client init
   */
  function custom_client_init() {
    add_filter('term_links-post_tag', array( &$this, 'term_links_post_tag_filter') );
    add_filter('query_vars', array(&$this, 'query_vars_filter'));	
		add_action('template_redirect', array(&$this, 'template_redirect_action'), 9 /* before canonical */);
    add_action('tagology_embeds', array(&$this, 'tagology_embeds_action')); 
    
    // query filters
    add_filter( 'posts_join', array(&$this, 'posts_join_filter' ));
    add_filter( 'posts_where', array(&$this, 'posts_where_filter' ));
    //add_filter( 'posts_distinct', array(&$this, 'posts_distinct_filter' ));    
    add_filter( 'posts_groupby', array(&$this, 'posts_groupby_filter' )); 
  }	
  
  /*
   * posts_groupby_filter
   */
  function posts_groupby_filter( $groupby ) {    
    if ( is_search() || is_author()) {
      global $wpdb;		
			$groupby = $wpdb->posts .'.ID';
    }
    return $groupby;
  }
  
  /*
   * posts_distinct_filter
   */
  function posts_distinct_filter( $distinct ) {
    
    if (!is_search()) 
      return $distinct;
    
    global $wpdb;
    
    $distinct = " DISTINCTROW $wpdb->posts.ID, ";
    return $distinct;
  }
  
  /*
   * posts_where_filter
   */
  function posts_where_filter( $where ) {
    global $wp_query;  
    global $wpdb; 
    
    if ( is_search() ) {
     
      $term = $wp_query->query_vars['search_terms'][0];    
      $post_type = $wp_query->query_vars['post_type'];      
      
      if (empty($term) || empty($post_type))
        return $where;
        
      $where = "AND $wpdb->posts.post_type = '{$post_type}' ";
      $where .= "AND $wpdb->posts.post_status = 'publish' ";    
      $where .= "AND ( $wpdb->posts.post_title LIKE '%{$term}%' OR $wpdb->terms.name like '%{$term}%' ) ";
      
    }
    
    if( is_author() ) {
			$author = get_userdata($wp_query->query_vars['author']);
			$term = get_term_by('name', $author->user_login, TAGOLOGY_COAUTHOR_TAXONOMY);
				
			if( $author && $term ) {				
        $where = preg_replace('/(\b(?:' . $wpdb->posts . '\.)?post_author\s*=\s*(\d+))/', '($1 OR (' . $wpdb->term_taxonomy . '.taxonomy = \''. TAGOLOGY_COAUTHOR_TAXONOMY.'\' AND '. $wpdb->term_taxonomy .'.term_id = \''. $term->term_id .'\'))', $where, 1);

			}
		}    
    
    return $where;
  }
  
  /*
   * posts_join_filter
   */
  function posts_join_filter( $join ) {
    global $wpdb;
    
    if( is_author() ) {
      
			$join .= " INNER JOIN $wpdb->term_relationships ON ($wpdb->posts.ID = $wpdb->term_relationships.object_id) ";
      $join .= " INNER JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id)";		
		}
    
    
    if ( is_search() ) {  
      
      // join posts to relationships
      $join .= " LEFT JOIN $wpdb->term_relationships ON ($wpdb->posts.ID = $wpdb->term_relationships.object_id) ";
      
      // join relationships to taxonomy
      $join .= " LEFT JOIN $wpdb->term_taxonomy ON ($wpdb->term_relationships.term_taxonomy_id = $wpdb->term_taxonomy.term_taxonomy_id) ";
      
      // join taxonomy to terms
      $join .= " LEFT JOIN $wpdb->terms ON ($wpdb->term_taxonomy.term_id = $wpdb->terms.term_id) ";
    
    }
  
    return $join;
  }
  
	/*
	* query vars filter
	*/
	function query_vars_filter($qvars) {
		$qvars[] = TAGOLOGY_QUERY_VAR;
    $qvars[] = TAGOLOGY_LIVESEARCH_VAR;
    $qvars[] = 'url';
    $qvars[] = 'title';
    $qvars[] = 'tags';
    $qvars[] = 'site';
    $qvars[] = 'c';
    $qvars[] = '_wpnonce';
    $qvars[] = '_ajax';
		return $qvars;
	}

  /*
   * url_rewrite_filter
   */
  function url_rewrite_filter($rules) {    
    
    // build from scratch
		$new_rules = array();
    
    // front page
		$new_rules['/?$'] = sprintf( 'index.php?post_type=%s', TAGOLOGY_POST_TYPE );
    // paged
		$new_rules['page/([0-9]{1,})/?$'] = sprintf( 'index.php?paged=$matches[1]&post_type=%s', TAGOLOGY_POST_TYPE );
    // single tag
		$new_rules['tag/([^/]+)/?$'] = sprintf( 'index.php?tag=$matches[1]&post_type=%s', TAGOLOGY_POST_TYPE );
    // paged tag
		$new_rules['tag/([^/]+)/([0-9]{1,})/?$'] = sprintf( 'index.php?tag=$matches[1]&paged=$matches[2]&post_type=%s', TAGOLOGY_POST_TYPE ); 
    $new_rules['tag/([^/]+)/page/([0-9]{1,})/?$'] = sprintf( 'index.php?tag=$matches[1]&paged=$matches[2]&post_type=%s', TAGOLOGY_POST_TYPE );   
    // date archives
    $new_rules['date/([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/page/?([0-9]{1,})/?$'] = sprintf( 'index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&paged=$matches[4]&post_type=%s', TAGOLOGY_POST_TYPE ); 
    $new_rules['date/([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/?$'] = sprintf( 'index.php?year=$matches[1]&monthnum=$matches[2]&day=$matches[3]&post_type=%s', TAGOLOGY_POST_TYPE ); 
    // single links
    //$new_rules['url/([^/]+)(/[0-9]+)?/?$'] = 'index.php?post_type=savorypost&savorypost=$matches[1]&page=$matches[2]';
    $new_rules['url/([^/]+)/?$'] = sprintf( 'index.php?%s=$matches[1]&post_type=%s', TAGOLOGY_POST_TYPE, TAGOLOGY_POST_TYPE );
    
    // feeds
    $new_rules['feed/?$'] = sprintf( 'index.php?%s=feed&post_type=%s', TAGOLOGY_QUERY_VAR, TAGOLOGY_POST_TYPE);
    
    
    // author - put at bottom and removed the need for the 'author/' path
    //$new_rules['author/([^/]+)/page/?([0-9]{1,})/?$'] = sprintf( 'index.php?author_name=$matches[1]&paged=$matches[2]&post_type=%s', TAGOLOGY_POST_TYPE );
    //$new_rules['author/([^/]+)/?$'] = sprintf( 'index.php?author_name=$matches[1]&post_type=%s', TAGOLOGY_POST_TYPE );  
    
    // save bookmark AJAX
    $new_rules['save/([0-9]+)/(.+)/?$'] = sprintf( 'index.php?%s=save&p=$matches[1]&post_type=%s&_wpnonce=$matches[2]', TAGOLOGY_QUERY_VAR, TAGOLOGY_POST_TYPE );
    
    //
    // bookmarklet URLs
    //
    
    // bookmarklet javascript
    $new_rules['bookmarklet/([a-z]+)/?$'] = sprintf( 'index.php?%s=bookmarklet/$matches[1]', TAGOLOGY_QUERY_VAR );
    // short link to bookmarked URL, e.g. http://mysite.com/s1234 --> http://example.com/foobar
    $new_rules['s([0-9]+)/?$'] = sprintf( 'index.php?%s=shortlink&p=$matches[1]', TAGOLOGY_QUERY_VAR );
    $new_rules['s/([0-9]+)/?$'] = sprintf( 'index.php?%s=shortlink&p=$matches[1]', TAGOLOGY_QUERY_VAR ); // deprecated
    // short link to bookmark
    $new_rules['b([0-9]+)/?$'] = sprintf( 'index.php?p=$matches[1]' );
    //$new_rules['b([0-9]+)c([0-9]+)/?$'] = sprintf( 'index.php?p=$matches[1]#comment-$matches[2]' );
    
    // live search
    $new_rules['livesearch/?$'] = sprintf ('index.php?%s=livesearch', TAGOLOGY_QUERY_VAR); // deprecated
    
    // author 
    $new_rules['([^/]+)/page/?([0-9]{1,})/?$'] = sprintf( 'index.php?author_name=$matches[1]&paged=$matches[2]&post_type=%s', TAGOLOGY_POST_TYPE );
    $new_rules['([^/]+)/?$'] = sprintf( 'index.php?author_name=$matches[1]&post_type=%s', TAGOLOGY_POST_TYPE );  
    

    
    // return new rules
		return $new_rules;
  }
  
	/*
	* Template Redirects
	*/
	function template_redirect_action() {
    
		switch (get_query_var(TAGOLOGY_QUERY_VAR)) {
			case 'bookmarklet/js' :
				$this->bookmarklet_redirect('bookmarklet/bookmarklet.js', true);
				break;
			case 'bookmarklet/panel' :
				$this->bookmarklet_redirect('bookmarklet/panel.php');
				break;
			case 'bookmarklet/new' :
				$this->bookmarklet_redirect('bookmarklet/new.php');
				break;
			case 'bookmarklet/logout' :
				$this->bookmarklet_redirect('bookmarklet/logout.php');
				break;
      case 'shortlink': 
        $this->shortlink_redirect();
        break;      
      case 'livesearch':
        $this->livesearch_redirect();
        break;
      case 'save':
        $this->save_redirect();
        break;
      case 'feed':
        $this->feed_redirect();
        break;
      //default:
      //  wp_die("Not Found.");
      //  break;
		}
	}
  
  /*
   * feed redirect
   */
  function feed_redirect() {
    include( 'feed-atom.php' );
    exit();
  }
  
  /*
   * save redirect
   */
  function save_redirect() {  
        
    // verify nonce
    $nonce = get_query_var('_wpnonce');
    if (! wp_verify_nonce($nonce, 'tagologysave') ) die('ERROR'); 

    global $wp_query;
    
    // did get a post
    $bookmark = $wp_query->post;
    if (!$bookmark)
      die();
    
    // is multi-user
    if (!$this->is_multi_user())
      die();
    
    // get post author
    $user_id = $bookmark->post_author;
    $authordata = get_userdata( $user_id );
    $author_username = $authordata->user_login;
    
    // get current logged in user
    global $current_user;
    get_currentuserinfo();  
    $logged_in_username = $current_user->user_login;
    
    // make sure they are different, i.e. an author can't SAVE their own bookmark
    if ($author_username  == $logged_in_username)
      die();      
    
    // add user as a term if they don't exist
    $insert = false;
    if(!term_exists( $logged_in_username, TAGOLOGY_COAUTHOR_TAXONOMY ) ) {
      //$args = array('slug' => sanitize_title($name) );		
      $insert = wp_insert_term( $logged_in_username, TAGOLOGY_COAUTHOR_TAXONOMY );
    }
    
    // print_r($insert);
    // ad authors as post terms
    if( !is_wp_error( $insert ) ) {
      $set = wp_set_post_terms( $bookmark->ID, $logged_in_username, TAGOLOGY_COAUTHOR_TAXONOMY, true /* append */ );
      print_r($set);
    } 
    else {
      // noop
    }
    
    // return response
    $ajax = get_query_var( '_ajax' );
    
    // ajax
    if ('1' == $ajax) {
      
      // echo back the count
      $owners = $this->get_owners();
      $count =  1 /* author */ + count($owners);
  
      echo $count;
      exit();
    }
    
    // refresh current page
    $url = get_permalink();
    if (!$url) { $url = site_url(); } /* shouldn't happen */
    wp_redirect( $url);
    exit();
  }
  
  /*
   * live search redirect
   */
  function livesearch_redirect() {
    global $wpdb;
    
    $results = array();
    $k = get_query_var(TAGOLOGY_LIVESEARCH_VAR);
    if (3 <= strlen($k)) {
    
      $querystr = $wpdb->prepare("
        SELECT wposts.*, wpostmeta.meta_value as bookmark_url
        FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta
        WHERE wposts.ID = wpostmeta.post_id 
        AND wpostmeta.meta_key = '%s' 
        AND wposts.post_title LIKE '%%%s%%' 
        AND wposts.post_status = 'publish' 
        AND wposts.post_type = '%s' 
        ORDER BY wposts.post_date DESC LIMIT 0, 10
      ", TAGOLOGY_METAKEY_URL, $k, TAGOLOGY_POST_TYPE );
      
      $posts = $wpdb->get_results($querystr);      
      $out = array_map( array( &$this, 'live_search_results' ), $posts ); 
      $results['bookmarks'] = $out;      
    }
    
    echo json_encode($results);
    die();
  }
  
  function live_search_results( $post ) {
    return array(
      'title'=>$post->post_title,
      'bookmark'=>$post->bookmark_url,
      'link'=>get_post_permalink($post->ID),
    );
  }
  
  /*
   * tagology_embeds_action
   */
  function tagology_embeds_action($bookmark = null) {
    // TODO    
  }
  
  /*
   * custom get_day_link function
   * always use /date/YYYY/mm/jj URLs
   */
  function get_day_link($year, $month, $day) {
   
    if ( !$year )
      $year = gmdate('Y', current_time('timestamp'));
    if ( !$month )
      $month = gmdate('m', current_time('timestamp'));
    if ( !$day )
      $day = gmdate('j', current_time('timestamp'));

    $daylink = 'date/%year%/%monthnum%/%day%';   
    $daylink = str_replace('%year%', $year, $daylink);
    $daylink = str_replace('%monthnum%', zeroise(intval($month), 2), $daylink);
    $daylink = str_replace('%day%', zeroise(intval($day), 2), $daylink);    
    $daylink = home_url( user_trailingslashit($daylink, 'day') );    
    return $daylink;
  }
 
  /*
   * get owners
   */
  function get_owners($bookmark = false) {
    if (!$bookmark) {
      global $post;
      $bookmark = $post;
    }
    return wp_get_object_terms( $bookmark->ID, TAGOLOGY_COAUTHOR_TAXONOMY );
  }
  
   /*
   * in multi-user mode?
   */
  function is_multi_user() {
    $options = $this->get_options();
    return ($options['is_multiuser']);
  }
 
  /*
   * get short link for a bookmark
   */
  function get_short_link( $bookmark ) {
    return site_url( sprintf('/s%s', $bookmark->ID));
  }
  
  /*
   * shortlink redirect
   */
  function shortlink_redirect() {
    $p = get_query_var('p');
    if (0 < (int)$p) {
      $posts = get_posts(array(
        'p' => $p,
        'post_type' => TAGOLOGY_POST_TYPE,
      ));
      //print_r($posts);
      if ($posts) {
        $url = get_post_meta($posts[0]->ID, TAGOLOGY_METAKEY_URL, true);
        //print "URL = $url\n";
        if (!empty($url)) {
          //echo "$url";
          wp_redirect( $url );
          exit();
        }
      }
    }
    wp_die("Not Found.");
  }
  
  /*
   * bookmarket redirect
   */
  function bookmarklet_redirect($basefile, $script = false) {
    
    header("HTTP/1.0 202 OK");
    
    $file = TEMPLATEPATH . '/' . $basefile;
    //$filesize = @filesize($file);
    
    @ob_end_clean();
    @session_write_close();
 
    //if (isset($filesize) && $filesize > 0)				
    //  header("Content-Length: ".$filesize);           
    //header("Pragma: no-cache");
    //header("Expires: 0");
    header("Robots: none");
    header("Vary: Accept-Encoding");
    	
    if ($script)
      header("Content-Type: application/javascript");
    else 
      header("Content-Type: text/html");
      
    if ($script)
      printf ("var src = \"%s\";\n", get_bloginfo('url'));
      
    // read file and output
    if (false !== strpos($basefile, '.php'))
      include($basefile);
    else
      readfile($file);
      
    exit();
  }

  
  /*
   * filter tag links - insert a special css class for 'special' tags
   */
  function term_links_post_tag_filter($links) {
    
    foreach ($links as &$link) {
      if (false !== strpos($link, 'me:'))
        $link = preg_replace("/<a /i", "<a class='tag_me' ", $link);
    }
    return $links;
  }
  
  /*
   * custom any init
   */
  function custom_any_init() {
    $this->register_custom_post_type();  
    $this->register_custom_taxonomy();
  }
  
  /*
   * save settings call-back
   */
  function save_settings() {   
    if (isset($_REQUEST['import_bookmarks']))
      $this->import_bookmarks();
    elseif (isset($_REQUEST['import_bookmarks_test']))
      $this->import_bookmarks(30);      
    elseif(isset($_REQUEST['delete_bookmarks']))
      $this->delete_bookmarks();
    
    if(isset($_REQUEST['import_recent_bookmarks'])) {
      $url = trim($_REQUEST['import_recent_bookmarks']);
      if (!empty($url))
        $this->import_bookmarks_from_url($url);
    }
      
    if (isset($_FILES['import_bookmark_file']))
      $this->upload_bookmarks($_FILES['import_bookmark_file']);    
  }
  
  /*
   * get the favicon ING tag
   */
  function get_the_favicon_url() {
    $url = $this->get_bookmark_url();
    $host = parse_url($url, PHP_URL_HOST);
    return $host;
  }  
  
  /*
  * get post's bookmark URL
  */
  function get_bookmark_url($bookmark = null) {
    if (!$bookmark) {
      global $post;  
      $bookmark = $post;
    }
    return get_post_meta($bookmark->ID, TAGOLOGY_METAKEY_URL, true);   
  }
  
  /*
   * get recent distinct tags
   * 
   * limit (default = 10)
   * user (default = any)
   */
  function get_recent_tags($args = array()) {
    
    $args = wp_parse_args($args, array(
      'limit' => 10,
      'user' => '*',
    ));
    extract( $args, EXTR_SKIP );
    
    global $wpdb;    
    $sql = "SELECT DISTINCT $wpdb->terms.term_id, $wpdb->terms.name, $wpdb->terms.slug
            FROM $wpdb->terms   
            INNER JOIN $wpdb->term_taxonomy ON ($wpdb->terms.term_id = $wpdb->term_taxonomy.term_id)
            INNER JOIN $wpdb->term_relationships ON ($wpdb->terms.term_id = $wpdb->term_relationships.term_taxonomy_id)
            INNER JOIN $wpdb->posts ON ($wpdb->term_relationships.object_id = $wpdb->posts.ID)
            WHERE $wpdb->term_taxonomy.taxonomy = 'post_tag' 
            %user%
            ORDER BY $wpdb->posts.post_date DESC LIMIT 1, $limit"; 
    
    // user constraints
    //
    switch ($user) {
      
      // ANY user
      case '*':
         $sql = str_replace('%user%', '', $sql); 
         break;
         
      // CURRENT user
      case '.':
        if (is_user_logged_in()) {
          wp_get_current_user();
          global $current_user;
          $sql = str_replace('%user%', sprintf("AND $wpdb->posts.post_author = %u", $current_user->ID), $sql);
        }
        else {
          $sql = str_replace('%user%', '', $sql); // fallback to ANY user
        }
        
        break;
      
      // ANY user fallback
      default:
        $sql = str_replace('%user%', '', $sql); 
    }
    
    // return raw results
    return $wpdb->get_results($wpdb->prepare($sql));
  }
  
  /*
   * get popular tags
   */
  function get_popular_tags($limit = 10) {
    $args = array (
      'number' => $limit,
      'taxonomy' => 'post_tag',
      //'format' => 'array',
      //'echo' => 0,
      'orderby' => 'count',
      'order' => 'DESC',
      
    );
    
    //$cloud = wp_tag_cloud($args);
    
    $tags = get_terms( $args['taxonomy'], $args ); // Always query top tags

    if ( empty( $tags ) )
      return;

    foreach ( $tags as $key => $tag ) {
      $link = get_term_link( intval($tag->term_id), $args['taxonomy'] );
      if ( is_wp_error( $link ) )
        continue;
      $tags[ $key ]->link = $link;
      $tags[ $key ]->id = $tag->term_id;
    }

    return $tags; // print_r($tags);
  }
    
  /*
   * upload bookmarks
   */
  function upload_bookmarks($file) {
    // Array ( [import_bookmark_file] => Array ( [name] => bookmarks-test.xml [type] => text/xml [tmp_name] => /tmp/phpWUTm16 [error] => 0 [size] => 865 ) )
    $tmp = $file['tmp_name'];
    if (file_exists($tmp))
      $this->import_bookmarks_from_url($tmp);
  }
  
  /*
   * delete all bookmarks
   */
  function delete_bookmarks() {

    $myposts = get_posts(array(
      'post_type' => TAGOLOGY_POST_TYPE,
      'numberposts' => -1,
    ));
    
    echo '<pre><textbox>';
    echo 'Attempting to delete ' . count($myposts) . " bookmarks...\n";
    
    foreach ($myposts as $post) {
      wp_delete_post( $post->ID, true );
    }
    
    echo '</textbox></pre>';    
  }
  
  /*
   * test if bookmark exists
   */
  function bookmark_exists($href) {
    
    $hash = md5($href);
    
    // check for existence
    $myposts = get_posts(array(
      'post_type' => TAGOLOGY_POST_TYPE,
      'meta_key' => TAGOLOG_METAKEY_HASH,
      'meta_value' => $hash,
    ));
    
    if (0 == count($myposts))
      return false;
    return $myposts[0];
    //return 0 < count($myposts);
  }

  /*
   * return a function that prepends a string to a tag object
   * PHP 5.3

  function hash_tag_f($prefix = '') {
    $f = function($a) use($prefix) {
      return $prefix . $a->name;
    };
    return $f;
  }
  */
  
  function prefix_tag_hash( $tag ) {
    return '#' . $tag->name;
  }

  function prefix_tag( $tag ) {
    return $tag->name;
  }  

  /*
   * return list of tags
   */
  function get_tags($bookmark) {
    // clean
    $msg = '';
    
    // get list of tags prefixed with a hash '#'
    $tags = get_the_tags($bookmark->ID);  
    if ($tags) 
      $tags = array_unique(array_values(array_map( array(&$this, 'prefix_tag' ), $tags)));
    
    return implode(' ', $tags);
  }
  
  /*
   * get a short message (think Twitter, etc...) from a bookmark post
   */
  function get_short_message($bookmark) {

    // clean
    $msg = '';

    // get title
    $title = $bookmark->post_title;  
    $msg .= $title;
    
    // get URL
    $short = $this->get_short_link($bookmark);
    if (!empty($short))
      $msg .= ' ' . $short;
    
    // get list of tags prefixed with a hash '#'
    $tags = get_the_tags($bookmark->ID);  
    if ($tags) {      
      $tags = array_unique(array_values(array_map(array( &$this, 'prefix_tag_hash') , $tags)));
      $tags = implode(' ', $tags);
      $msg .= ' '. $tags;
    }
    
    // result...
    return $msg;
  }
  
  /*
   * insert bookmark
   */
  function insert_bookmark($hash, $href, $desc, $taglist, $time, $replace = true) {

    // echo "Trying $hash, $href, $desc, $taglist, $time";
    // die();
    
    // check for existence
    $myposts = get_posts(array(
      'post_type' => TAGOLOGY_POST_TYPE,
      'meta_key' => TAGOLOG_METAKEY_HASH,
      'meta_value' => $hash,
    ));
    
    $bookmark = $this->bookmark_exists($href);
    if ($bookmark) {
      
      if ($replace) {
        // update tags
        $tags = explode(' ', $taglist);
        wp_set_post_tags( $bookmark->ID, $tags, false );
        return true;
      }
      
      // not replacing
      return false;
    }
    
    // make post data        
    $my_post = array(
     'post_title' => $desc,
     'post_content' => '',
     'post_status' => 'publish',
     'post_author' => 1,
     'post_type' => TAGOLOGY_POST_TYPE,
     'post_date_gmt' => date( 'Y-m-d H:i:s GMT', strtotime($time) ),
     'post_date' => date( 'Y-m-d H:i:s', strtotime($time) ),
    );
    
    // insert
    $id = false;
    $id = wp_insert_post( $my_post );
    if (!$id)
      return false; // ERROR
    
    // add metadata
    update_post_meta($id, TAGOLOG_METAKEY_HASH, $hash);    
    update_post_meta($id, TAGOLOGY_METAKEY_URL, $href);       
    
    // add tags, split on SPACE
    $tags = explode(' ', $taglist);
    //$tags = preg_split('/[ ,]/', $taglist);
    wp_set_post_tags( $id, $tags, false );
    
    // ok
    return true;
  }
  
  /*
   * import bookmarks from URL
   */
  function import_bookmarks_from_url( $url, $count = -1 ) {
       
    echo '<pre><textbox>';      
    echo "Attemping to import bookmarks from $url\n";
    
    $xmlstr = file_get_contents($url);          
    $xml = new SimpleXMLElement($xmlstr);
    
    $i = 0;
    foreach ($xml->post as $post) {
      
      $hash = (string)$post['hash'];
      $href = (string)$post['href'];
      $desc = (string)$post['description'];
      $taglist = (string)$post['tag'];
      $time = (string)$post['time'];
      
      if ($this->insert_bookmark($hash, $href, $desc, $taglist, $time))
        echo "OK.\n";
      else
        echo "Skipping $desc\n";
        
      // count
      $i++;
      
      // break if reached limit
      if (0 < $count && $i == $count)
        break;
      
    }      
    echo '</textbox></pre>';
  }
  
  /*
   * import bookmarks from local file
   */
  function import_bookmarks($count = -1) {      
    $file = PLUGIN_PATH_savory . '/bookmarks.xml';    
    $this->import_bookmarks_from_url($file);
  }
  
  /*
   * custom taxonomy to handle multiple authors
   */
  function register_custom_taxonomy() {
     if( !taxonomy_exists( TAGOLOGY_COAUTHOR_TAXONOMY )) {
      register_taxonomy( TAGOLOGY_COAUTHOR_TAXONOMY, TAGOLOGY_POST_TYPE, array(
        'hierarchical' => false, 
        'label' => false, 
        'query_var' => false, 
        'rewrite' => false, 
        'sort' => true ) 
      );
    }
  } 

  /*
   * custom post type for templates
   */
	function register_custom_post_type() {		    
		register_post_type( TAGOLOGY_POST_TYPE,
			array(
				'labels' => array(
					'name' => __( 'Bookmarks' ),
					'singular_name' => __( 'Bookmark' ),
					'add_new' => __( 'Add New' ),
					'add_new_item' => __( 'Add New Bookmark' ),
					'edit' => __( 'Edit' ),
					'edit_item' => __( 'Edit Bookmark' ),
					'new_item' => __( 'New Bookmark' ),
					'view' => __( 'View Bookmark' ),
					'view_item' => __( 'View Bookmark' ),
					'search_items' => __( 'Search Bookmarks' ),
					'not_found' => __( 'No Bookmarks found' ),
					'not_found_in_trash' => __( 'No Bookmarks found in Trash' )
				),
				'capabilities' => array( 'manage_links' ),
				'taxonomies' => array('post_tag'),
        'public' => true,
        'show_ui' => true,
        'publicly_queryable' => true,
        'exclude_from_search' => false,
        'rewrite' => array( 'slug' => TAGOLOGY_POST_TYPE_SLUG, 'with_front' => false ),        
				'menu_position' => 20,
				'supports' => array( 'title', 'editor', 'custom-fields' ),
			)
		);		
	}
}

?>
