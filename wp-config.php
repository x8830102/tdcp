<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'punplace' );

/** MySQL database username */
define( 'DB_USER', 'punplace' );

/** MySQL database password */
define( 'DB_PASSWORD', 'pun+place=punplace' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
/*define( 'AUTH_KEY',          '7!iG~QP58%aBF:d3GE!}m`_t !Dm2VnU:kp8GQV2lHnm(#E8t,-=21DLs<d>7QF}' );
define( 'SECURE_AUTH_KEY',   ' texp@i2$6R#El1JYh~{,4;<qLH`h/O,;H`wb>NFsn7NAscB(55/22W9Ngl`xkC:' );
define( 'LOGGED_IN_KEY',     'oBdqn]r7Qo?P0R4Z.~?WW5~/}e 3fE`9>&|)DdRs=#A.ArdEC:@,:z7bualCa{0F' );
define( 'NONCE_KEY',         'zFP>%=NM+[]5rjEA!ewd]$`5r*j2^Dht#%XPD! jskWHmFRS]L;7/xvSVDR9qG6_' );
define( 'AUTH_SALT',         'TTVLO_=3U6Eecckq$ZU1CETi_My|IfA8PiF5rU:]Gmub:$s}c9j+U#wV4L.iW1k]' );
define( 'SECURE_AUTH_SALT',  'K$|?^w43E[kBgU/&PRX@|%cEJ@UX-6J=#>qJp-<E9a||8=+ka{uB@ yD4fT`.k`%' );
define( 'LOGGED_IN_SALT',    '6/,a& qn|7;YiKhD:P!zSi3dB9{My60TQ-~M ?4yMi&xiDjz4fK=XjUpvFbs_{PH' );
define( 'NONCE_SALT',        'P&#ZP|;Wu?Uk$D4?,/G?tdkvpcdFAUw]7Zal<nelK0{4c;N%bi]ePxySm2BG<?J_' );
define( 'WP_CACHE_KEY_SALT', 'qP:e@Mi^PP@&#%$_Q%gH(jgm#CSM0sSe#;oO&@AzO}@9I412}bVjU+W! /xVq17<' );
*/
define('AUTH_KEY',         '-4lCI5?)c9>Cceo;*x1I9r6-(AE:@pWC!b@ HM/Vf4N69|MU`*dL$JPK+^z+d+]V');
define('SECURE_AUTH_KEY',  '8UQiM(Xg<~EYs83pO+!}8aiCLuOP7jQ`EnJl07-2a.iJsS!|Y&m-CI ]ig0@vy;]');
define('LOGGED_IN_KEY',    '7,!-cxJf|j&PT=:YsBN{f,l*H_+s;p!8/Ne^fe,$O#$*9^)wOVY$D_MK!Zm)k4n3');
define('NONCE_KEY',        'uY E/DQxx=W| /2PBKIYT1{$ h47zG/lv->x,{$PG6+:R^kwuSV|K#enT)VqdEmF');
define('AUTH_SALT',        '--99dS-+dP#PCGsccPm]D}{F)E))C#8uxf_ySEWJZU-D|T~G<-O]r+`DBpKN8>w-');
define('SECURE_AUTH_SALT', 'K/9AJG@>Xt3[<!HO)k}B,^OVjGM52C(/Ou%6kV.u</o*j{+Th-D-X1SuiO(>Vw+6');
define('LOGGED_IN_SALT',   'ib-<IkXZ|Sl<`3J@]Sep3a+3K)g+6GUsia`:DghV3x%82S ]V|qr8dICCu`/&H V');
define('NONCE_SALT',       'z9H%Dy-YMe60<f-$tr3R=hxMr|W(nE8o xV^:kr1k A;BC*9c9IaYY5]1W$3i+Lp');
/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/*cookie secure set*/
//@ini_set('session.cookie_httponly', true);
//@ini_set('session.cookie_secure', true);
//@ini_set('session.use_only_cookies', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
