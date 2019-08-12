<?php
/**
 * This file contains a class with some post-related helper functions.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * This class implements post-related helper functions.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/admin
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.0.0
 *
 * @SuppressWarnings( PHPMD.ExcessiveClassComplexity )
 */
class Nelio_Content_Post_Helper {

	/**
	 * The single instance of this class.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    Nelio_Content_Post_Helper
	 */
	protected static $_instance;

	/**
	 * Cloning instances of this class is forbidden.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function __clone() {

		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'nelio-content' ), '1.0.0' ); // @codingStandardsIgnoreLine

	}//end __clone()


	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public function __wakeup() {

		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'nelio-content' ), '1.0.0' ); // @codingStandardsIgnoreLine

	}//end __wakeup()


	/**
	 * Returns the single instance of this class.
	 *
	 * @return Nelio_Content_Post_Helper the single instance of this class.
	 *
	 * @since  1.0.0
	 * @access public
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}//end if

		return self::$_instance;

	}//end instance()

	/**
	 * This function returns the suggested and external references of the post.
	 *
	 * @param integer|WP_Post $post The post whose reference we want or its ID.
	 *
	 * @return array an array with two lists: _suggested_ and _included_ references.
	 *
	 * @since  1.3.4
	 * @access public
	 *
	 * @SuppressWarnings( PHPMD.CyclomaticComplexity )
	 */
	public function get_all_references( $post = 0 ) {

		$result = array(
			'included'  => array(),
			'suggested' => array(),
		);

		if ( is_int( $post ) ) {
			$post = get_post( $post );
		}//end if

		if ( is_wp_error( $post ) ) {
			return $result;
		}//end if

		// Extract the URLs that are indeed included in the post content, as well as
		// the list of references that, apparently, are also included in the post.
		$urls = $this->extract_urls( $post->post_content );
		$reference_ids = nc_get_post_reference( $post->ID, 'included' );

		$references = array();
		foreach ( $reference_ids as $id ) {
			$reference = new Nelio_Content_Reference( $id );
			$meta = nc_get_suggested_reference_meta( $id, $post->ID );
			if ( ! empty( $meta ) ) {
				$reference->mark_as_suggested( $meta['advisor'], $meta['date'] );
			}//end if
			array_push( $references, $reference );
		}//end foreach

		// And make sure these references are really included in the post.
		foreach ( $references as $ref ) {

			if ( in_array( $ref->get_url(), $urls, true ) ) {

				$aux = $ref->json_encode();
				array_push( $result['included'], $aux );

			} else {

				nc_delete_post_reference( $post->ID, $ref->ID );

			}//end if

		}//end foreach

		// Now let's get all the suggested references.
		$reference_ids = nc_get_post_reference( $post->ID, 'suggested' );

		foreach ( $reference_ids as $id ) {
			$reference = new Nelio_Content_Reference( $id );
			$meta = nc_get_suggested_reference_meta( $id, $post->ID );
			if ( ! empty( $meta ) ) {
				$reference->mark_as_suggested( $meta['advisor'], $meta['date'] );
			}//end if
			array_push( $result['suggested'], $reference->json_encode() );
		}//end foreach

		return $result;

	}//end get_all_references()

	/**
	 * This function returns a list with the domains that shouldn't be considered
	 * as references.
	 *
	 * @return array an array with the external references
	 *
	 * @since  1.3.4
	 * @access public
	 *
	 * @SuppressWarnings( PHPMD.CyclomaticComplexity )
	 */
	public function get_non_reference_domains() {

		/**
		 * List of domain names that shouldn't be considered as external references.
		 *
		 * @param array domains list of domain names that shouldn't be considered as
		 *                      external references. It accepts the star (*) char as
		 *                      a wildcard.
		 *
		 * @since 1.3.4
		 */
		return apply_filters( 'nelio_content_non_reference_domains', array(
			'bing.*',
			'*.bing.*',
			'flickr.com',
			'giphy.com',
			'google.*',
			'*.google.*',
			'linkedin.com',
			'unsplash.com',
			'twitter.com',
			'facebook.com',
		) );

	}//end get_non_reference_domains()

