# Elysian Trail — access code setup

The app can sit behind a shared code so that a public URL is not a public game.
This is optional. `index.html` works fine with no PHP at all.

## First-time setup

The live config is deliberately not in this repository. Create it from the example:

```bash
cp elysian-config.example.php elysian-config.php
```

Then edit **`elysian-config.php`** only:

```php
const ELYSIAN_ACCESS_CODE = 'CHANGE-ME';
```

Save, upload that one file, done. Players already inside stay inside until they
close the tab.

`elysian-config.php` is listed in `.gitignore`. Keep it that way — committing it
publishes your code and makes the gate decorative.

Two extra switches in the same file:

- `ELYSIAN_CASE_INSENSITIVE` — accept any capitalisation.
- `ELYSIAN_PASS_TTL` — seconds a pass lasts; `0` means until the tab closes.

## Files

| File | Role |
|---|---|
| `index.php` | Front door. Serves the app and stamps in a pass for anyone already cleared. |
| `auth.php` | Compares the typed code server-side with `hash_equals`. Answers only yes / no. |
| `elysian-config.example.php` | Template. Copy it to `elysian-config.php`. |
| `elysian-config.php` | Your code. Never sent to the browser, never committed. Blocked by `.htaccess` as a second layer. |
| `.htaccess` | Serves `index.php` first, denies the config file, disables directory listings. |
| `index.html` | The app plus cover page. Works on its own offline. |

## Deploying to a shared host

Upload the whole folder. Visit the directory URL — Apache serves `index.php`
because of the `DirectoryIndex` line. Nothing else to configure.

## When PHP is not running

If the file is opened straight off a hard drive, or hosted somewhere static,
`auth.php` cannot answer. The cover page then compares a SHA-256 digest of what
was typed against a stored digest. The plain code is still not in `index.html` —
only the one-way hash.

**The digests shipped in this repository match `CHANGE-ME`.** After you set your
own code, regenerate them and replace both values in `index.html`:

```bash
printf '%s' 'YourNewCode' | shasum -a 256
```

```js
var DIGEST_SHA256 = '<paste the hash here>';
var DIGEST_DJB2   = 3537040006;   // see below
```

`DIGEST_DJB2` is a fallback for browsers with no `crypto.subtle` — essentially
pre-2017 or a non-secure context. To regenerate it:

```bash
python3 -c "
code='YourNewCode'
h=5381
for c in code: h=((h*33)^ord(c)) & 0xFFFFFFFF
print(h)"
```

If you only ever host with PHP, you can leave both digests alone; the server
check takes precedence and the fallback never runs.

## What this does and does not protect

- The code is **not** discoverable in the page source. That was the goal.
- Server-side checking is authoritative and rate-limited to roughly one attempt
  per 0.7 seconds per visitor, with a delay on each failure.
- The app's own HTML and card images are still public files. Anyone who knows
  their URLs can fetch them directly. Gating the images too would mean serving
  each one through a PHP file that checks the session first.
