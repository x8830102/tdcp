<?php
/**
 * The plugin uses several AJAX calls. This class implements feeds-related
 * admin AJAX callbacks.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     Antonio Villegas <antonio.villegas@neliosoftware.com>
 * @since      1.5.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * This class implements feeds-related admin AJAX callbacks.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     Antonio Villegas <antonio.villegas@neliosoftware.com>
 * @since      1.5.9
 *
 * @SuppressWarnings( PHPMD.CyclomaticComplexity, PHPMD.ExcessiveClassComplexity )
 */
class Nelio_Content_Feeds_Ajax_API {

	/**
	 * Registers all callbacks.
	 *
	 * @since  1.5.9
	 * @access public
	 */
	public function register_ajax_callbacks() {

		// These operations are available to all users with access to the plugin.
		add_action( 'wp_ajax_nelio_content_add_feed', array( $this, 'add_feed' ) );
		add_action( 'wp_ajax_nelio_content_rename_feed', array( $this, 'rename_feed' ) );
		add_action( 'wp_ajax_nelio_content_remove_feed', array( $this, 'remove_feed' ) );
		add_action( 'wp_ajax_nelio_content_get_feeds', array( $this, 'get_feeds' ) );
		add_action( 'wp_ajax_nelio_content_get_feed_items', array( $this, 'get_feed_items' ) );

	}//end register_ajax_callbacks()

	/**
	 * This AJAX endpoint stores a feed in the site.
	 *
	 * @since  1.5.9
	 * @access public
	 */
	public function add_feed() {

		include_once ABSPATH . WPINC . '/feed.php';

		if ( ! isset( $_REQUEST['url'] ) ) { // @codingStandardsIgnoreLine
			header( 'HTTP/1.1 400 Bad Request' );
			wp_send_json( _x( 'Feed URL is empty.', 'error', 'nelio-content' ) );
		}//end if

		$feed_url = sanitize_text_field( wp_unslash( $_REQUEST['url'] ) ); // @codingStandardsIgnoreLine

		$rss = fetch_feed( $feed_url );

		if ( is_wp_error( $rss ) ) {
			header( 'HTTP/1.1 500 Internal Server Error' );
			wp_send_json( _x( 'Error while processing feeds.', 'error', 'nelio-content' ) );
		}//end if

		$feed = array(
			'id'   => $rss->subscribe_url(),
			'name' => $rss->get_title(),
			'url'  => $rss->get_permalink(),
			'feed' => $rss->subscribe_url(),
			'icon' => $rss->get_image_url(),
		);

		if ( empty( $feed['name'] ) ) {
			$feed['name'] = $feed['id'];
		}//end if

		if ( empty( $feed['url'] ) ) {
			$feed['url'] = $feed['id'];
		}//end if

		$all_feeds = get_option( 'nc_feeds', array() );
		if ( ! in_array( $feed, $all_feeds, true ) ) {
			array_push( $all_feeds, $feed );
			update_option( 'nc_feeds', $all_feeds );
		}//end if

		wp_send_json( $feed );

	}//end add_feed()

	/**
	 * This AJAX endpoint stores a feed in the site.
	 *
	 * @since  1.5.9
	 * @access public
	 */
	public function rename_feed() {

		if ( ! isset( $_REQUEST['id'] ) ) { // @codingStandardsIgnoreLine
			header( 'HTTP/1.1 400 Bad Request' );
			wp_send_json( _x( 'Feed ID is empty.', 'error', 'nelio-content' ) );
		}//end if

		if ( ! isset( $_REQUEST['name'] ) || empty( $_REQUEST['name'] ) ) { // @codingStandardsIgnoreLine
			header( 'HTTP/1.1 400 Bad Request' );
			wp_send_json( _x( 'New feed name is empty.', 'error', 'nelio-content' ) );
		}//end if

		$feed_id = sanitize_text_field( wp_unslash( $_REQUEST['id'] ) ); // @codingStandardsIgnoreLine
		$name    = sanitize_text_field( wp_unslash( $_REQUEST['name'] ) ); // @codingStandardsIgnoreLine
		$feeds   = get_option( 'nc_feeds', array() );

		$feed = false;
		foreach ( $feeds as &$candidate ) {
			if ( $candidate['id'] === $feed_id ) {
				$feed = &$candidate;
			}//end if
		}//end foreach

		if ( ! $feed ) {
			header( 'HTTP/1.1 500 Internal Server Error' );
			wp_send_json( sprintf(
				/* translators: feed ID (a URL) */
				_x( 'Feed «%s» could not be found.', 'error', 'nelio-content' ),
				$feed_id
			) );
		}//end if

		$feed['name'] = $name;
		update_option( 'nc_feeds', $feeds );

		wp_send_json( $feed );

	}//end rename_feed()