	/**
	 * Modifies the metas of the given post, so that it won't be automatically reshared.
	 *
	 * @param int $post_id Post ID.
	 *
	 * @since  1.4.2
	 * @access public
	 */
	public function exclude_from_reshare( $post_id ) {

		delete_post_meta( $post_id, '_nc_include_in_reshare' );

		$settings = Nelio_Content_Settings::instance();
		if ( 'include-in-reshare' === $settings->get( 'auto_reshare_default_mode' ) ) {
			update_post_meta( $post_id, '_nc_exclude_from_reshare', true );
		}//end if

	}//end exclude_from_reshare()

	/**
	 * Modifies the metas of the given post, so that it can be automatically reshared.
	 *
	 * @param int $post_id Post ID.
	 *
	 * @since  1.4.2
	 * @access public
	 */
	public function include_in_reshare( $post_id ) {

		delete_post_meta( $post_id, '_nc_exclude_from_reshare' );

		$settings = Nelio_Content_Settings::instance();
		if ( 'exclude-from-reshare' === $settings->get( 'auto_reshare_default_mode' ) ) {
			update_post_meta( $post_id, '_nc_include_in_reshare', true );
		}//end if

	}//end include_in_reshare()

	/**
	 * Returns whether the post is excluded from reshare or not.
	 *
	 * @param int $post_id Post ID.
	 *
	 * @return boolean whether the post is excluded from reshare or not.
	 *
	 * @since  1.4.2
	 * @access public
	 */
	public function is_post_excluded_from_reshare( $post_id ) {

		$settings = Nelio_Content_Settings::instance();
		if ( 'include-in-reshare' === $settings->get( 'auto_reshare_default_mode' ) ) {
			$excluded = get_post_meta( $post_id, '_nc_exclude_from_reshare', true );
			return ! empty( $excluded );
		} else {
			$included = get_post_meta( $post_id, '_nc_include_in_reshare', true );
			return empty( $included );
		}//end if

	}//end is_post_excluded_from_reshare()

	/**
	 * Sets users to follow specified post.
	 *
	 * @param int $post ID of the post.
	 *
	 * @since  1.4.2
	 * @access public
	 */
	public function add_default_followers( $post ) {

		$post = get_post( $post );
		if ( ! $post ) {
			return;
		}//end if

		// Add current user to following users.
		$user = wp_get_current_user();
		if ( $user && apply_filters( 'nelio_content_notification_auto_subscribe_current_user', true, 'subscription_action' ) ) {
			nc_add_post_meta_once( $post->ID, '_nc_following_users', $user->ID );
		}//end if

		// Add post author to following users.
		if ( apply_filters( 'nelio_content_notification_auto_subscribe_post_author', true, 'subscription_action' ) ) {
			nc_add_post_meta_once( $post->ID, '_nc_following_users', $post->post_author );
		}//end if

	}//end add_default_followers()

	/**
	 * Sets users to follow specified post.
	 *
	 * @param int   $post  ID of the post.
	 * @param array $users User IDs that follow the post.
	 *
	 * @return boolean true on success and false on failure
	 *
	 * @since  1.4.2
	 * @access public
	 */
	public function save_post_followers( $post, $users = null ) {

		$post = get_post( $post );
		if ( ! $post ) {
			return new WP_Error( 'missing-post', _x( 'The post does not exist', 'error', 'nelio-content' ) );
		}//end if

		if ( ! is_array( $users ) ) {
			$users = array();
		}//end if

		// Add current user to following users.
		$user = wp_get_current_user();
		if ( $user && apply_filters( 'nelio_content_notification_auto_subscribe_current_user', true, 'subscription_action' ) ) {
			$users[] = $user->ID;
		}//end if

		// Add post author to following users.
		if ( apply_filters( 'nelio_content_notification_auto_subscribe_post_author', true, 'subscription_action' ) ) {
			$users[] = $post->post_author;
		}//end if

		$users = array_unique( array_map( 'intval', $users ) );

		return nc_update_post_meta_array( $post->ID, '_nc_following_users', $users );

	}//end save_post_followers()

