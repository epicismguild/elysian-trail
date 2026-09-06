# Elysian Trail

A race of fate, fortune, and perfect rolls.

**Try it live:** https://elysiantrail.epicism.com — access code `B31ngKind15FREE`
(the shared public default; see "The access gate" before hosting your own game).

Elysian Trail is a free party race game played on a physical board with the players
themselves as the pieces. One person hosts from a screen: they draw the cards, roll
the dice, and read the results aloud. Everyone else walks the trail.

This repository holds the whole thing — the host's card table app, the printable card
set, the player guide, and the access gate.

Made by Epicism Guild for the enjoyment of all. Free to play, free to print, free to share.

---

## What is in here

| Path | What it is |
|---|---|
| `index.html` | The host app. Card table on top, physics dice tray below. Single file, no build step. |
| `elysian-trail-board-one-v1.pdf` | The game board. Reproducible in most physical or virtual spaces. An importable blueprint is also available for supported in-game housing/building systems — see "Hosting a game." |
| `how-to-play.html` | The player guide. Prints cleanly on one or two sheets. |
| `blessings/` `ordeals/` `dilemmas/` | 54 card faces plus one deck back per suit, 781 × 1101 PNG. |
| `print/` | Print-ready card sheets as a single PDF. |
| `assets/` | Guild crest and logo. Swap these for your own if you're forking this — see "Making it yours." |
| `index.php` `auth.php` `elysian-config.example.php` `.htaccess` | Host/player access gate — see "The access gate." |
| `README-access.md` | Full detail on how the gate works. |
| `LICENSE` `LICENSE-ART.md` | MIT for the code, CC BY-NC-SA 4.0 for the game. |

---

## Running it

**Locally.** Clone or download the repo. Rename `elysian-config.example.php` to
`elysian-config.php`. Open `index.html`, enter the access code, click **Proceed**.
No server, no install, no build — every path is relative, so the folder runs from
anywhere.

**Hosted.** Upload the folder to any web host. With PHP available, the access gate
runs server-side. Without it — most static hosts — `index.html` falls back to a
client-side digest check automatically.

**Internet access.** The card table works fully offline. The 3D dice tray loads
three.js and Rapier physics from a CDN, so dice need a connection on first load. If
that fails, the app says so and the card side keeps working — roll physical dice
instead.

**Browser.** Any current Chrome, Edge, Firefox, or Safari. The dice tray needs WebGL2
and WebAssembly (standard since 2017).

---

## Hosting a game

1. Set up the board. If your game supports importable blueprints, use ours directly.
   Otherwise, `elysian-trail-board-one-v1.pdf` allows you to reproduce the same trail,
   Golden Gates, and shortcuts anywhere.
3. Open `index.html` where everyone can see it, or keep it to yourself and read
   results aloud.
4. Send players `how-to-play.html`, or print it.
5. Players stand on their spaces. You handle every card and every roll.

### Host hotkeys

| Key | Action |
|---|---|
| `1` `2` `3` | Draw Blessing, Ordeal, Dilemma |
| `R` / `Space` | Roll the dice |
| `U` / `Ctrl`+`Z` | Undo |
| `S` | Shuffle all decks |
| `H` / `?` | Open How to Play |
| `Esc` | Close a card or the rules |

Drawing a card deals it to the discard pile and zooms it full-screen automatically.
Click anywhere or press `Esc` to send it back down.

### Dice tray

Pick a die, set a quantity, roll — up to six dice at once. The view is locked
top-down so nothing can be knocked askew mid-game; scroll to zoom toward the dice.
The inset panel on the left is a close-up camera tracking wherever the dice land.

---

## Printing the cards

Fastest route: **`print/elysian-trail-cards-v1.pdf`**, ready to send to a printer.

To compose your own sheets, individual faces are 781 × 1101 PNG (5:7 ratio — a
standard 2.5×3.5" card at ~300 DPI). Nine to a US Letter sheet, or send to any
print-on-demand service that accepts individual faces.

Each deck folder includes one unnumbered file (`blessing.png`, `ordeal.png`,
`dilemma.png`) — that deck's back.

Need higher-resolution masters for a large print run? Open an issue.

---

## Changing the cards

The app builds decks from filenames — no code edits beyond one number:

1. Drop your face into the right folder, following existing numbering
   (`blessings/blessing19.png`).
2. In `index.html`, find the `DECKS` array and raise that deck's card count.

Card names, subtitles, and effects live in the art, not the code. The host reads
what the card says, so rules changes are art changes.

---

## The access gate

The gate isn't about keeping the internet out — it's what lets one screen show
everything (full deck, dice controls) while keeping that hidden from players, so the
game keeps its surprises. Full detail in `README-access.md`. Short version: the code
is compared server-side and never sent to the browser. Without PHP, the cover page
falls back to a SHA-256 digest check instead, so the plain code still never appears
in the page source.

**Hosting your own public game? Change the default code first.** `B31ngKind15FREE`
ships identically in every copy of this repo. Fine for a single private table — but
in a public instance, anyone who knows the default can reach your host controls and
see everything you're about to reveal to your own players. Takes a minute to fix.

**If you can run PHP:**

```bash
cp elysian-config.example.php elysian-config.php
```

Set your own `ELYSIAN_ACCESS_CODE` in that file. It's already in `.gitignore` —
keep it that way; never commit your real code to a public fork.

**If you can't run PHP** (most static hosts — GitHub Pages, Netlify, Cloudflare
Pages), `index.html`'s client-side check is what actually gates access. Regenerate
both digests for your new code:

