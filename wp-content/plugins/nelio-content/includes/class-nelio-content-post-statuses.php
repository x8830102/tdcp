<?php
/**
 * This file contains a class for registering new post types.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.2.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}//end if

/**
 * This class is used for registering new post types.
 *
 * Special thanks to Edit Flow's "Custom Statuses" module; most of the code
 * included here uses the ideas and hacks they created. The main difference
 * between our approach and theirs is that we don't allow user-defined
 * custom statuses.
 *
 * @package    Nelio_Content
 * @subpackage Nelio_Content/includes
 * @author     David Aguilera <david.aguilera@neliosoftware.com>
 * @since      1.2.9
 */
class Nelio_Content_Post_Statuses {

	/**
	 * The single instance of this class.
	 *
	 * @since  1.2.9
	 * @access protected
	 * @var    Nelio_Content_Post_Statuses
	 */
	protected static $_instance;

	/**
	 * Returns the single instance of this class.
	 *
	 * @return Nelio_Content_Post_Statuses the single instance of this class.
	 *
	 * @since  1.2.9
	 * @access public
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}//end if

		return self::$_instance;

	}//end instance()

	/**
	 * Adds all the filters and actions required for custom post statuses to work.
	 *
	 * @since  1.2.9
	 * @access public
	 */
	public function define_hooks() {

		add_action( 'init', array( $this, 'register_post_statuses' ) );

		// These methods are hacks for fixing bugs in WordPress core
		add_filter( 'wp_insert_post_data', array( $this, 'fix_custom_status_timestamp' ), 10, 2 );
		add_action( 'wp_insert_post', array( $this, 'fix_post_name' ), 10, 2 );
		add_filter( 'preview_post_link', array( $this, 'fix_preview_link_part_one' ) );
		add_filter( 'post_link', array( $this, 'fix_preview_link_part_two' ), 10, 3 );
		add_filter( 'page_link', array( $this, 'fix_preview_link_part_two' ), 10, 3 );
		add_filter( 'post_type_link', array( $this, 'fix_preview_link_part_two' ), 10, 3 );
		add_filter( 'get_sample_permalink', array( $this, 'fix_get_sample_permalink' ), 10, 5 );
		add_filter( 'get_sample_permalink_html', array( $this, 'fix_get_sample_permalink_html' ), 10, 5);

		if ( is_admin() ) {

			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'display_post_states', array( $this, 'display_post_statuses' ), 10, 2 );
			add_filter( 'post_row_actions', array( $this, 'fix_post_row_actions' ), 10, 2 );
			add_filter( 'page_row_actions', array( $this, 'fix_post_row_actions' ), 10, 2 );

			add_action( 'admin_init', array( $this, 'check_timestamp_on_publish' ) );

		}//end if

	}//end define_hooks()

	/**
	 * Enqueues the custom statuses scripts.
	 *
	 * @since  1.2.9
	 * @access public
	 */
	public function enqueue_scripts() {

		if ( ! $this->is_whitelisted_page() ) {
			return;
		}//end if

		// Get current user
		wp_get_current_user();

		// Enqueue the script and localize it.
		$aux = Nelio_Content();
		wp_enqueue_script(
			'nelio-content-custom-status',
			NELIO_CONTENT_ADMIN_URL . '/js/custom-status.min.js',
			array( 'jquery', 'underscore' ),
			$aux->get_version(),
			true
		);

		wp_localize_script(
			'nelio-content-custom-status',
			'NelioContentPostStatuses',
			$this->get_params_for_admin_script()
		);

	}//end enqueue_scripts()

	/**
	 * Register new post statuses.
	 *
	 * @since  1.2.9
	 * @access public
	 */
	public function register_post_statuses() {

		$statuses = $this->get_custom_statuses();
		foreach ( $statuses as $status => $attrs ) {
			register_post_status( $status, $attrs );
		}//end foreach

	}//end register_post_statuses()

	/**
	 * Modifies the actions in the post list table, depending on the status and the
	 * permissions of the current user.
	 *
	 * @param array   $actions list of available actions.
	 * @param WP_Post $post    the current post.
	 *
	 * @return array list of available actions.
	 *
	 * @since  1.2.9
	 * @access public
	 */
	public function fix_post_row_actions( $actions, $post ) {

		$screen = get_current_screen();
		if ( 'edit' !== $screen->base ) {
			return $actions;
		}//end if

		if ( ! $this->has_pre_publish_custom_status( $post ) ) {
			return $actions;
		}//end if

		// 'view' is only set if the user has permission to post
		if ( empty( $actions['view'] ) ) {
			return $actions;
		}//end if

		$actions['view'] = sprintf(
			'<a href="%s" title="%s" rel="permalink">%s</a>',
			esc_url( $this->get_preview_link( $post ) ),
			/* translators: a post title */
			esc_attr( sprintf( __( 'Preview &#8220;%s&#8221;' ), $post->post_title ) ),
			__( 'Preview' )
		);

		return $actions;

	}//end fix_post_row_actions()

	/**
	 *
	 * @since 1.3.4
	 */
	private function has_pre_publish_custom_status( $post ) {

		$statuses = array_keys( $this->get_custom_statuses() );
		if ( ! in_array( $post->post_status, $statuses ) ) {
			return false;
		}//end if

		$settings = Nelio_Content_Settings::instance();
		$post_types = $settings->get( 'calendar_post_types' );
		if ( ! in_array( $post->post_type, $post_types ) ) {
			return false;
		}//end if

		return true;

	}//end has_pre_publish_custom_status()

	/**
	 * XXX
	 *
	 * @since  1.2.9
	 * @access public
	 */
	public function display_post_statuses( $post_statuses, $post ) {

		$settings = Nelio_Content_Settings::instance();
		$post_types = $settings->get( 'calendar_post_types' );

		$statuses = $this->get_custom_statuses();
		if ( isset( $_GET['post_status'] ) ) {
			if ( in_array( $_GET['post_status'], array_keys( $statuses ) ) ) {
				return '';
			}//end if
		}//end if

		foreach ( $statuses as $status => $attrs ) {
			if ( in_array( $post->post_type, $post_types ) && $post->post_status === $status ) {
				return array( $status => $attrs['label'] );
			}//end if
		}//end foreach

		return $post_statuses;

	}//end display_post_statuses()

	/**
	 * This is a hack! hack! hack! until core is fixed/better supports custom statuses
	 *
	 * When publishing a post with a custom status, set the status to 'pending' temporarily
	 *
	 * @see Works around this limitation: http://core.trac.wordpress.org/browser/tags/3.2.1/wp-includes/post.php#L2694
	 * @see Original thread: http://wordpress.org/support/topic/plugin-edit-flow-custom-statuses-create-timestamp-problem
	 * @see Core ticket: http://core.trac.wordpress.org/ticket/18362
	 *
	 * @since 1.2.9
	 * @access public
	 */
	public function check_timestamp_on_publish() {

		global $pagenow, $wpdb;

		if ( $this->disable_custom_statuses_for_post_type() ) {
			return;
		}//end if

		// Handles the transition to 'publish' on edit.php
		if ( 'edit.php' === $pagenow && isset( $_REQUEST['bulk_edit'] ) ) {

			// For every post_id, set the post_status as 'pending' only when there's no timestamp set for $post_date_gmt
			if ( 'publish' === $_REQUEST['_status'] ) {

				$post_ids = array_map( 'intval', ( array ) $_REQUEST['post'] );
				foreach ( $post_ids as $post_id ) {
					$wpdb->update(
						$wpdb->posts,
						array( 'post_status' => 'pending' ),
						array( 'ID' => $post_id, 'post_date_gmt' => '0000-00-00 00:00:00' )
					);
					clean_post_cache( $post_id );
				}//end foreach

			}//end if

		}//end if

		// Handles the transition to 'publish' on post.php
		if ( 'post.php' === $pagenow && isset( $_POST['publish'] ) ) {

			// Set the post_status as 'pending' only when there's no timestamp set for $post_date_gmt
			if ( isset( $_POST['post_ID'] ) ) {

				$post_id = ( int ) $_POST['post_ID'];
				$ret = $wpdb->update(
					$wpdb->posts,
					array( 'post_status' => 'pending' ),
					array( 'ID' => $post_id, 'post_date_gmt' => '0000-00-00 00:00:00' )
				);
				clean_post_cache( $post_id );

				foreach ( array('aa', 'mm', 'jj', 'hh', 'mn') as $timeunit ) {
					if ( ! empty( $_POST[ 'hidden_' . $timeunit ] )
						&& $_POST[ 'hidden_' . $timeunit ] != $_POST[ $timeunit ] ) {
						$edit_date = '1';
						break;
					}//end if
				}//end foreach

				if ( $ret && empty( $edit_date ) ) {
					add_filter( 'pre_post_date', array( $this, 'helper_timestamp_hack' ) );
					add_filter( 'pre_post_date_gmt', array( $this, 'helper_timestamp_hack' ) );
				}//end if

			}//end if

		}//end if

	}//end check_timestamp_on_publish()

	/**
	 * PHP < 5.3.x doesn't support anonymous functions
	 * This helper is only used for the check_timestamp_on_publish method above
	 *
	 * @since 1.2.9
	 * @access public
	 */
	public function helper_timestamp_hack() {

		return ( 'pre_post_date' === current_filter() ) ? current_time('mysql') : '';

	}//end helper_timestamp_hack()

	/**
	 * This is a hack! hack! hack! until core is fixed/better supports custom statuses
	 *
	 * Normalize post_date_gmt if it isn't set to the past or the future
	 *
	 * @see Works around this limitation: https://core.trac.wordpress.org/browser/tags/4.5.1/src/wp-includes/post.php#L3182
	 * @see Original thread: http://wordpress.org/support/topic/plugin-edit-flow-custom-statuses-create-timestamp-problem
	 * @see Core ticket: http://core.trac.wordpress.org/ticket/18362
	 *
	 * @since 1.2.9
	 * @access public
	 */
	public function fix_custom_status_timestamp( $data, $postarr ) {

		if ( $this->disable_custom_statuses_for_post_type() ) {
			return $data;
		}//end if

		$status_slugs = array_keys( $this->get_custom_statuses() );

		//Post is scheduled or published? Ignoring.
		if ( ! in_array( $postarr['post_status'], $status_slugs ) ) {
			return $data;
		}//end if

		//If empty, keep empty.
		if ( empty( $postarr['post_date_gmt'] ) || '0000-00-00 00:00:00' === $postarr['post_date_gmt'] ) {
			$data['post_date_gmt'] = '0000-00-00 00:00:00';
		}//end if

		return $data;

	}//end fix_custom_status_timestamp()

	/**
	 * Another hack! hack! hack! until core better supports custom statuses
	 *
	 * @since 0.7.4
	 *
	 * Keep the post_name value empty for posts with custom statuses
	 * Unless they've set it customly
	 * @see https://github.com/danielbachhuber/Edit-Flow/issues/123
	 * @see http://core.trac.wordpress.org/browser/tags/3.4.2/wp-includes/post.php#L2530
	 * @see http://core.trac.wordpress.org/browser/tags/3.4.2/wp-includes/post.php#L2646
	 */
	public function fix_post_name( $post_id, $post ) {

		global $wpdb, $pagenow;
		if ( 'post.php' !== $pagenow ) {
			return;
		}//end if

		if ( ! $this->has_pre_publish_custom_status( $post ) ) {
			return;
		}//end if

		// The slug has been set by the meta box
		if ( ! empty( $_POST['post_name'] ) ) {
			return;
		}//end if

		$wpdb->update( $wpdb->posts, array( 'post_name' => '' ), array( 'ID' => $post_id ) );
		clean_post_cache( $post_id );

	}//end fix_post_name()

	/**
	 * Another hack! hack! hack! until core better supports custom statuses
	 *
	 * @since 0.7.4
	 *
	 * The preview link for an unpublished post should always be ?p=
	 */
	public function fix_preview_link_part_one( $preview_link ) {

		global $pagenow;
		if ( ! is_admin() || 'post.php' !== $pagenow ) {
			return $preview_link;
		}//end if

		$post = get_post( get_the_ID() );
		if ( ! $post ) {
			return $preview_link;
		}//end if

		if ( ! $this->has_pre_publish_custom_status( $post ) ) {
			return $preview_link;
		}//end if

		if ( strpos( $preview_link, 'preview_id' ) !== false
			|| $post->filter === 'sample' ) {
			return $preview_link;
		}//end if

		return $this->get_preview_link( $post );

	}//end fix_preview_link_part_one()

	/**
	 * Another hack! hack! hack! until core better supports custom statuses
	 *
	 * @since 0.7.4
	 *
	 * The preview link for an unpublished post should always be ?p=
	 * The code used to trigger a post preview doesn't also apply the 'preview_post_link' filter
	 * So we can't do a targeted filter. Instead, we can even more hackily filter get_permalink
	 * @see http://core.trac.wordpress.org/ticket/19378
	 */
	public function fix_preview_link_part_two( $permalink, $post, $sample ) {

		global $pagenow;

		if ( is_int( $post ) ) {
			$post = get_post( $post );
		}//end if

		// Should we be doing anything at all?
		$settings = Nelio_Content_Settings::instance();
		$post_types = $settings->get( 'calendar_post_types' );
		if ( ! in_array( $post->post_type, $post_types ) ) {
			return $permalink;
		}//end if

		// Is this published?
		if ( in_array( $post->post_status, array( 'publish', 'future', 'private' ) ) ) {
			return $permalink;
		}//end if

		// Are we overriding the permalink? Don't do anything
		if ( isset( $_POST['action'] ) && 'sample-permalink' === $_POST['action'] ) {
			return $permalink;
		}//end if

		// Are we previewing the post from the normal post screen?
		if( ( $pagenow == 'post.php' || $pagenow == 'post-new.php' )
			&& ! isset( $_POST['wp-preview'] ) ) {
			return $permalink;
		}//end if

		// If it's a sample permalink, not a preview
		if ( $sample ) {
			return $permalink;
		}

		return $this->get_preview_link( $post );

	}//end fix_preview_link_part_two()

	/**
	 * Fix get_sample_permalink. Previosuly the 'editable_slug' filter was leveraged
	 * to correct the sample permalink a user could edit on post.php. Since 4.4.40
	 * the `get_sample_permalink` filter was added which allows greater flexibility in
	 * manipulating the slug. Critical for cases like editing the sample permalink on
	 * hierarchical post types.
	 * @since 0.8.2
	 *
	 * @param string  $permalink Sample permalink
	 * @param int     $post_id   Post ID
	 * @param string  $title     Post title
	 * @param string  $name      Post name (slug)
	 * @param WP_Post $post      Post object
	 * @return string $link      Direct link to complete the action
	 */
	public function fix_get_sample_permalink( $permalink, $post_id, $title, $name, $post ) {

		//Should we be doing anything at all?
		$settings = Nelio_Content_Settings::instance();
		$post_types = $settings->get( 'calendar_post_types' );
		if ( ! in_array( $post->post_type, $post_types ) ) {
			return $permalink;
		}//end if

		//Is this published?
		if ( in_array( $post->post_status, array( 'publish', 'future', 'private' ) ) ) {
			return $permalink;
		}//end if

		//Are we overriding the permalink? Don't do anything
		if ( isset( $_POST['action'] ) && 'sample-permalink' === $_POST['action'] ) {
			return $permalink;
		}//end if

		list( $permalink, $post_name ) = $permalink;

		$post_name = $post->post_name ? $post->post_name : sanitize_title( $post->post_title );
		$post->post_name = $post_name;

		$ptype = get_post_type_object( $post->post_type );

		if ( $ptype->hierarchical ) {
			$post->filter = 'sample';

			$uri = get_page_uri( $post->ID ) . $post_name;

			if ( $uri ) {
				$uri = untrailingslashit( $uri );
				$uri = strrev( stristr( strrev( $uri ), '/' ) );
				$uri = untrailingslashit( $uri );
			}

			/** This filter is documented in wp-admin/edit-tag-form.php */
			$uri = apply_filters( 'editable_slug', $uri, $post );

			if ( ! empty($uri) ) {
				$uri .= '/';
			}//end if

			$permalink = str_replace( '%pagename%', "{$uri}%pagename%", $permalink );

		}//end if

		unset( $post->post_name );

		return array( $permalink, $post_name );

	}//end fix_get_sample_permalink()

	/**
	 * Hack to work around post status check in get_sample_permalink_html
	 *
	 *
	 * The get_sample_permalink_html checks the status of the post and if it's
	 * a draft generates a certain permalink structure.
	 * We need to do the same work it's doing for custom statuses in order
	 * to support this link
	 * @see https://core.trac.wordpress.org/browser/tags/4.5.2/src/wp-admin/includes/post.php#L1296
	 *
	 * @since 0.8.2
	 *
	 * @param string  $return    Sample permalink HTML markup
	 * @param int 	  $post_id   Post ID
	 * @param string  $new_title New sample permalink title
	 * @param string  $new_slug  New sample permalink kslug
	 * @param WP_Post $post 	 Post object
	 */
	public function fix_get_sample_permalink_html( $return, $post_id, $new_title, $new_slug, $post ) {

		$status_slugs = array_keys( $this->get_custom_statuses() );

		list( $permalink, $post_name ) = get_sample_permalink( $post->ID, $new_title, $new_slug );

		$view_link = false;
		$preview_target = '';

		if ( current_user_can( 'read_post', $post_id ) ) {
			if ( in_array( $post->post_status, $status_slugs ) ) {
				$view_link = $this->get_preview_link( $post );
				$preview_target = " target='wp-preview-{$post->ID}'";
			} else {
				if ( 'publish' === $post->post_status || 'attachment' === $post->post_type ) {
					$view_link = get_permalink( $post );
				} else {
					// Allow non-published (private, future) to be viewed at a pretty permalink.
					$view_link = str_replace( array( '%pagename%', '%postname%' ), $post->post_name, $permalink );
				}//end if
			}//end if
		}//end if

		// Permalinks without a post/page name placeholder don't have anything to edit
		if ( false === strpos( $permalink, '%postname%' ) && false === strpos( $permalink, '%pagename%' ) ) {
			$return = '<strong>' . __( 'Permalink:' ) . "</strong>\n";

			if ( false !== $view_link ) {
				$display_link = urldecode( $view_link );
				$return .= '<a id="sample-permalink" href="' . esc_url( $view_link ) . '"' . $preview_target . '>' . $display_link . "</a>\n";
			} else {
				$return .= '<span id="sample-permalink">' . $permalink . "</span>\n";
			}//end if

			// Encourage a pretty permalink setting
			if ( '' == get_option( 'permalink_structure' ) && current_user_can( 'manage_options' ) && !( 'page' == get_option('show_on_front') && $id == get_option('page_on_front') ) ) {
				$return .= '<span id="change-permalinks"><a href="options-permalink.php" class="button button-small" target="_blank">' . __('Change Permalinks') . "</a></span>\n";
			}//end if
		} else {
			if ( function_exists( 'mb_strlen' ) ) {
				if ( mb_strlen( $post_name ) > 34 ) {
					$post_name_abridged = mb_substr( $post_name, 0, 16 ) . '&hellip;' . mb_substr( $post_name, -16 );
				} else {
					$post_name_abridged = $post_name;
				}//end if
			} else {
				if ( mb_strlen( $post_name ) > 34 ) {
					$post_name_abridged = mb_substr( $post_name, 0, 16 ) . '&hellip;' . mb_substr( $post_name, -16 );
				} else {
					$post_name_abridged = $post_name;
				}//end if
			}//end if

			$post_name_html = '<span id="editable-post-name">' . $post_name_abridged . '</span>';
			$display_link = str_replace( array( '%pagename%', '%postname%' ), $post_name_html, urldecode( $permalink ) );

			$return = '<strong>' . __( 'Permalink:' ) . "</strong>\n";
			$return .= '<span id="sample-permalink"><a href="' . esc_url( $view_link ) . '"' . $preview_target . '>' . $display_link . "</a></span>\n";
			$return .= '&lrm;'; // Fix bi-directional text display defect in RTL languages.
			$return .= '<span id="edit-slug-buttons"><button type="button" class="edit-slug button button-small hide-if-no-js" aria-label="' . __( 'Edit permalink' ) . '">' . __( 'Edit' ) . "</button></span>\n";
			$return .= '<span id="editable-post-name-full">' . $post_name . "</span>\n";
		}//end if

		return $return;

	}//end fix_get_sample_permalink_html()

	/**
	 * Returns the friendly name for a given status.
	 *
	 * @param string $status_slug The status slug.
	 *
	 * @return string The friendly name for the status.
	 *
	 * @since 1.4.2
	 * @access public
	 */
	public function get_post_status_friendly_name( $status_slug ) {

		$statuses = $this->get_statuses();
		foreach ( $statuses as $status ) {
			if ( $status_slug === $status['name'] ) {
				return $status['label'];
			}//end if
		}//end foreach

		return _x( '(unknown)', 'text (status)', 'nelio-content' );

	}//end get_post_status_friendly_name()

	/**
	 * XXX
	 *
	 * @since  1.2.9
	 * @access private
	 */
	private function get_custom_statuses() {

		return array(
			'idea' => array(
				'label'                     => _x( 'Idea', 'post status', 'nelio-content' ),
				'protected'                 => true,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				/* translators: a number */
				'label_count'               => _n_noop( 'Idea <span class="count">(%s)</span>', 'Ideas <span class="count">(%s)</span>', 'post status', 'nelio-content' ),
			),
			'assigned' => array(
				'label'                     => _x( 'Assigned', 'post status', 'nelio-content' ),
				'protected'                 => true,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				/* translators: a number */
				'label_count'               => _n_noop( 'Assigned <span class="count">(%s)</span>', 'Assigned <span class="count">(%s)</span>', 'post status', 'nelio-content' ),
			),
			'in-progress' => array(
				'label'                     => _x( 'In Progress', 'post status', 'nelio-content' ),
				'protected'                 => true,
				'show_in_admin_all_list'    => true,
				'show_in_admin_status_list' => true,
				/* translators: a number */
				'label_count'               => _n_noop( 'In Progress <span class="count">(%s)</span>', 'In Progress <span class="count">(%s)</span>', 'post status', 'nelio-content' ),
			),
		);

	}//end get_custom_statuses()

	/**
	 * XXX
	 *
	 * @param WP_Post $post XXX.
	 *
	 * @return string XXX.
	 *
	 * @since  1.2.9
	 * @access private
	 */
	private function get_preview_link( $post ) {

		switch ( $post->post_type ) {

			case 'page':
				$args = array(
					'page_id' => $post->ID,
					'preview' => 'true',
				);
				break;

			case 'post':
				$args = array(
					'p'       => $post->ID,
					'preview' => 'true',
				);
				break;

			default:
				$args = array(
					'p'         => $post->ID,
					'post_type' => $post->post_type,
					'preview'   => 'true',
				);

		}//end switch

		return add_query_arg( $args, home_url() );

	}//end get_preview_link()

	/**
	 * Whether custom post statuses should be disabled for this post type.
	 *
	 * Used to stop custom statuses from being registered for post types that
	 * don't support them.
	 *
	 * @since 1.2.9
	 *
	 * @return bool
	 */
	private function disable_custom_statuses_for_post_type( $post_type = null ) {

		global $pagenow;

		// Only allow deregistering on 'edit.php' and 'post.php'
		if ( ! in_array( $pagenow, array( 'edit.php', 'post.php', 'post-new.php' ) ) ) {
			return false;
		}//end if

		if ( is_null( $post_type ) ) {
			$post_type = $this->get_current_post_type();
		}//end if

		$settings = Nelio_Content_Settings::instance();
		$post_types = $settings->get( 'calendar_post_types' );

		if ( $post_type && ! in_array( $post_type, $post_types ) ) {
			return true;
		}//end if

		return false;

	}//end disable_custom_statuses_for_post_type()

	/**
	 * Returns the current post type.
	 *
	 * @return string|null $post_type The post type we've found, or null if no post type.
	 *
	 * @since 1.2.9
	 * @access private
	 */
	private function get_current_post_type() {

		global $post, $typenow, $pagenow, $current_screen;

		$post_id = false;
		if ( isset( $_REQUEST['post'] ) ) {
			$post_id = absint( $_REQUEST['post'] );
		}//end if

		if ( $post && $post->post_type ) {
			$post_type = $post->post_type;
		} elseif ( $typenow ) {
			$post_type = $typenow;
		} elseif ( $current_screen && ! empty( $current_screen->post_type ) ) {
			$post_type = $current_screen->post_type;
		} elseif ( isset( $_REQUEST['post_type'] ) ) {
			$post_type = sanitize_key( $_REQUEST['post_type'] );
		} elseif ( 'post.php' === $pagenow && $post_id && ! empty( get_post( $post_id )->post_type ) ) {
			$post_type = get_post( $post_id )->post_type;
		} elseif ( 'edit.php' === $pagenow && empty( $_REQUEST['post_type'] ) ) {
			$post_type = 'post';
		} else {
			$post_type = null;
		}//end if

		return $post_type;

	}//end get_current_post_type()

	/**
	 * Check whether custom status stuff should be loaded on this page
	 *
	 * @return boolean XXX.
	 *
	 * @since 1.2.9
	 */
	private function is_whitelisted_page() {

		global $pagenow;

		if ( $this->disable_custom_statuses_for_post_type() ) {
			return false;
		}//end if

		$post_type = $this->get_current_post_type();
		if ( is_null( $post_type ) ) {
			return false;
		}//end if

		$post_type_obj = get_post_type_object( $post_type );
		if ( ! current_user_can( $post_type_obj->cap->edit_posts ) ) {
			return false;
		}//end if

		return in_array( $pagenow, array( 'post.php', 'edit.php', 'post-new.php', 'page.php', 'edit-pages.php', 'page-new.php' ) );

	}//end is_whitelisted_page()

	/**
	 * XXX
	 *
	 * @since  1.2.9
	 * @access private
	 */
	private function get_params_for_admin_script() {

		global $post;

		if ( empty( $post ) || ! $post->ID || 'auto-draft' === $post->post_status ) {

			$settings = Nelio_Content_Settings::instance();
			if ( $settings->get( 'use_custom_post_statuses' ) ) {
				$status = 'idea';
			} else {
				$status = 'draft';
			}//end if

		} else {

			$status = $post->post_status;

		}//end if

		$post_type_obj = get_post_type_object( $this->get_current_post_type() );
		return array(
			'canUserPublishPosts'       => current_user_can( $post_type_obj->cap->publish_posts ),
			'canUserEditPublishedPosts' => current_user_can( $post_type_obj->cap->edit_published_posts ),
			'currentStatus'             => $status,
			'i18n' => array(
				'noChange'  => '&mdash; ' . _x( 'No Change', 'text', 'nelio-content' ) . ' &mdash;',
				'ok'        => _x( 'OK', 'command', 'nelio-content' ),
				'cancel'    => _x( 'Cancel', 'command', 'nelio-content' ),
				'save'      => _x( 'Save', 'command', 'nelio-content' ),
				'published' => _x( 'Published', 'text', 'nelio-content' ),
			),
			'statuses' => $this->get_statuses(),
		);

	}//end get_params_for_admin_script()

	/**
	 * Get all statuses a post may have in WordPress (including custom ones).
	 *
	 * @return array Array of statuses
	 *
	 * @since  1.4.2
	 * @access private
	 */
	private function get_statuses() {
		return array(
			array(
				'name'   => 'private',
				'label'  => _x( 'Private', 'post status', 'nelio-content' ),
				'action' => _x( 'Save as Private', 'command', 'nelio-content' ),
			),
			array(
				'name'   => 'idea',
				'label'  => _x( 'Idea', 'post status', 'nelio-content' ),
				'action' => _x( 'Save Idea', 'command', 'nelio-content' ),
			),
			array(
				'name'   => 'assigned',
				'label'  => _x( 'Assigned', 'post status', 'nelio-content' ),
				'action' => _x( 'Save', 'command', 'nelio-content' ),
			),
			array(
				'name'   => 'in-progress',
				'label'  => _x( 'In Progress', 'post status', 'nelio-content' ),
				'action' => _x( 'Save', 'command', 'nelio-content' ),
			),
			array(
				'name'   => 'draft',
				'label'  => _x( 'Draft', 'post status', 'nelio-content' ),
				'action' => _x( 'Save Draft', 'command', 'nelio-content' ),
			),
			array(
				'name'   => 'pending',
				'label'  => _x( 'Pending', 'post status', 'nelio-content' ),
				'action' => _x( 'Save as Pending', 'command', 'nelio-content' ),
			),
			array(
				'name'   => 'future',
				'label'  => _x( 'Scheduled', 'post status', 'nelio-content' ),
				'action' => _x( 'Save', 'command', 'nelio-content' ),
			),
			array(
				'name'   => 'publish',
				'label'  => _x( 'Published', 'post status', 'nelio-content' ),
				'action' => _x( 'Save', 'command', 'nelio-content' ),
			),
		);
	}//end get_statuses()

}//end class
