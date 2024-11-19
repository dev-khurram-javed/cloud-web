<?php
/**
 * Controls endpoints and features related to the REST API.
 *
 * @package Core
 * @subpackage REST API
 * @since 1.0.0
 */

/**
 * Retrieves a list of posts matching the specified query.
 *
 * @since 1.0.0
 * @internal
 *
 * @param WP_REST_Request $query
 * @return array
 */
function core_rest_get_posts( $query ) {
	$params = $query->get_json_params();
    // return $params;
	if ( ! $params || empty( $params ) ) return null;

    $post_type = $query->get_param('post_type');

    $component = (isset($params['component'])) ? $params['component'] : '';
    if ($component) unset($params['component']);

	// Get the list of posts.
	$post_query = new WP_Query( $params );
	
	$posts = $post_query->posts;
    wp_reset_postdata();

	$response = [
		'results' => $posts,
		'max_num_pages' => $post_query->max_num_pages,
        'total_posts' => $post_query->found_posts
	];

    if ($component) {
        foreach ($posts as $post) {
            $response['html'][] = component($component, ['post' => $post, 'fields' => $params['fields']], '', [], false);
        }
    }

	return rest_ensure_response($response ?: []);
}

/**
 * Registers custom REST API endpoints.
 *
 * @since 1.0.0
 * @internal
 */
function core_rest_register_endpoints() {

	// ? Endpoint: core/v1/posts
	register_rest_route( 'core/v1', '/posts', [
		'methods' => 'POST',
		'callback' => 'core_rest_get_posts',
		'permission_callback' => '__return_true'
	]);
}

add_action( 'rest_api_init', 'core_rest_register_endpoints' );
