<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'nova_ecom' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

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
define( 'AUTH_KEY',         '!>.$KV?7Owb5^QUoS@kp=y@1Qw}De2 {4}wqV;}#qa|ND$t3y@<Jmh[7I^?gk1#t' );
define( 'SECURE_AUTH_KEY',  'SUP[)qP`AjDP&Gru#W%_7K=eYXV@z&($}}Bw~98$lnNDC8(%I}NCxq!C!%#8hKI+' );
define( 'LOGGED_IN_KEY',    '! B-pZ,}fmi>GJkG![Y1)Z&wPY%TtZ[t`X+=F2SQa{6D1-edcOC(<cH+Hu^^t*?g' );
define( 'NONCE_KEY',        '7Pj8vfD-8UFq;8S[TB_{/-F:BdqGT9ljB:iOVRgN{s9AELfIM)[1^/,KEOE#Qi9P' );
define( 'AUTH_SALT',        '`e1)OI_@LT+hUY:8N{!E~SOnYHaO9wO++w+^1,;N0!4AXM6iWB!yp4VV]w6FPW<y' );
define( 'SECURE_AUTH_SALT', 'pZ+q8<RUkinc0mNo~7OT~8yNnJ1*|I?NOItb`&SZlFx0oaFG6j|  OW>t*6+*OxF' );
define( 'LOGGED_IN_SALT',   'x%6]LBA8cNQRf>olc4,6P2{?sT~uqf %R0osD$6zb^_?CbbN,~(vV82$sAf~N)ba' );
define( 'NONCE_SALT',       'bnUO-6GCRr8RKV|#O8O%mz=XaryMTTs%xu41N2r{KBN;mgA#G$AZ:t^(u&L1B[9f' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
