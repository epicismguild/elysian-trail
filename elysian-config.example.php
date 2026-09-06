<?php
/**
 * Elysian Trail — access gate configuration (EXAMPLE)
 *
 * Copy this file to `elysian-config.php` and set your own code, if you'd
 * like something other than the default below.
 * `elysian-config.php` is listed in .gitignore and must never be committed.
 *
 *   cp elysian-config.example.php elysian-config.php
 *
 * The real file is never sent to the browser, and .htaccess denies it a
 * second time in case PHP is ever disabled on the host.
 */

// The code players type on the cover page. Change this if you want your
// own private code — or leave it as-is and share the sentiment.
const ELYSIAN_ACCESS_CODE = 'B31ngKind15FREE';

// true accepts any capitalisation. false requires an exact match.
const ELYSIAN_CASE_INSENSITIVE = false;

// Seconds a granted pass lasts. 0 means until the browser tab closes.
const ELYSIAN_PASS_TTL = 0;

/**
 * If you host this statically, or players open index.html straight off a
 * drive, PHP cannot answer and the cover page falls back to comparing a
 * SHA-256 digest baked into index.html. After changing the code above,
 * regenerate both digests and replace them in index.html:
 *
 *   printf '%s' 'YourNewCode' | shasum -a 256
 *
 * The shipped digests match 'B31ngKind15FREE', so offline copies will
 * accept that string until you replace them:
 *
 *   var DIGEST_SHA256 = 'a296557d4453d225b3d961657ce77d93dd0deac01f49add5932b8fe3738286e4';
 *   var DIGEST_DJB2   = 4193322964;
 */