	/**
	 * This function creates a ncselect2-ready object with (a) the current post
	 * in the loop or (b) the post specified in `$post_id`.
	 *
	 * @param integer $post_id The ID of the post we want to stringify.
	 *
	 * @return array a ncselect2-ready object with (a) the current post in the
	 *               loop or (b) the post specified in `$post_id`.
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 * @SuppressWarnings( PHPMD.CyclomaticComplexity )
	 */
	public function post_to_json( $post_id ) {

		$post = get_post( $post_id );
		if ( is_wp_error( $post ) || ! $post ) {
			return false;
		}//end if

		$analytics = Nelio_Content_Analytics_Helper::instance();
		$result = array(
			'id'                  => $post_id,
			'author'              => absint( $post->post_author ),
			'authorName'          => $this->get_the_author( $post ),
			'calendarKind'        => 'post',
			'categories'          => $this->get_the_categories( $post ),
			'date'                => $this->get_post_time( $post, false ),
			'editLink'            => $this->get_edit_post_link( $post ),
			'excerptFormatted'    => $this->get_the_excerpt( $post ),
			'excludedFromReshare' => $this->is_post_excluded_from_reshare( $post_id ),
			'followers'           => $this->get_post_followers( $post ),
			'image'               => $this->get_post_thumbnail( $post, false ),
			'imageId'             => $this->get_post_thumbnail_id( $post->ID ),
			'permalink'           => $this->get_permalink( $post ),
			'statistics'          => $analytics->get_post_stats( $post_id ),
			'status'              => $post->post_status,
			'thumbnail'           => $this->get_featured_thumb( $post ),
			'titleFormatted'      => $this->get_the_title( $post ),
			'type'                => $post->post_type,
			'typeName'            => $this->get_post_type_name( $post ),
		);

		return $result;

	}//end post_to_json()

	/**
	 * This function creates an AWS-ready post object.
	 *
	 * @param integer $post_id The ID of the post we want to stringify.
	 *
	 * @return array an AWS-ready post object.
	 *
	 * @since  1.4.5
	 * @access public
	 */
	public function post_to_aws_json( $post_id ) {

		$post = get_post( $post_id );
		if ( is_wp_error( $post ) || ! $post ) {
			return false;
		}//end if

		$result = array(
			'id'                  => $post_id,
			'date'                => $this->get_post_time( $post, 'none' ),
			'title'               => html_entity_decode( wp_strip_all_tags( $this->get_the_title( $post ) ), ENT_HTML5 ),
			'excerpt'             => html_entity_decode( wp_strip_all_tags( $this->get_the_excerpt( $post ) ), ENT_HTML5 ),
			'author'              => absint( $post->post_author ),
			'categories'          => wp_list_pluck( $this->get_the_categories( $post ), 'slug' ),
			'content'             => $this->get_the_content( $post ),
			'tags'                => $this->get_tags( $post ),
			'images'              => $this->get_images( $post ),
			'featuredImage'       => $this->get_post_thumbnail( $post, 'none' ),
			'permalink'           => $this->get_permalink( $post ),
			'references'          => $this->get_external_references( $post ),
			'status'              => $post->post_status,
			'timezone'            => nc_get_timezone( $post ),
			'excludedFromReshare' => $this->is_post_excluded_from_reshare( $post_id ),
			'type'                => $post->post_type,
			'networkImages' => array(
				'facebook'   => apply_filters( 'nelio_content_facebook_featured_image', false, $post_id ),
				'googleplus' => apply_filters( 'nelio_content_googleplus_featured_image', false,  $post_id ),
				'instagram'  => apply_filters( 'nelio_content_instagram_featured_image', false,  $post_id ),
				'linkedin'   => apply_filters( 'nelio_content_linkedin_featured_image', false,  $post_id ),
				'pinterest'  => apply_filters( 'nelio_content_pinterest_featured_image', false,  $post_id ),
				'twitter'    => apply_filters( 'nelio_content_twitter_featured_image', false,  $post_id ),
			),
		);

		return $result;

	}//end post_to_aws_json()

