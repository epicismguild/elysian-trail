<?php
/* ============================================================
   ELYSIAN TRAIL — front door
   ------------------------------------------------------------
   Upload the whole folder to Dreamhost and this file becomes the
   entry point. It serves the same index.html, but stamps in a
   server-signed pass when the visitor has already cleared the
   cover page, so they are not asked twice in one session.

   The access code lives ONLY in elysian-config.php and is never
   written into the page.
   ============================================================ */

declare(strict_types=1);

/* Fall back to the example so a fresh clone does not fatal-error.
   Copy elysian-config.example.php to elysian-config.php and set your code. */
$cfg = __DIR__ . '/elysian-config.php';
require file_exists($cfg) ? $cfg : __DIR__ . '/elysian-config.example.php';

session_start();

$granted = $_SESSION['elysian_pass'] ?? 0;
$authed  = $granted && (ELYSIAN_PASS_TTL === 0 || (time() - $granted) < ELYSIAN_PASS_TTL);

$html = file_get_contents(__DIR__ . '/index.html');
if ($html === false) {
    http_response_code(500);
    exit('Elysian Trail could not be loaded.');
}

// The marker sits in <head>. Replacing it tells the page a pass is held.
$flag = $authed
    ? '<script>window.__ELYSIAN_PASS = true;</script>'
    : '<script>window.__ELYSIAN_SERVER = true;</script>';

$html = str_replace('<!--ELYSIAN-PASS-->', $flag, $html);

header('Content-Type: text/html; charset=utf-8');
header('Cache-Control: no-store');
echo $html;
