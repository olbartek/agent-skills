# custom-plugin overlay

Scaffold a minimal custom plugin under `wp-content/plugins/{{PLUGIN_SLUG}}/`.

## Add files

- `wp-content/plugins/{{PLUGIN_SLUG}}/{{PLUGIN_SLUG}}.php`
- `wp-content/plugins/{{PLUGIN_SLUG}}/readme.txt`

## Patch existing files

### `.gitignore`

Find the line `# WP_INIT:CUSTOM_PLUGIN_WHITELIST` and insert **before** it:

```
!wp-content/plugins/{{PLUGIN_SLUG}}/
```

### `scripts/seed-db.sh`

Find the line `# WP_INIT:CUSTOM_PLUGINS` and insert **before** it:

```bash
$WP plugin activate {{PLUGIN_SLUG}} 2>/dev/null || true
```

### `.env.deploy.example` (only if FTP overlay applied)

Find the line `# WP_INIT:CUSTOM_PLUGINS` and replace the `FTP_CUSTOM_PLUGINS=`
line above it with:

```
FTP_CUSTOM_PLUGINS={{PLUGIN_SLUG}}
```