	/**
	 * This function returns whether the given post has changed since the last update or not.
	 *
	 * @param integer $post_id the post ID.
	 *
	 * @return boolean whether the post has changed since the last update or not.
	 *
	 * @since  1.6.8
	 * @access public
	 */
	public function has_post_changed_since_last_sync( $post_id ) {

		$new_hash = $this->get_post_hash( $post_id );
		$old_hash = get_post_meta( $post_id, '_nc_sync_hash', true );

		return $new_hash && $new_hash !== $old_hash;

	}//end has_post_changed_since_last_sync()

	/**
	 * This function adds a custom meta so that we know that the post, as is right now, has been synched with AWS.
	 *
	 * @param integer $post_id the post ID.
	 *
	 * @since  1.6.8
	 * @access public
	 */
	public function mark_post_as_synched( $post_id ) {

		$hash = $this->get_post_hash( $post_id );
		if ( $hash ) {
			update_post_meta( $post_id, '_nc_sync_hash', $hash );
		}//end if

	}//end mark_post_as_synched()

	private function get_post_hash( $post_id ) {

		$post = $this->post_to_aws_json( $post_id );
		if ( ! $post ) {
			return false;
		}//end if

		unset( $post['content'] );
		$post = array_map( function( $value ) {
			if ( is_array( $value ) ) {
				sort( $value );
			}//end if
			return $value;
		}, $post );

		$post['date'] = substr( $post['date'], 0 ,strlen( 'YYYY-MM-DDThh:mm' ) );

		$encoded_post = wp_json_encode( $post );
		if ( empty( $encoded_post ) ) {
			return false;
		}//end if

		return md5( $encoded_post );

	}//end get_post_hash()

	private function find_root_category( $term_id, $categories = false ) {

		if ( ! $categories ) {
			$categories = get_categories( array( 'hide_empty' => false ) );
		}//end if

		foreach ( $categories as $cat ) {
			if ( $cat->term_id === $term_id ) {
				if ( 0 === $cat->parent ) {
					return $cat;
				} else {
					return $this->find_root_category( $cat->parent, $categories );
				}//end if
			}//end if
		}//end foreach

		return false;

	}//end find_root_category()

	private function get_the_author( $post ) {

		return get_the_author_meta( 'display_name', $post->post_author );

	}//end get_the_author()

	private function get_the_categories( $post ) {

		$aux = get_the_category( $post->ID );
		$processed_categories = array();
		$categories = array();

		foreach ( $aux as $cat ) {

			$root_cat = $this->find_root_category( $cat->term_id );
			if ( $root_cat && ! in_array( $root_cat->term_id, $processed_categories, true ) ) {
				array_push( $processed_categories, $root_cat->term_id );
				array_push( $categories, array(
					'id'   => $root_cat->term_id,
					'slug' => $root_cat->slug,
					'name' => $root_cat->name,
				) );
			}//end if

		}//end foreach

		return $categories;

	}//end get_the_categories()

	private function get_post_thumbnail( $post, $default ) {

		$aux = Nelio_Content_External_Featured_Image_Helper::instance();
		$settings = Nelio_Content_Settings::instance();

		$featured_image = $aux->get_external_featured_image( $post->ID );

		if ( empty( $featured_image ) && $this->get_post_thumbnail_id( $post->ID ) ) {
			$featured_image = wp_get_attachment_url( $this->get_post_thumbnail_id( $post->ID ) );
		}//end if

		$auto_feat_image = $settings->get( 'auto_feat_image' );
		if ( empty( $featured_image ) && 'disabled' !== $auto_feat_image ) {
			$featured_image = $aux->get_auto_featured_image( $post->ID, $auto_feat_image );
		}//end if

		if ( empty( $featured_image ) ) {
			$featured_image = $default;
		}//end if

		return $featured_image;

	}//end get_post_thumbnail()

