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
define( 'DB_NAME', 'royal' );

/** Database username */
define( 'DB_USER', 'royal' );

/** Database password */
define( 'DB_PASSWORD', 'Harry1212' );

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
define( 'AUTH_KEY',         'cY#!0uRo)SI)~UaN=+336&=lCIAqQgx/O e{nQrlSXVG,-{|muvI|G[%[6,WS|>D' );
define( 'SECURE_AUTH_KEY',  'aucTfZb?:}24y:0%wkLhMvc>#.uvK04>{m3qzJ{-}-%|xg]XY+{-v3xl.hfL.;u=' );
define( 'LOGGED_IN_KEY',    '.&OJ5w-xKd(,|ddbB}q_9b0 iPt/^aDlHrE`i{]J-=7|rZCngQv{pfp?DN1r;,nn' );
define( 'NONCE_KEY',        'iW:%3jXr_[imoLz_(},:sqw%5;Ec7)IwPQf@fDwGx7:Kgc4xY+&%oQTY,oEF^Q,~' );
define( 'AUTH_SALT',        '[^CAww1^Kb{p2lJ]XPP5V@tY|oJ{ssLFADZx4`BgfI61N%G|Ys=8I$^6T )+`#:p' );
define( 'SECURE_AUTH_SALT', '0@tiRmJM_gf?($3{O2h.8 A(zo[XDiFKJ:^?Pn&*rKtpyqk387q+f~|1#^/^^eMX' );
define( 'LOGGED_IN_SALT',   ')r[;^e1*H]lI%=Vu;1_T>0tSH-Vi$i49>bf[TOg+ge<@;-=a}^(EHm$>B~%q :+{' );
define( 'NONCE_SALT',       's/qt4[%YXiJgM~1Mp`Ok`Y@!0~}O ClnJqXO[;J [/bNJSX_qP+jMdMN0W=9IOSC' );

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
