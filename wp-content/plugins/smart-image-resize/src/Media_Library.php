<?php

namespace WP_Smart_Image_Resize;

use WP_Smart_Image_Resize\Filters\Filter_Processable_Regenerate_Thumbnails;
use WP_Smart_Image_Resize\Singleton_Trait;

class Media_Library
{

  use Singleton_Trait;

  /**
   * Register
   * @return self
   */
  public function define_hooks()
  {
    add_action('restrict_manage_posts', array($this, 'render_filter_dropdown_list_view'), 10);
    add_action('pre_get_posts', array($this, 'filter_query_data_list_view'));
    add_filter('ajax_query_attachments_args', array($this, 'filter_query_data_grid_view'));
    add_action('admin_enqueue_scripts', array($this, 'enqueue_scripts_filter_dropdown_grid_view'), 15);
  }


  /**
   * Filter query to only show concerned images.
   * 
   * @return mixed
   */
  public function filter_query_data_grid_view($query)
  {

    if (!$this->is_filter_on()) {
      return $query;
    }

    $post_query = filter_input(INPUT_POST, 'query', FILTER_SANITIZE_STRING, FILTER_REQUIRE_ARRAY);

    if (!isset($post_query['_filter'])) {
      return $query;
    }

    // Processed images.
    if ($post_query['_filter'] === 'processed') {
      $query['meta_query'] = array(
        array(
          'key' => '_processed_at',
          'compare' => 'EXISTS'
        )
      );
    } elseif ($post_query['_filter'] === 'unprocessed') {
      $filter_processable = new Filter_Processable_Regenerate_Thumbnails;
      $image_ids = $filter_processable->filter_processable_images();

      $query['meta_query'] = array(
        array(
          'key' => '_processed_at',
          'compare' => 'NOT EXISTS'
        )
      );
      $query['post__in'] = $image_ids;
    }

    return $query;
  }


  /**
   * Enqueue scripts to show filter dropdown in the grid view.
   */
  public function enqueue_scripts_filter_dropdown_grid_view()
  {

    if (!$this->is_filter_on()) {
      return;
    }

    $current_screen = get_current_screen();

    if (empty($current_screen) || $current_screen->id !== 'upload') {
      return;
    }

    wp_localize_script('wp-smart-image-resize', 'sir_vars', array(
      'filter_strings'     => array(
        'all'     => 'Smart Resize: All images',
        'processed' => 'Smart Resize: Processed',
        'unprocessed' => 'Smart Resize: Not processed',
      )
    ));
  }

  function is_filter_on()
  {
    return apply_filters('wp_sir_enable_media_library_filter', true);
  }
  public function render_filter_dropdown_list_view($post_type)
  {

    if (!$this->is_filter_on()) {
      return;
    }

    if ($post_type !== 'attachment') {
      return;
    }

    $selected_filter = filter_input(INPUT_GET, 'wp_sir_filter', FILTER_SANITIZE_STRING);

?>

    <label for="wp-sir-filter" class="screen-reader-text">
      Filter by Smart Image Resize Status
    </label>
    <select class="wp-sir-filters" name="wp_sir_filter" id="wp-sir-filter">
      <option value="" <?php selected($selected_filter, ''); ?>>Smart Resize: All images</option>
      <option value="processed" <?php selected($selected_filter, 'processed'); ?>>Smart Resize: Processed</option>
      <option value="unprocessed" <?php selected($selected_filter, 'unprocessed'); ?>>Smart Resize: Not processed</option>
    </select>

<?php
  }

  public function filter_query_data_list_view($query)
  {

    if (!$this->is_filter_on()) {
      return;
    }


    global $current_screen;

    if (!is_admin() || (!empty($current_screen) &&  $current_screen->base !== 'upload')) {
      return;
    }

    if (!isset($_REQUEST['wp_sir_filter'])) {
      return;
    }

    switch ($_REQUEST['wp_sir_filter']) {
      case 'processed':
        $query->set('meta_query', [
          [
            'key' => '_processed_at',
            'compare' => 'EXISTS'
          ]
        ]);
        break;
      case 'unprocessed':
        // Only processable images.
        $filter_processable = new Filter_Processable_Regenerate_Thumbnails;
        $image_ids = $filter_processable->filter_processable_images();
        $query->set('post__in', $image_ids);

        $query->set('meta_query', array(array(
          'key' => '_processed_at',
          'compare' => 'NOT EXISTS'
        )));
        break;
    }
  }
}

Media_Library::instance()->define_hooks();