	private function get_featured_thumb( $post ) {

		$aux = Nelio_Content_External_Featured_Image_Helper::instance();
		$settings = Nelio_Content_Settings::instance();

		$featured_thumb = $aux->get_external_featured_image( $post->ID );

		if ( empty( $featured_thumb ) && $this->get_post_thumbnail_id( $post->ID ) ) {
			$featured_thumb = wp_get_attachment_thumb_url( $this->get_post_thumbnail_id( $post->ID ) );
		}//end if

		$position = $settings->get( 'auto_feat_image' );
		if ( empty( $featured_thumb ) && 'disabled' !== $position ) {
			$featured_thumb = $aux->get_auto_featured_image( $post->ID, $position );
		}//end if

		if ( empty( $featured_thumb ) ) {
			$featured_thumb = NELIO_CONTENT_ADMIN_URL . '/images/default-featured-image-thumbnail.png';
		}//end if

		return $featured_thumb;

	}//end get_featured_thumb()

	private function get_post_thumbnail_id( $post_id ) {

		$post_thumbnail_id = get_post_meta( $post_id, '_thumbnail_id', true );
		if ( empty( $post_thumbnail_id ) ) {
			$post_thumbnail_id = 0;
		}//end if

		return $post_thumbnail_id;

	}//end get_post_thumbnail_id()

	private function get_post_type_name( $post ) {

		$post_type_name = _x( 'Post', 'text (default post type name)', 'nelio-content' );
		$post_type = get_post_type_object( $post->post_type );
		if ( ! empty( $post_type ) && isset( $post_type->labels ) && isset( $post_type->labels->singular_name ) ) {
			$post_type_name = $post_type->labels->singular_name;
		}//end if

		return $post_type_name;

	}//end get_post_type_name()

	private function get_the_title( $post ) {

		/**
		 * Modifies the title of the post.
		 *
		 * @param string $title   the title.
		 * @param int    $post_id the ID of the post.
		 *
		 * @since 1.0.0
		 */
		return apply_filters( 'nelio_content_post_title', apply_filters( 'the_title', $post->post_title, $post->ID ), $post->ID );

	}//end get_the_title()

	private function get_the_excerpt( $post ) {

		if ( ! empty( $post->post_excerpt ) ) {
			$excerpt = $post->post_excerpt;
		} else {
			$excerpt = '';
		}//end if

		/**
		 * Modifies the excerpt of the post.
		 *
		 * @param string $excerpt the excerpt.
		 * @param int    $post_id the ID of the post.
		 *
		 * @since 1.0.0
		 */
		return apply_filters( 'nelio_content_post_excerpt', $excerpt, $post->ID );

	}//end get_the_excerpt()

	private function get_post_time( $post, $default ) {

		$timezone = date_default_timezone_get();
		date_default_timezone_set( 'UTC' );

		$date = ' ' . $post->post_date_gmt;
		if ( strpos( $date, '0000-00-00' ) ) {
			$date = $default;
		} else {
			$date = get_post_time( 'c', true, $post );
		}//end if

		date_default_timezone_set( $timezone );

		return $date;

	}//end get_post_time()

	private function get_edit_post_link( $post ) {

		$link = get_edit_post_link( $post->ID, 'default' );
		if ( empty( $link ) ) {
			$link = '';
		}//end if

		return $link;

	}//end get_edit_post_link()

	private function get_permalink( $post ) {

		$permalink = get_permalink( $post );
		if ( 'publish' !== $post->post_status ) {
			$aux = clone $post;
			$aux->post_status = 'publish';
			if ( empty( $aux->post_name ) ) {
				$aux->post_name = sanitize_title( $aux->post_title, $aux->ID );
			}//end if
			$aux->post_name = wp_unique_post_slug( $aux->post_name, $aux->ID, 'publish', $aux->post_type, $aux->post_parent );
			$permalink = get_permalink( $aux );
		}//end if
		$permalink = apply_filters( 'nelio_content_post_permalink', $permalink, $post->ID );

		return $permalink;

	}//end get_permalink()

