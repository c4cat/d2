<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'dress');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         '(ui-(3q2),b/`t[H9+mOtz pcYueF8=qva%ZoN&z|0|*4#2q=X{+7P;;-~`~txKy');
define('SECURE_AUTH_KEY',  '|5:!w_BH)nUkS;e G_9U%fQQ}*,O*d=}yyP.R2@(C[4*`)g9*N_ [BO}<2^^M~hm');
define('LOGGED_IN_KEY',    'btQNg&UBF:V9Zc{I2&B.)Eo+x?86EFSOb6[ $+~,>:4La3%$gW28V%h4Wzf7d+a%');
define('NONCE_KEY',        '_Gnhhb{X9O b@Q4vMgzX6Q{2jb:q&_].d[Ivz(i>)@gz$.<<40&_,VfkF;6lLdHx');
define('AUTH_SALT',        'Xi ue/B@S6rz]<<{P 9)cNR<Gu T%r||TIX@A+,j+CYvl<xY|;tqE/.qsS~aE7?G');
define('SECURE_AUTH_SALT', ')d:Km`;g|z)u~?*Rj%_we~XiA.x*VaXi;Bc3XG[Vnj&N$B1:cO,;g?-.e.Bp/Z}y');
define('LOGGED_IN_SALT',   'q;~qb@06=|Qgw(!I|PMA^H;Z93ss*`.SB$Uve+tZ-i%d}DR+V&RpVo_jrFFi2?9i');
define('NONCE_SALT',       'F=mT$^JUrt`kx%|(=|5uvi|DAtwy|VBw?jt+MrdrhIPvm-OGa!:L0@e[-[J6rp)q');

/**#@-*/

// ** FTP SETTINGS FOR AUTO-UPDATE ** //
define('FTP_HOST', '162.243.149.107');
define('FTP_USER', 'root');
define('FTP_PASS', 'mzfzpztnlvwb');
define("FS_METHOD","direct");
define("FS_CHMOD_DIR", 0777);
define("FS_CHMOD_FILE", 0777);

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress. A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de_DE.mo to wp-content/languages and set WPLANG to 'de_DE' to enable German
 * language support.
 */
define('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
