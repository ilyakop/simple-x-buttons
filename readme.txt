=== Simple X Buttons – Twitter Share & Follow for WordPress ===
Contributors: topdevs
Tags: social, x, twitter, share, follow, buttons, performance, privacy
Requires at least: 5.8
Tested up to: 6.9
Requires PHP: 7.2
Stable tag: 2.0.0
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Add X (Twitter) follow and share buttons to your WordPress site. No API key, no external scripts, no rate limits. Pure Web Intents.

== Description ==

**Simple X Buttons** adds X (Twitter) follow and share buttons to your WordPress posts and pages — the right way.

= Why not use X's timeline or profile embeds? =

X's embed infrastructure has been unreliable since 2023. The `publish.twitter.com` oEmbed endpoint and the `platform.twitter.com/widgets.js` SDK both hit HTTP 429 rate limits at scale, causing embeds to fail silently or load slowly. X has not committed to fixing this.

This plugin takes a different approach entirely.

= What are X Web Intents? =

Web Intents are plain URLs provided by X for sharing, following, reposting, liking, and more. They open a lightweight native X popup — no JavaScript SDK, no third-party cookies, no external scripts loaded on page load. You click, a popup opens, done.

That means:

* **No API key required** — zero setup friction
* **No external scripts** — nothing loads until the user clicks
* **No rate limits** — static URLs, no API calls
* **GDPR-friendly** — no cookies or tracking on page load
* **Fast** — the plugin CSS is ~1 KB and loaded only when needed

= Features =

* **Auto-insert** buttons after (or before) any post type — configure once, done
* **Share button** — pre-fills tweet with post title, permalink, hashtags, and via handle
* **Follow button** — opens X's native follow dialog for your account
* **Widget** — drop a follow button into any sidebar
* **Shortcodes** — `[sxb-share]`, `[sxb-follow]`, `[sxb-repost url=""]`, `[sxb-like url=""]`, `[sxb-dm]`
* **Gutenberg block** — drag-and-drop X follow button block
* **Template tags** — `sxb_follow_button()`, `sxb_share_button()`, `sxb_button_bar()` for theme developers
* **Three button styles** — Dark, Outline, Ghost
* **Popup or new-tab** intent window

= Integration =

Four ways to add buttons to your site:

1. **Auto-insert** (recommended): Enable in Settings → Simple X Buttons. Buttons appear after every post automatically.
2. **Widget**: Add the "SXB – Follow Button" widget to any sidebar.
3. **Shortcode**: `[sxb-follow handle="yourhandle"]` or `[sxb-share]` anywhere in content.
4. **Block**: Search for "X Follow Button" in the Gutenberg block inserter.

= Compatibility =

* WordPress 5.8 or higher
* PHP 7.2 or higher
* No API key required
* No external JavaScript loaded on page load
* Works with any theme

== Installation ==

1. Upload the `simple-x-buttons` folder to `/wp-content/plugins/`
2. Activate the plugin in **Plugins → Installed Plugins**
3. Go to **Settings → Simple X Buttons**
4. Enter your X handle and enable "After post content"
5. That's it

== Frequently Asked Questions ==

= Does this load Twitter/X scripts on my pages? =

No. The plugin loads only its own 1 KB stylesheet, and only on pages where a button appears. No external scripts are loaded at any point unless a user clicks a button and opens the intent popup.

= Does this need an API key? =

No. X Web Intents are plain URLs with no authentication required.

= Why not embed a Twitter timeline? =

X's timeline embed system (`widgets.js`) has been intermittently rate-limited since 2023, causing embeds to fail to load or show HTTP 429 errors. X has not publicly committed to fixing this. Web Intents are unaffected because they are static URLs, not API calls.

= Is this GDPR-friendly? =

Yes. No external scripts, cookies, or tracking occur on page load. X's servers are only contacted when a user actively clicks a button and the intent popup opens — the same as clicking any external link.

= What is the difference between "Popup" and "New Tab" intent window? =

Popup mode opens a small 600×360 window centered on the screen — the standard X intent experience. New Tab simply opens the intent URL in a new browser tab. Both work without JavaScript (popup requires JS for the `window.open` call; if JS is disabled it falls back to a new tab).

= Can I add buttons to custom post types? =

Yes. In the Placements section of the settings page, you can enable auto-insertion for any public post type registered on your site.

= Does this work with page builders? =

The shortcodes work in any context that processes WordPress shortcodes. The Gutenberg block works in the block editor. For Elementor, Bricks, or other builders, use the shortcode widget.

== Screenshots ==

1. Settings page — clean section layout
2. Dark, Outline, and Ghost button styles on the frontend
3. Gutenberg block editor preview
4. Widget settings

== Changelog ==

= 2.0.0 =
* Complete rewrite — X Web Intents architecture, no API dependencies
* Three button styles: Dark, Outline, Ghost
* Auto-insert into any post type
* Gutenberg block, widget, shortcodes, template tags
* GDPR-friendly: zero cookies or external scripts on page load

== Upgrade Notice ==

= 2.0.0 =
Complete rewrite. The old Twitter Timeline widget is gone. Install fresh — no migration from v1.x settings.