	private function get_post_followers( $post ) {

		$follower_ids = get_post_meta( $post->ID, '_nc_following_users', false );
		$follower_ids = array_map( 'intval', $follower_ids );

		$aux = Nelio_Content_User_Helper::instance();
		$followers = array();
		foreach ( $follower_ids as $follower_id ) {
			$follower = $aux->user_to_json( $follower_id );
			if ( $follower ) {
				array_push( $followers, $follower );
			}//end if
		}//end foreach

		return $followers;

	}//end get_post_followers()

	private function extract_urls( $content ) {

		// Extract all the URLs.
		preg_match_all(
			'#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#',
			$content,
			$matches
		);

		if ( count( $matches ) > 0 ) {
			return $matches[0];
		} else {
			return array();
		}//end if

	}//end extract_urls()

	private function is_external_reference( $url, $non_ref_domains ) {

		// Internal URLs are not external.
		if ( 0 === strpos( $url, get_home_url() ) ) {
			return false;
		}//end if

		// Discard any URL that is an external reference.
		foreach ( $non_ref_domains as $pattern ) {
			if ( preg_match( $pattern, $url ) ) {
				return false;
			}//end if
		}//end if

		return true;

	}//end is_external_reference()

	private function get_tags( $post ) {

		if ( 'post' !== $post->post_type ) {
			return array();
		}//end if

		$tags = get_the_tags( $post->ID );
		if ( is_array( $tags ) && count( $tags ) > 0 ) {
			$tags = wp_list_pluck( $tags, 'name' );
		} else {
			$tags = array();
		}//end if

		foreach ( $tags as $i => $tag ) {
			$tags[ $i ] = preg_replace( '/ /', '', ucwords( $tag ) );
		}//end foreach

		return $tags;

	}//end get_tags()

	private function get_the_content( $post ) {

		return apply_filters( 'the_content', $post->post_content );

	}//end get_the_content()

	private function get_images( $post ) {

		$content = $this->get_the_content( $post );
		preg_match_all( '/<img[^>]+>/i',$content, $matches );

		$result = array();
		foreach ( $matches[0] as $img ) {

			preg_match_all( '/src=("[^"]*"|\'[^\']*\')/i', $img, $aux );

			if ( count( $aux ) <= 1 ) {
				continue;
			}//end if

			$url = $aux[1][0];
			$url = substr( $url, 1, strlen( $url ) - 2 );
			array_push( $result, $url );

		}//end foreach

		shuffle( $result );
		return array_slice( $result, 0, 10 );

	}//end get_images()

	private function get_external_references( $post ) {

		$result = array();
		$references = $this->get_all_references( $post );

		$non_ref_domains = $this->get_non_reference_domains();
		$count = count( $non_ref_domains );
		for ( $i = 0; $i < $count; ++$i ) {
			$pattern = $non_ref_domains[ $i ];
			$pattern = str_replace( '.', '\\.', $pattern );
			$pattern = preg_replace( '/\*$/', '[^\/]+', $pattern );
			$pattern = str_replace( '*', '[^\/]*', $pattern );
			$pattern = '/^[^:]+:\/\/[^\/]*' . $pattern . '/';
			$non_ref_domains[ $i ] = $pattern;
		}//end for

		foreach ( $references['included'] as $reference ) {

			if ( ! $this->is_external_reference( $reference['url'], $non_ref_domains ) ) {
				continue;
			}//end if

			array_push( $result, array(
				'url'     => $reference['url'],
				'author'  => $reference['author'],
				'title'   => $reference['title'],
				'twitter' => $reference['twitter'],
			) );

		}//end foreach

		return $result;

	}//end get_external_references()

}//end class
