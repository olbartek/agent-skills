# Post-setup card

Render this with the substitution map and print to chat. Include conditional
sections only when their flag is true.

```
✓ WordPress project scaffolded at: {{CWD}}

Next steps:
  1. Add to /etc/hosts:
       127.0.0.1  {{HOSTNAME}}

  2. Start the local stack:
       cp .env.example .env
       docker compose up -d
       ./scripts/seed-db.sh
       open http://{{HOSTNAME}}:{{WP_PORT}}

  3. Theme dev loop:
       cd wp-content/themes/{{THEME_SLUG}}
       npm install
{{#if SCSS_ENABLED}}       npm run watch{{/if}}

  4. Local URLs:
       Site:        http://{{HOSTNAME}}:{{WP_PORT}}
       wp-admin:    http://{{HOSTNAME}}:{{WP_PORT}}/wp-admin   (admin / admin)
       phpMyAdmin:  http://localhost:{{PHPMYADMIN_PORT}}
       MariaDB:     localhost:{{DB_PORT}}

{{#if FTP_ENABLED}}  5. When ready to deploy:
       cp .env.deploy.example .env.deploy   # fill in FTP creds
       ./scripts/test-ftp.sh                # verify connection
       ./scripts/deploy-ftp.sh              # ship the theme
{{/if}}{{#if MULTI_LANG}}  6. After first seed, configure Polylang languages in /wp-admin/ → Languages.
     See docs/local-development.md → "First-time Polylang setup".
{{/if}}{{#if DEEPL_ENABLED}}  7. Translations: see docs/translations.md.
{{/if}}{{#if PROD_BOOTSTRAP}}  8. Production bootstrap token (single use, save now):
       {{PROD_BOOTSTRAP_TOKEN}}
     See docs/deployment-ftp.md → "Production bootstrap".
{{/if}}

Project docs:
  - docs/local-development.md   ← start here
  - docs/plugins.md
{{#if FTP_ENABLED}}  - docs/deployment-ftp.md
{{/if}}{{#if DEEPL_ENABLED}}  - docs/translations.md
{{/if}}{{#if HTML_MOCKUP}}  - docs/import-html.md
{{/if}}

Agent setup ready: CLAUDE.md / AGENTS.md / GEMINI.md → .agent/AGENTS.md

If port {{WP_PORT}} is already in use, edit .env (WP_PORT=...) and
`docker compose up -d` again.
```
