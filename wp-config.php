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

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'restaurant' );

/** MySQL database username */
define( 'DB_USER', 'root' );

/** MySQL database password */
define( 'DB_PASSWORD', 'mysql' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         'BIR.xO By}ZN_dgFfw[^Bx<V5I#^w2lxKJoYLoQ4[6|iiK9dkc8&l.VL2/6Q [-k' );
define( 'SECURE_AUTH_KEY',  'n}bhPP,j.BHX]PE6T)3yWbmRm ]5vh*!,lF*9P3O?w}+jt|(BC-hR!f.~NLYv,]~' );
define( 'LOGGED_IN_KEY',    '`gEhcGI;9IE,[#Wzco~)YIf-jp*/f@{*L|_xG.uw1{;/]rdc]H99Ng<oLD*;{oIO' );
define( 'NONCE_KEY',        'qK/ !u] 2C185YR4%Q|je[rqv*,eVR:132p5c15T{GfsQ59BBjQ}4=w mX,pXm@V' );
define( 'AUTH_SALT',        'ml|;]2-nVk3,zxKH*m9eL}Rx`,-+ka-qR_-tb8k9HdYzt&hc$<wEj}r_d-mHk)E%' );
define( 'SECURE_AUTH_SALT', 'N/?L<tYI_}{=(eAuiVbyM tCy;h(r>Bp7?I8PLB!{kO*d=T<|_sfcFQ{FtK&m`.V' );
define( 'LOGGED_IN_SALT',   'HX2-YAMz$J8nc[}+Y-h6=rFD!$AXXGJSv//xHPQ<t.O:~zZW&A-E-__T}Bbu3izg' );
define( 'NONCE_SALT',       'Q_C}sw@By,:ZC!t}<$JJ4~~o H36]~6~ca^19a!0T_PP,RpH:bi5G6f7.b6xHWKT' );

/**#@-*/

/**
 * WordPress Database Table prefix.
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
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define( 'WP_DEBUG', false );

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once( ABSPATH . 'wp-settings.php' );
