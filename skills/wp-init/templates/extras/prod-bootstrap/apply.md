# prod-bootstrap overlay

Add a one-shot, token-gated, self-destructing PHP script for hosts with no
SSH access.

## Add files

- `scripts/prod-bootstrap.php` — the rendered token replaces
  `{{PROD_BOOTSTRAP_TOKEN}}`. Print the token in the post-setup card so the
  user can save it.

## Patch existing files

### `docs/deployment-ftp.md`

Append the following section (only if the FTP overlay was applied — if not,
abort with: `prod-bootstrap overlay: requires ftp-deploy overlay`):

```markdown

## Production bootstrap (FTP-only hosts)

For hosts with no SSH, use `scripts/prod-bootstrap.php`:

1. Upload `scripts/prod-bootstrap.php` to the WordPress root via FTP.
2. Visit `https://YOUR-DOMAIN/prod-bootstrap.php?token=YOUR_TOKEN_HERE`.
3. The script installs and activates configured plugins, sets pretty
   permalinks, then deletes itself.
4. If it fails mid-way, fix the underlying issue and re-run with the same
   token (idempotent).

Token was generated at scaffold time. If lost, edit the `$expected_token`
constant in `scripts/prod-bootstrap.php` to a new value before upload.
```

### `.gitignore`

Already includes `scripts/prod-bootstrap.php` (added by core/root/.gitignore.tmpl
when `PROD_BOOTSTRAP` is true). Verify the line is present; if missing, add:

```
scripts/prod-bootstrap.php
```
