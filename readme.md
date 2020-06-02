# API Absensi Online

Codeigniter API untuk Absensi Online

# Konfigurasi API 

- Buka folder `application/config/database.php` , lalu sesuaikan **hostname,username,password dan database** dengan punya kamu.

```

$db['default'] = array(
	'dsn'	=> '',
	'hostname' => 'localhost',
	'username' => 'root',
	'password' => '',
 	'database' => 'flutter_news',
	'dbdriver' => 'mysqli',
	'dbprefix' => '',
	'pconnect' => FALSE,
	'db_debug' => (ENVIRONMENT !== 'production'),
	'cache_on' => FALSE,
	'cachedir' => '',
	'char_set' => 'utf8',
	'dbcollat' => 'utf8_general_ci',
	'swap_pre' => '',
	'encrypt' => FALSE,
	'compress' => FALSE,
	'stricton' => TRUE,
	'failover' => array(),
	'save_queries' => TRUE
);

```
- Buat database pada **phpmyadmin** , kemudian import database yang ada di repository ke database yang baru dibuat.
