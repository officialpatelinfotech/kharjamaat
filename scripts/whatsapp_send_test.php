<?php
// Standalone test for ExprezBot WhatsApp template send (bypasses CodeIgniter bootstrap).
require __DIR__ . '/../vendor/autoload.php';

// Load .env if present
if (class_exists('Dotenv\Dotenv')) {
    try {
        $dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
        $dotenv->safeLoad();
    } catch (Throwable $e) {
        // ignore
    }
}

$token = getenv('EXPREZBOT_WHATSAPP_TOKEN') ?: ($_ENV['EXPREZBOT_WHATSAPP_TOKEN'] ?? '');
$url = getenv('EXPREZBOT_WHATSAPP_API_URL') ?: ($_ENV['EXPREZBOT_WHATSAPP_API_URL'] ?? 'https://bot.exprezbot.com/api/send-notification');

if (empty($token)) {
    echo "EXPREZBOT_WHATSAPP_TOKEN not set in environment or .env\n";
    exit(2);
}

$phone = $argv[1] ?? '';
$template = $argv[2] ?? 'rsvp_new_khar';
$lang = $argv[3] ?? 'en';
$bodyVarsJson = $argv[4] ?? '[]';
$bodyVars = json_decode($bodyVarsJson, true);
if (json_last_error() !== JSON_ERROR_NONE) $bodyVars = [];

$payload = [
    'token' => $token,
    'template_name' => $template,
    'template_language' => $lang,
    'phone' => preg_replace('/[^0-9]/','', $phone),
    'body' => array_map(function ($v) {
        if (is_array($v) && array_key_exists('text', $v)) {
            return ['text' => (string)$v['text']];
        }
        return ['text' => (string)$v];
    }, array_values($bodyVars)),
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json','Accept: application/json']);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

$raw = curl_exec($ch);
$errno = curl_errno($ch);
$err = $errno ? curl_error($ch) : '';
$http = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP: {$http}\n";
if ($errno) echo "cURL error: {$err}\n";
if ($raw) {
    echo "Response:\n" . $raw . "\n";
}

return ($errno || $http < 200 || $http >= 300) ? 1 : 0;