	/**
	 * This AJAX endpoint removes a feed stored in the site.
	 *
	 * @since  1.5.9
	 * @access public
	 */
	public function remove_feed() {

		if ( ! isset( $_REQUEST['id'] ) ) { // @codingStandardsIgnoreLine
			header( 'HTTP/1.1 500 Internal Server Error' );
			wp_send_json( _x( 'Feed URL is empty.', 'error', 'nelio-content' ) );
		}//end if

		$feed_id = sanitize_text_field( wp_unslash( $_REQUEST['id'] ) ); // @codingStandardsIgnoreLine

		$all_feeds = get_option( 'nc_feeds', array() );
		foreach ( $all_feeds as $key => $val ) {
			if ( $val['id'] === $feed_id ) {
				$feed = $val;
				array_splice( $all_feeds, $key, 1 );
				break;
			}//end if
		}//end foreach

		update_option( 'nc_feeds', $all_feeds );
		wp_send_json( $feed );

	}//end remove_feed()

	/**
	 * This AJAX endpoint returns the feeds stored in the site.
	 *
	 * @since  1.5.9
	 * @access public
	 */
	public function get_feeds() {
		$feeds = get_option( 'nc_feeds', array() );
		wp_send_json( $feeds );
	}//end get_feeds()

	/**
	 * This AJAX endpoint returns the feed items according to the feeds stored in the site.
	 *
	 * @since  1.5.9
	 * @access public
	 */
	public function get_feed_items() {

		include_once ABSPATH . WPINC . '/feed.php';

		$rss_items = array();
		$result    = array();

		$start = 0;
		if ( isset( $_REQUEST['start'] ) ) { // @codingStandardsIgnoreLine
			$start = intval( wp_unslash( $_REQUEST['start'] ) ); // @codingStandardsIgnoreLine
		}//end if

		$feed_urls = 'all';
		if ( isset( $_REQUEST['feed'] ) ) { // @codingStandardsIgnoreLine
			$feed_urls = sanitize_text_field( wp_unslash( $_REQUEST['feed'] ) ); // @codingStandardsIgnoreLine
		}//end if

		if ( 'all' === $feed_urls ) {
			$feeds     = get_option( 'nc_feeds', array() );
			$feed_urls = array();

			foreach ( $feeds as $feed ) {
				array_push( $feed_urls, $feed['feed'] );
			}//end foreach
		}//end if

		$rss = fetch_feed( $feed_urls );

		if ( is_wp_error( $rss ) ) {
			header( 'HTTP/1.1 500 Internal Server Error' );
			wp_send_json( _x( 'Error while processing feeds.', 'error', 'nelio-content' ) );
		}//end if

		$max_items = $rss->get_item_quantity();
		$rss_items = $rss->get_items( $start, 10 );

		foreach ( $rss_items as $item ) {

			$feed = $item->get_feed();
			$item = array(
				'id'        => $item->get_permalink(),
				'title'     => $item->get_title(),
				'permalink' => $item->get_permalink(),
				'author'    => $this->prepare_authors( $item->get_authors() ),
				'content'   => $item->get_description(),
				'date'      => $item->get_date( get_option( 'date_format' ) ),
				'feed'      => array(
					'title'     => $feed->get_title(),
					'permalink' => $feed->get_permalink(),
					'image'     => $feed->get_image_url(),
				),
			);
			array_push( $result, $item );

		}//end foreach

		wp_send_json( array(
			'items' => $result,
			'total' => $max_items,
		) );

	}//end get_feed_items()

	/**
	 * Extract names from authors.
	 *
	 * @param  [array] $authors The authors as SimplePie_Author objects.
	 * @return [array]             The array of author names.
	 */
	private function prepare_authors( $authors ) {

		$result = array();
		foreach ( $authors as $author ) {
			array_push( $result, $author->get_name() );
		}//end foreach

		return $result;
	}//end prepare_authors()

	public static function sort_feeds_by_name( $a, $b ) {
		return strcasecmp( $a['name'], $b['name'] );
	}//end sort_feeds_by_name()

}//end class
