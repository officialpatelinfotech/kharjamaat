<?php
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
  require_once __DIR__ . '/vendor/autoload.php';
} else {
  die("Composer autoloader not found!");
}

// Load Dotenv for environment variables from .env
if (class_exists('Dotenv\Dotenv')) {
  try {
    $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
    $dotenv->safeLoad();
  } catch (Throwable $e) {
    // Non-fatal: continue if dotenv fails
  }
}

date_default_timezone_set('Asia/Kolkata');

/*
 *---------------------------------------------------------------
 * APPLICATION ENVIRONMENT
 *---------------------------------------------------------------
 */

// Handle CLI (php index.php ...)
if (php_sapi_name() === 'cli' || defined('STDIN')) {
  // Allow overriding env for cron/CLI runs (e.g. CI_ENV=production)
  $envFrom = function ($key) {
    $v = getenv($key);
    if ($v !== false && $v !== null && $v !== '') return $v;
    if (isset($_ENV[$key]) && $_ENV[$key] !== '') return $_ENV[$key];
    if (isset($_SERVER[$key]) && $_SERVER[$key] !== '') return $_SERVER[$key];
    return '';
  };

  $cliEnv = $envFrom('CI_ENV');
  if ($cliEnv === '') $cliEnv = $envFrom('ENVIRONMENT');
  if ($cliEnv === '') $cliEnv = $envFrom('APP_ENV');
  $cliEnv = is_string($cliEnv) ? strtolower(trim($cliEnv)) : '';
  if (in_array($cliEnv, ['development', 'testing', 'production'], true)) {
    define('ENVIRONMENT', $cliEnv);
  } else {
    define('ENVIRONMENT', 'development');
  }
} else {
  $host = $_SERVER['HTTP_HOST'] ?? 'production';

  if ($host === 'localhost' || $host === '127.0.0.1' || $host === '::1') {
    define('ENVIRONMENT', 'development');
  } else {
    define('ENVIRONMENT', 'production');
  }
}

/*
 *---------------------------------------------------------------
 * ERROR REPORTING
 *---------------------------------------------------------------
 */
switch (ENVIRONMENT) {
  case 'development':
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    break;

  case 'testing':
  case 'production':
    ini_set('display_errors', 0);
    if (version_compare(PHP_VERSION, '5.3', '>=')) {
      error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
    } else {
      error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
    }
    break;

  default:
    header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
    echo 'The application environment is not set correctly.';
    exit(1); // EXIT_ERROR
}

/*
 *---------------------------------------------------------------
 * SYSTEM DIRECTORY NAME
 *---------------------------------------------------------------
 */
$system_path = 'system';

/*
 *---------------------------------------------------------------
 * APPLICATION DIRECTORY NAME
 *---------------------------------------------------------------
 */
$application_folder = 'application';

/*
 *---------------------------------------------------------------
 * VIEW DIRECTORY NAME
 *---------------------------------------------------------------
 */
$view_folder = '';

/*
 * ---------------------------------------------------------------
 *  Resolve the system path
 * ---------------------------------------------------------------
 */
if (defined('STDIN')) {
  chdir(dirname(__FILE__));
}

if (($_temp = realpath($system_path)) !== FALSE) {
  $system_path = $_temp . DIRECTORY_SEPARATOR;
} else {
  $system_path = rtrim($system_path, '/\\') . DIRECTORY_SEPARATOR;
}

if (!is_dir($system_path)) {
  header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
  echo 'Your system folder path does not appear to be set correctly. Please open the following file and correct this: ' . pathinfo(__FILE__, PATHINFO_BASENAME);
  exit(3); // EXIT_CONFIG
}

/*
 * -------------------------------------------------------------------
 *  Main path constants
 * -------------------------------------------------------------------
 */
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
define('BASEPATH', $system_path);
define('FCPATH', dirname(__FILE__) . DIRECTORY_SEPARATOR);
define('SYSDIR', basename(BASEPATH));

if (is_dir($application_folder)) {
  if (($_temp = realpath($application_folder)) !== FALSE) {
    $application_folder = $_temp;
  } else {
    $application_folder = rtrim($application_folder, '/\\');
  }
} elseif (is_dir(BASEPATH . $application_folder . DIRECTORY_SEPARATOR)) {
  $application_folder = BASEPATH . rtrim($application_folder, '/\\');
} else {
  header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
  echo 'Your application folder path does not appear to be set correctly. Please open the following file and correct this: ' . SELF;
  exit(3); // EXIT_CONFIG
}
define('APPPATH', $application_folder . DIRECTORY_SEPARATOR);

if (!isset($view_folder[0]) && is_dir(APPPATH . 'views' . DIRECTORY_SEPARATOR)) {
  $view_folder = APPPATH . 'views';
} elseif (is_dir($view_folder)) {
  if (($_temp = realpath($view_folder)) !== FALSE) {
    $view_folder = $_temp;
  } else {
    $view_folder = rtrim($view_folder, '/\\');
  }
} elseif (is_dir(APPPATH . $view_folder . DIRECTORY_SEPARATOR)) {
  $view_folder = APPPATH . rtrim($view_folder, '/\\');
} else {
  header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
  echo 'Your view folder path does not appear to be set correctly. Please open the following file and correct this: ' . SELF;
  exit(3); // EXIT_CONFIG
}
define('VIEWPATH', $view_folder . DIRECTORY_SEPARATOR);

/*
 * --------------------------------------------------------------------
 * LOAD THE BOOTSTRAP FILE
 * --------------------------------------------------------------------
 */
require_once BASEPATH . 'core/CodeIgniter.php';
