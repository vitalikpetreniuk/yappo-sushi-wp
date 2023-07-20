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
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'yappo' );

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
define( 'AUTH_KEY',         'm.Em7~RAUgC>yl9.;&rY#s*xl-K*&b~#bn2,i[xc#qM}`=Fm=] QL:;o*7CnCVyG' );
define( 'SECURE_AUTH_KEY',  '-v4xrR/%)1}gJ/rN13$$I*bU@_Jx2vz4c_ ^MafZHQI5)rE2B; !())lvYbdz~dR' );
define( 'LOGGED_IN_KEY',    'B=4T:[tyh(vR6YVAyqn$E`4W6B|E,s(ydJ{=mM)UUIflO%RjtIG#I~Y}NvYS^MX#' );
define( 'NONCE_KEY',        'p[v_5pOPp x=8@r[LY2a:RMV)h4$1VZ3^gy-$-nxJ8p!pq&s[;s70Sxt$Kk(`8p_' );
define( 'AUTH_SALT',        'tU9sg7(NfdvVY<66+;ZUjy_ow<wp?bM)VGXQ@?U[hyKE@ltyrsRz)nA#F.Xi9/N3' );
define( 'SECURE_AUTH_SALT', 'f]fmG.O3B![0}uIk(X|,ZikABaB7}q*Fzhu%]D}5|9/RBn2FZ`P3Em}c<6Ekt*wp' );
define( 'LOGGED_IN_SALT',   'qlOhodJc<I3<SS4Yj1rceU;G3Jqrxy&}n=S1/+nMW8-`D?E@f<E[X!v,|tV3N_|(' );
define( 'NONCE_SALT',       '{*dR7(i0D>Q^D(-kbIIH>EWBTq(GY4kB`)@-M(R2p3Sp-]}g`QEwMY?,>nGiH`nk' );

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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', true );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
