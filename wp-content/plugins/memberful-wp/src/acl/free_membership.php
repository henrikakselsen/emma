<?php

function memberful_wp_get_post_available_to_any_registered_users( $post_id ) {
  return get_post_meta( $post_id, 'memberful_available_to_any_registered_user', TRUE ) === "1";
}

function memberful_wp_set_post_available_to_any_registered_users( $post_id, $is_viewable_by_any_registered_users ) {
  update_post_meta( $post_id, 'memberful_available_to_any_registered_user', $is_viewable_by_any_registered_users);

  $globally_viewable_by_by_any_registered_users = memberful_wp_get_all_posts_available_to_any_registered_user();

  if ( $is_viewable_by_any_registered_users ) {
    $globally_viewable_by_by_any_registered_users[$post_id] = $post_id;
  } else {
    unset($globally_viewable_by_by_any_registered_users[$post_id]);
  }

  update_option( 'memberful_posts_available_to_any_registered_user', $globally_viewable_by_by_any_registered_users );
}

function memberful_wp_get_all_posts_available_to_any_registered_user() {
  return get_option( 'memberful_posts_available_to_any_registered_user', array() );
}
