<?php
/*
Plugin Name: Ideascale Top Ideas
Plugin URI: http://dpworld.i360hub.com
Description: A widget that shows the top voted idea.
Author: Suraj Shirvankar
Version: 0.1
Author URI: http://surajms.com/
*/
 
/**
 * Ideascale top ideas
 */

class ideascale_top_ideas extends WP_Widget {
 
 
    function ideascale_top_ideas() {
        parent::WP_Widget(false, $name = 'Ideascale Top Ideas');
    }
 
	function widget($args, $instance) {
		extract( $args );
		$title 		= apply_filters('widget_title', $instance['title']); 
		$community_url 	= $instance['community_url']; 
		$api_key 	= $instance['api_key'];
		$num_of_ideas = $instance['num_of_ideas']; 
		$args = array(
			'community_url' => $community_url,
			'api_key'	=> $api_key
		);
		$opts = array(
		  'http'=>array(
		    'method'=>"GET",
		    'header'=>"Accept-language: en\r\n" .
		              "Cookie: foo=bar\r\n" .
		              "api_token: ".$api_key."\r\n".
		              "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.31 (KHTML, like Gecko) Chrome/26.0.1410.64 Safari/537.31\r\n"
		  )
		);

		$context = stream_context_create($opts);

		// Open the file using the HTTP headers set above
		$file = file_get_contents($community_url.'/a/rest/v1/ideas/top', false, $context);
		$data = json_decode($file);
		?>
		<?php echo $before_widget; ?>
		<h2><?php echo $title;?></h2>
		<ul>
		<?php $i=0; foreach($data as $ideas){ ?>
			<?php if ($i < $num_of_ideas){ ?>
			<li><a href="<?php echo $ideas->url; ?>"><h6><?php echo $ideas->title;?></h6></a></li>
			<?php } ++$i;?>
		<?php } ?>
		</ul>
		<a style="
		border-right: none;
		font-weight: 900;
		margin: .5em;
		display: inline-block;
		border-top: 1px solid rgba(255,255,255,0.8);
		box-shadow: 0 0.05em 0 rgba(255, 255, 255, 0.6) inset, 0 3px 3px rgba(0, 0, 0, 0.5);
		-webkit-transition: box-shadow 0.25s linear;
		   -moz-transition: box-shadow 0.25s linear;
			-ms-transition: box-shadow 0.25s linear;
			 -o-transition: box-shadow 0.25s linear;
				transition: box-shadow 0.25s linear;
		z-index: 10;
		-webkit-user-select: none;
		-moz-user-select: none;
		user-select: none;
		text-decoration: none;
		text-align: center;
		white-space: pre;
		text-shadow: 0px 2px 2px #ffffff inset;
		text-transform: uppercase; 
		padding: 0.7em 1em 0.7em; 
		-webkit-border-radius: 2em 2em 2em 2em; 
		-moz-border-radius: 2em 2em 2em 2em; 
		border-radius: 2em 2em 2em 2em;
		background-color: transparent;
		background-image: -moz-linear-gradient(center top , rgba(255, 255, 255, 0.75) 0%, rgba(255, 255, 255, 0) 75%, rgba(255, 255, 255, 0.25) 100%);
		background-image: -webkit-linear-gradient(top, rgba(255, 255, 255, 0.75) 0%, rgba(255, 255, 255, 0) 75%, rgba(255, 255, 255, 0.25) 100%);
		background-image: linear-gradient(top, rgba(255, 255, 255, 0.75) 0%, rgba(255, 255, 255, 0) 75%, rgba(255, 255, 255, 0.25) 100%);
		background-image: -ms-linear-gradient(top, rgba(255, 255, 255, 0.75) 0%, rgba(255, 255, 255, 0) 75%, rgba(255, 255, 255, 0.25) 100%);" href="<?php echo $community_url;?>">Submit Idea</a>
		<?php echo $after_widget; ?>
		<?php
	}
 
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['community_url'] = strip_tags($new_instance['community_url']);
		$instance['api_key'] = $new_instance['api_key'];
		$instance['num_of_ideas'] = $new_instance['num_of_ideas'];
		return $instance;
	}
 
    function form($instance) {
 
        $title 		= esc_attr($instance['title']);
        $community_url		= esc_attr($instance['community_url']);
        $exclude	= esc_attr($instance['exclude']);
        $api_key	= esc_attr($instance['api_key']);
        $num_of_ideas = esc_attr($instance['num_of_ideas']);
        ?>
        <p>
          <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
          <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
        </p>
		<p>
          <label for="<?php echo $this->get_field_id('community_url'); ?>"><?php _e('Enter community url'); ?></label>
          <input id="<?php echo $this->get_field_id('community_url'); ?>"  placeholder="http://idea.ideascale.com" name="<?php echo $this->get_field_name('community_url'); ?>" type="text" value="<?php echo $community_url; ?>" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('api_key'); ?>"><?php _e('Enter API Key'); ?></label>
          <input id="<?php echo $this->get_field_id('api_key'); ?>" name="<?php echo $this->get_field_name('api_key'); ?>" type="text" value="<?php echo $api_key; ?>" />
        </p>
        <p>
          <label for="<?php echo $this->get_field_id('num_of_ideas'); ?>"><?php _e('Number of Ideas'); ?></label>
          <input id="<?php echo $this->get_field_id('num_of_ideas'); ?>" name="<?php echo $this->get_field_name('num_of_ideas'); ?>" value="<?php echo $num_of_ideas;?>"/>
        </p>
        <?php
    }
 
 
}
add_action('widgets_init', create_function('', 'return register_widget("ideascale_top_ideas");'));
?>