```bash
printf '%s' 'YourNewCode' | shasum -a 256
```

```python
code = 'YourNewCode'
h = 5381
for c in code:
    h = ((h * 33) ^ ord(c)) & 0xFFFFFFFF
print(h)
```

Then update both lines in `index.html`:

```js
var DIGEST_SHA256 = '<SHA-256 output>';
var DIGEST_DJB2   = <DJB2 number>;
```

These are one-way hashes — publishing them, even in a public repo, never reveals
the actual code.

The card images themselves are ordinary public files. The gate protects the game,
not the artwork.

---

## Making it yours

Built for our own table, meant to be re-homed:

- Swap the crest and logo in `assets/` — same filenames, no code changes needed.
- Set your own access code (see above), especially before hosting publicly.
- Remix the art, board, and rules freely under CC BY-NC-SA — reskin it, rename the
  decks, redraw the cards, whatever fits your table.

---

## Rules in brief

Start at the Verdant Threshold. Reach the Golden Gate of Elysium. First three to
arrive place first, second, third.

- Each turn, choose a D4, D6, or D8, roll it, walk that many spaces. First place may
  not choose the D8.
- Obey the space you land on. Colored spaces send you to a deck.
- Golden Paths are shortcuts. Golden Gates are tolls — no passing without rolling
  max on your chosen die.
- The D12, Favor of the Forgotten, belongs to last place alone. Roll a 1 and the
  fates take pity: move one space, then roll again.
- The D20, Appeal to the Fates, is once per game per player, and it can hurt.

Full rules in `how-to-play.html`.

---

## Game pacing

A run is roughly 65–90 spaces, or 25–30 turns per player. Each turn takes 30–45
seconds, so:

`minutes ≈ turns × rounds × 40 ÷ 60`

Two to five players running individually: 35–110 minutes. Past five, use **teams** —
shared position, shared die, voting on Dilemmas, one shared D20 — to hold any group
size to roughly 60–90 minutes.

**To go faster** (in order of effect): open Golden Paths from the start (−6 turns),
open the D8 to everyone (−6), let players win by reaching *or passing* the end
rather than landing exactly (−3). **To go longer:** D4/D6 only, play all three
placements. Out of time? Call a final round — furthest along wins.

Modeled from average die values, not many sessions. Trust your table over this math.

---

## Contributing

Card art, new decks, alternate rule sets, and board variants are welcome. Open an
issue first for anything large, so we can talk before you spend a weekend on it.

Please keep contributions publisher-neutral — see "A note on theme" below.

---

## License

- **Code** (`index.html`, `how-to-play.html`, the PHP gate) — [MIT](LICENSE).
- **Card art, board, print files, rules** — [CC BY-NC-SA 4.0](LICENSE-ART.md).

This keeps the software freely reusable while keeping the game itself out of anyone's
storefront, including ours. Play it, print it, host it, remix it. Do not sell it.

---

## A note on theme

Elysian Trail is an original game with a Greek-myth setting, built by a gaming guild
for its own table. It is not affiliated with, endorsed by, or sponsored by any game
publisher, and it is not for sale. Please keep it that way in anything you fork or
redistribute.

---

## On the use of AI

This project used AI assistance, and we'd rather say so plainly than have someone
discover it.

**What AI did.** Wrote and refactored most of the app's JavaScript, CSS, and PHP.
Debugged the physics dice tray. Verified rule structure. Estimated pacing.

**What it did not do.** The game is not generated. The cards, the trail, the rules,
the die-choice mechanic, and the balance came from real play sessions with real
feedback. Layout, typography, and every final composition decision were made by a
person.

**Where accountability sits.** With us. Everything here was reviewed, tested, and
run in front of a live table before it shipped.

**What we ask.** This is a free, non-commercial game made by a gaming guild for its
own table. Nothing here is for sale. If you have a concern about how AI was used,
open an issue and ask. If you're an artist who wants to replace a card with your own
work, we'd rather have yours — the art is CC BY-NC-SA, so send a pull request.

---

## Support

Elysian Trail is free and will stay free. If it earned a laugh at your table,
[support Epicism Guild on Ko-fi](https://ko-fi.com/epicismguild).

Everything else: <https://github.com/epicismguild/elysian-trail>
