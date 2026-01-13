<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cookies extends CI_Controller
{
    private const CONSENT_COOKIE_NAME = 'km_cookie_consent';

    public function index()
    {
        show_404();
    }

    /**
     * Sets cookie consent flag.
     * Endpoint: /cookies/accept
     */
    public function accept()
    {
        $cookieName = self::CONSENT_COOKIE_NAME;
        $cookieValue = '1';
        $expires = time() + (365 * 24 * 60 * 60);

        $cookiePath = config_item('cookie_path') ?: '/';
        $cookieDomain = (string)(config_item('cookie_domain') ?: '');
        $cookieSecure = (bool)(config_item('cookie_secure') ?: FALSE);
        $cookieHttpOnly = (bool)(config_item('cookie_httponly') ?: TRUE);
        $cookieSameSite = (string)(config_item('cookie_samesite') ?: 'Lax');

        $cookieSameSite = ucfirst(strtolower($cookieSameSite));
        if (!in_array($cookieSameSite, ['Lax', 'Strict', 'None'], true)) {
            $cookieSameSite = 'Lax';
        }
        if ($cookieSameSite === 'None') {
            $cookieSecure = true;
        }

        $ok = false;

        if (PHP_VERSION_ID >= 70300) {
            $ok = setcookie($cookieName, $cookieValue, [
                'expires' => $expires,
                'path' => $cookiePath,
                'domain' => ($cookieDomain !== '' ? $cookieDomain : null),
                'secure' => $cookieSecure,
                'httponly' => $cookieHttpOnly,
                'samesite' => $cookieSameSite,
            ]);
        } else {
            $header = $cookieName . '=' . rawurlencode($cookieValue);
            $header .= '; Expires=' . gmdate('D, d M Y H:i:s', $expires) . ' GMT';
            $header .= '; Max-Age=' . (365 * 24 * 60 * 60);
            $header .= '; Path=' . $cookiePath;
            $header .= ($cookieDomain !== '' ? '; Domain=' . $cookieDomain : '');
            $header .= ($cookieSecure ? '; Secure' : '');
            $header .= ($cookieHttpOnly ? '; HttpOnly' : '');
            $header .= '; SameSite=' . $cookieSameSite;

            header('Set-Cookie: ' . $header, false);
            $ok = true;
        }

        $_COOKIE[$cookieName] = $cookieValue;

        $payload = [
            'success' => (bool)$ok,
            'cookie' => $cookieName,
        ];

        $this->output
            ->set_content_type('application/json')
            ->set_output(json_encode($payload));
    }
}
