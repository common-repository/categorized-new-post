<?php
/*
Plugin Name: Categorized New Post
Plugin URI: https://boomil.com
Description: Widget for categorized new post.
Version: 1.0.3
Author: boomil
Author URI: https://boomil.com/
License: GPLv2
*/

class MyCategorizedNewPostWidget extends WP_Widget {

	function MyCategorizedNewPostWidget() {
		parent::__construct(false, 'Categorized New Post');
	}

	function widget( $args, $instance ) {
		// argsのkey-valueペアを変数にしつつ、プロパティ値も用意する
	  extract($args);
		$title = $instance['title'];
		$item_number = $instance['item_number'];
		$thumbnail_height = $instance['thumbnail_height'];
		$thumbnail_width = $instance['thumbnail_width'];
		
		echo '<div class="sb-widget">';

		// タイトルを表示する
		if( !empty( $title ) ) { echo '<h4 class="widget-title">' . $title . '</h4>';}

		// カテゴリ別で記事を取得する
		if(is_home()) {
			$new_posts = get_posts('numberposts=' .$item_number);
		} else {
			$category_id = get_the_category()[0]->cat_ID;
			$new_posts = get_posts('numberposts=' .$item_number. '&category=' . $category_id);
		}
		
		// 記事一覧を表示する
		global $post;
		if ($new_posts) {
			echo "<ul>";
			foreach ( $new_posts as $post ) :
				setup_postdata ( $post );
				echo '<li style="height:' . $thumbnail_height . 'px;">';
				echo '<a href=' . get_permalink () . '>';
				if (has_post_thumbnail ()) {
					echo '<span style="float:left; margin-right:5px;">' . get_the_post_thumbnail ( $page->ID, array (
							$thumbnail_height,
							$thumbnail_width 
					) ) . '</span>';
				}
				echo '<span>' . the_title ( '', '', false ) . '</span>';
				echo "</a></li>";
			endforeach
			;
			echo "</ul>";
		} else {
			echo '<p>No new post.</p>';
		}
		
		echo '</div>';
	}

	function update($new_instance, $old_instance) {
		return $new_instance;
	}

	function form($instance) {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'item_number' => 5, 'thumbnail_height' => 120, 'thumbnail_width' => 120, 'text' => '' ) );
    $title = strip_tags($instance['title']);
		$item_number = strip_tags($instance['item_number']);
		$thumbnail_height = strip_tags($instance['thumbnail_height']);
		$thumbnail_width = strip_tags($instance['thumbnail_width']);
    ?>
    <p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></p>
		<label for="<?php echo $this->get_field_id('item_number'); ?>"><?php _e('Item number:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('item_number'); ?>" name="<?php echo $this->get_field_name('item_number'); ?>" type="text" value="<?php echo esc_attr($item_number); ?>" /></p>
		<label for="<?php echo $this->get_field_id('thumbnail_height'); ?>"><?php _e('Thumbnail height:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('thumbnail_height'); ?>" name="<?php echo $this->get_field_name('thumbnail_height'); ?>" type="text" value="<?php echo esc_attr($thumbnail_height); ?>" /></p>
		<label for="<?php echo $this->get_field_id('thumbnail_width'); ?>"><?php _e('Thumbnail width:'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('thumbnail_width'); ?>" name="<?php echo $this->get_field_name('thumbnail_width'); ?>" type="text" value="<?php echo esc_attr($thumbnail_width); ?>" /></p>
		<br/>
		If you like this plugin, please share the link for my blog. もしこのプラグインを気に入ったら、私のブログへのリンクをお願いします.<br/>
		https://boomil.com/?p=2808
		<?php
	}
}

function my_categorized_new_post_widget_register() {
	register_widget( 'MyCategorizedNewPostWidget' );
}

add_action( 'widgets_init', 'my_categorized_new_post_widget_register' );

?>
