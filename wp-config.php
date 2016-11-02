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
define('DB_NAME', 'es');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'root');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         'rnR:U33-alt)7lk43hIA>#;JZ F2-Y*K%(;X`]b=Z@# /He^v%q`Y``p&{hJqKSQ');
define('SECURE_AUTH_KEY',  'NDkc{i*Q9]t`ALXS8>]T4t)jC0RJk)oNn!94VC%1vc:g:i,q@$p`EQhzd(<Ty)*o');
define('LOGGED_IN_KEY',    'bouXd~L,&58V`4lz&Zffm(das5^)#35w0gtZ6{YL^rjtgnRkG#3y3p<5diuRd<b2');
define('NONCE_KEY',        'p=sQUcETrlCM2tcpM,D8Xt&Y dwsS_J67y[n/<s93rE#I_b3,9E+|o<A}A8%})Zp');
define('AUTH_SALT',        'O12q|<Wa?hk;vO Yac.= z6y,5O-T%am=3!<tSt8onxaKx,44Rb9dZiKW^iLNnRx');
define('SECURE_AUTH_SALT', '$J+0WcL9MLA;qE#Qvx|fDEK[-{<A.+][wQ4PeF6co&jY@M3HV5?) rrBrGIV?[R+');
define('LOGGED_IN_SALT',   'c=#pF_%:vrcUD6V}3f?]b&*A<g QbB^<RyYhcBfM9KG<wQBDSAu8FmF_nC&L,d^L');
define('NONCE_SALT',       'Iyn4-[&e4 |2R,~0BNV* <s<?N:P*qFW`kc=lAk?TFCz<s=?9P.#7xd1:K#PMES!');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
