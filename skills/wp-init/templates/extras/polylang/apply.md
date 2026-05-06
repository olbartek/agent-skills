# polylang overlay

Add Polylang helpers + multi-language seed step.

## Add files

- `wp-content/themes/{{THEME_SLUG}}/includes/polylang-config.php`
- `wp-content/themes/{{THEME_SLUG}}/template-parts/language-switcher.php`

## Patch existing files

### `wp-content/themes/{{THEME_SLUG}}/functions.php`

Find the line `// WP_INIT:INCLUDES` and insert **before** it:

```php
require_once {{PREFIX_UPPER}}_THEME_DIR . '/includes/polylang-config.php';
```

### `scripts/seed-db.sh`

Find the line `# WP_INIT:PLUGINS` and insert **before** it:

```bash
PLUGINS+=("polylang")
```

Then find the line `# WP_INIT:CUSTOM_PLUGINS` and insert **before** it
(the `LOCALES_CSV` is already substituted into the rendered text):

```bash
# Configure Polylang languages.
$WP eval '
$langs = explode(",", "{{LOCALES_CSV}}");
$default = "{{DEFAULT_LOCALE}}";
foreach ($langs as $locale) {
    $slug = strtolower(substr($locale, 0, 2));
    if (function_exists("PLL")) {
        $existing = wp_list_pluck(PLL()->model->get_languages_list(), "slug");
        if (!in_array($slug, $existing, true)) {
            PLL()->model->add_language([
                "name" => $slug, "slug" => $slug, "locale" => $locale,
                "flag" => $slug, "rtl" => 0, "term_group" => 0,
            ]);
        }
    }
}
$opts = get_option("polylang", []);
$opts["default_lang"] = strtolower(substr("{{DEFAULT_LOCALE}}", 0, 2));
$opts["force_lang"]   = 1;
$opts["hide_default"] = 1;
update_option("polylang", $opts);
'
```

### `wp-content/themes/{{THEME_SLUG}}/header.php`

Insert the language switcher template part inside `.site-header__inner`,
immediately before the closing `</div>`. If the header was customised so the
closing `</div>` is no longer the last line of `.site-header__inner`, abort
with: `polylang overlay: header.php has been customised — manually add
get_template_part('template-parts/language-switcher') to it`.

If any sentinel is missing, abort with: `polylang overlay: <file> missing
sentinel <name> — re-render the corresponding core template`.
