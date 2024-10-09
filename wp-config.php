<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wp_alba' );

/** Database username */
define( 'DB_USER', 'Max_admin' );

/** Database password */
define( 'DB_PASSWORD', 'P@ssw0rd123456!' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY', '&5#ka]^m&beKfT2|6}lD7-:HC?,K}Mnfu+l8Yax,<`y@=,I$@o<W65XRrUN-Er~+' );
define( 'SECURE_AUTH_KEY', '6K$BVr6!4+bEhwKek4?> p>?tY?{_@8m K!-z}?t<Q)41|pE7w#!uh&6],8(z/P~' );
define( 'LOGGED_IN_KEY', '9x64XGs]MintAX%VQ7D/D.i*4L1&CBYSw!gc60!,O4jM^^0EM8*G} .]Gd9EfODk' );
define( 'NONCE_KEY', 'oT)$M?_3c.f/7Y9Hev-x8Vywe/ez/$IKlzFj-3!IiXrn&.bI{K`2:J1Pey4jr5AV' );
define( 'AUTH_SALT', '}-Su>ojqs7V#lNm~@w3P*4uZ$A@)L)GmeMq9PHJ=MQo!)15$|^@LfvX1K,J4WLnR' );
define( 'SECURE_AUTH_SALT', 'oS]<K[jn6MQ?X.`y2cawYo*@e9IZOm~%yJ{R!bF?2PH=6e[O8Wxn6UT+hF;@Mo{q' );
define( 'LOGGED_IN_SALT', '%NqPTjbU`ZeivK<x6p!YR0.7Ys|*aOL1MV_uQ2WaE!VXZydwTy{s}hq<ttz#$J+S' );
define( 'NONCE_SALT', 'VazCQ*tyY&E2TAr)*IxEHRIH*R3&/MPx~Y{0Ag,HS,Jtk/>.4hwvsGR0l- 0@pev' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'bad_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );
define( 'WP_DEBUG_LOG', false );
define( 'WP_DEBUG_DISPLAY', false );

/* Add any custom values between this line and the "stop editing" line. */


/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
