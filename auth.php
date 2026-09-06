<?php
/* ============================================================
   ELYSIAN TRAIL — access check
   ------------------------------------------------------------
   Receives a POSTed code, compares it server-side, and hands back
   only "yes" or "no". The code itself never leaves this server.
   Edit elysian-config.php to change the code.
   ============================================================ */

declare(strict_types=1);

/* Fall back to the example so a fresh clone does not fatal-error.
   Copy elysian-config.example.php to elysian-config.php and set your code. */
$cfg = __DIR__ . '/elysian-config.php';
require file_exists($cfg) ? $cfg : __DIR__ . '/elysian-config.example.php';

session_start();

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('X-Content-Type-Options: nosniff');

function reply(bool $ok, string $msg = ''): void {
    echo json_encode(['ok' => $ok, 'message' => $msg]);
    exit;
}

/* ---- already holding a valid pass? ---- */
$granted = $_SESSION['elysian_pass'] ?? 0;
if ($granted && (ELYSIAN_PASS_TTL === 0 || (time() - $granted) < ELYSIAN_PASS_TTL)) {
    if (($_GET['check'] ?? '') === '1') reply(true, 'pass');
}

if (($_GET['check'] ?? '') === '1') reply(false, 'no pass');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    reply(false, 'Use POST.');
}

/* ---- crude flood brake: one attempt per 700ms per session ---- */
$now  = microtime(true);
$last = $_SESSION['elysian_last_try'] ?? 0.0;
if (($now - $last) < 0.7) {
    http_response_code(429);
    reply(false, 'Slow down.');
}
$_SESSION['elysian_last_try'] = $now;

/* ---- read the submitted code (JSON body or plain form post) ---- */
$given = '';
$raw   = file_get_contents('php://input');
if ($raw !== '' && $raw !== false) {
    $json = json_decode($raw, true);
    if (is_array($json) && isset($json['code'])) $given = (string) $json['code'];
}
if ($given === '' && isset($_POST['code'])) $given = (string) $_POST['code'];

$given    = trim($given);
$expected = ELYSIAN_ACCESS_CODE;

if (ELYSIAN_CASE_INSENSITIVE) {
    $given    = strtolower($given);
    $expected = strtolower($expected);
}

/* hash_equals compares in constant time, so response timing leaks nothing */
if ($given !== '' && hash_equals($expected, $given)) {
    $_SESSION['elysian_pass'] = time();
    $_SESSION['elysian_tries'] = 0;
    reply(true, 'The gate opens.');
}

$_SESSION['elysian_tries'] = ($_SESSION['elysian_tries'] ?? 0) + 1;
usleep(250000);   // small, deliberate delay on failure
reply(false, 'The gate does not stir.');
