# SH Notices – WordPress Plugin

A lightweight plugin that adds a **Notice** custom post type and a flexible display component that integrates cleanly with **GeneratePress** (free and Premium).

---

## Features

| Feature | Details |
|---|---|
| Custom post type | `sh_notice` – full block-editor support, title, editor, excerpt, thumbnail, revisions, author, comments |
| Widget | Classic WP widget → drop into any sidebar, including GP's right sidebar |
| Shortcode | `[sh_notices]` – use anywhere shortcodes are allowed |
| GP hook injection | Select any GP action hook in Settings and notices are injected automatically |
| GP Elements | Named hook location `sh_notices_output` available as a GP Elements target |
| PHP function | `sh_notices_display()` / `sh_notices_render()` for child-theme use |

---

## Installation

1. Copy the `sh-notices` folder into `wp-content/plugins/`.
2. Activate via **Plugins → Installed Plugins**.
3. Go to **Notices → Settings** to configure defaults and GP integration.

---

## Usage

### Shortcode

```
[sh_notices]
[sh_notices count="3" show_date="0" title="Latest Notices"]
```

| Attribute | Default | Description |
|---|---|---|
| `count` | Setting value (5) | Number of notices |
| `show_excerpt` | Setting value (1) | Show excerpt (0/1) |
| `show_date` | Setting value (1) | Show date (0/1) |
| `show_thumbnail` | Setting value (1) | Show featured image (0/1) |
| `title` | _(empty)_ | Optional heading above the list |

---

### Widget

Go to **Appearance → Widgets** and add **SH Notices** to any registered sidebar. Each instance has its own title and display options.

For the **GeneratePress right sidebar**, add the widget to the `Right Sidebar` widget area.

---

### GeneratePress – automatic hook injection

1. Open **Notices → Settings**.
2. Under **GeneratePress Hook Integration**, select a hook (e.g. `generate_before_right_sidebar_content`).
3. Save. Notices appear on every page at that hook location.

Common hooks for the right sidebar:

| Hook | When it fires |
|---|---|
| `generate_before_right_sidebar_content` | Before widgets in the right sidebar |
| `generate_after_right_sidebar_content` | After widgets in the right sidebar |

---

### GeneratePress Premium – Elements

If you use GP Premium ≥ 2.0:

1. Go to **Appearance → Elements → Add New**.
2. Choose **Hook**.
3. Under **Hook Name** type (or select) `sh_notices_output`.
4. Add the shortcode `[sh_notices]` (or any content) to the block editor.
5. Use Display Rules to target specific pages/templates.

This lets you show notices only on certain pages without touching PHP.

---

### PHP / child-theme

```php
// Echo notices anywhere in a template
sh_notices_display();

// With options
sh_notices_display( [
    'count'          => 3,
    'show_excerpt'   => true,
    'show_date'      => false,
    'show_thumbnail' => true,
    'title'          => 'Notices',
] );

// Return HTML string
$html = sh_notices_render( [ 'count' => 5 ] );

// Inject into a specific GP hook from your child theme
add_action( 'generate_before_right_sidebar_content', 'sh_notices_in_right_sidebar', 5 );
// sh_notices_in_right_sidebar() is already defined by the plugin.

// Or use the named action hook directly
add_action( 'generate_after_header', function() {
    sh_notices_display( [ 'title' => 'Notices' ] );
}, 20 );
```

---

## CSS Customisation

The stylesheet uses CSS custom properties scoped to `.sh-notices-wrap`. Override in your child-theme:

```css
.sh-notices-wrap {
    --sh-notice-accent:     #e63946;   /* link / heading colour */
    --sh-notice-thumb-size: 64px;      /* thumbnail square size */
    --sh-notice-font-size:  0.875rem;
}
```

Dark-mode overrides are included via `@media (prefers-color-scheme: dark)`.

---

---

## Gutenberg block – Notices Grid

The block is available in the editor under **Widgets → Notices Grid** (megaphone icon).

### Block inspector options

| Option | Default | Description |
|---|---|---|
| Heading | _(empty)_ | Optional `<h2>` heading above the grid |
| Number of notices | 3 | 1–12 |
| Show excerpt | on | Toggle excerpt visibility |
| Show date | on | Toggle date visibility |
| Show featured image | on | Toggle image visibility |
| Mobile columns | 1 | 1 (stacked) or 2 columns on mobile |

Desktop **always** renders 3 columns. Use the block's built-in **Align** control (toolbar) to set `wide` or `full` alignment if your theme supports it.

The block also inherits Gutenberg's native colour, spacing, typography, and block-gap controls from the block supports API.

### Live preview in editor

The edit canvas shows a real `ServerSideRender` preview — what you see is exactly what is rendered on the front end.

### No build step required

The block ships as a pre-built single `index.js` file and plain CSS. No webpack/npm run required on the server. If you want to modify the JS, set up `@wordpress/scripts` in the `blocks/notices-grid/` directory:

```bash
cd blocks/notices-grid
npx @wordpress/scripts start
# or: npx @wordpress/scripts build
```

---

## File structure

```
sh-notices/
├── sh-notices.php              ← Plugin bootstrap
├── README.md
├── assets/
│   └── css/
│       └── sh-notices.css      ← Sidebar/widget/shortcode styles
├── blocks/
│   └── notices-grid/
│       ├── block.json          ← Block metadata (name, attributes, supports)
│       ├── index.js            ← Editor script (Edit component + registration)
│       ├── render.php          ← Server-side render (front end + SSR preview)
│       ├── style.css           ← Front-end + editor canvas styles
│       ├── editor.css          ← Editor-only style overrides
│       └── view.js             ← Front-end script (scroll reveal)
└── includes/
    ├── blocks.php              ← register_block_type() for all blocks
    ├── post-type.php           ← CPT registration + flush on activate/deactivate
    ├── renderer.php            ← sh_notices_render() / sh_notices_display()
    ├── widget.php              ← SH_Notices_Widget (classic widget API)
    ├── shortcode.php           ← [sh_notices] shortcode
    ├── generatepress.php       ← GP hook auto-inject + Elements location
    └── settings.php            ← Settings page under Notices → Settings
```
