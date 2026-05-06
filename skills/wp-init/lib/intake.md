# Intake questions

Ask **one question at a time** using `AskUserQuestion`. Defaults shown in
`[brackets]`. Yes/No defaults to **No** unless noted. Branching rules are
listed under each question — skip irrelevant phases entirely.

## Phase A — Identity (required, no skip)

1. **Project display name?**
   *Free text. Example: "Kardiologia Opole".*

2. **Project slug?** (kebab-case)
   *Default = kebab(display name). Example: `kardiologia-opole`.*

3. **Theme slug?**
   *Default = `{project-slug}-theme`. Example: `kardiologia-opole-theme`.*

4. **PHP/CSS prefix?** (3–5 lowercase letters)
   *Default = first letters of project-slug words concatenated. Used for
   PHP constants like `KARD_THEME_VERSION` and CSS custom-property prefix.*

5. **One-line project description?**
   *Used in README, AGENTS.md, theme `style.css` header.*

## Phase B — Local environment

6. **Local hostname?**
   *Default = `{project-slug}.local`.*

7. **WP host port?**
   *Skill scans free ports per `lib/port-detect.md`. Suggest first free in
   `[8090, 8092, 8094, 8096]`.*

8. **phpMyAdmin port?**
   *Default = `{wp_port} + 1`.*

9. **Database port?**
   *First free in `[3307, 3308, 3309]`.*

## Phase C — Languages

10. **Languages?**
    - **(a) Single (English, en_US)** *[default]*
    - **(b) Single non-English** — ask for locale, e.g. `pl_PL`.
    - **(c) Multi-language with Polylang** — sets `MULTI_LANG=true`.

11. *(only if c)* **Locales?** Comma-separated, e.g. `pl_PL,en_US,fr_FR,de_DE`.

12. *(only if c)* **Default locale?** Default = first locale from the list.

## Phase D — Optional extras

13. **FTP deploy scaffolding?** *[Yes]* — sets `FTP_ENABLED`.

14. *(only if FTP yes)* **FTP remote path strategy?**
    - **(a) Account scoped to project subdomain** *[default]* — paths like
      `wp-content/themes/{theme-slug}` (no project-slug prefix).
    - **(b) Account scoped higher** — paths get `{project-slug}/` prefix.

15. **Generate `prod-bootstrap.php`?** — sets `PROD_BOOTSTRAP`.

16. *(only if MULTI_LANG)* **DeepL `.po` translation pipeline?** — sets
    `DEEPL_ENABLED`.

17. **Scaffold a custom plugin?** — sets `CUSTOM_PLUGIN`. If yes, ask:
    - **17a.** Plugin slug? *Default = `{prefix}-custom`.*
    - **17b.** Plugin one-line description?

18. **SCSS / Gulp pipeline?** *[Yes]* — sets `SCSS_ENABLED`. If no, skip
    `package.json`, `gulpfile.js`, `sass/`, `dist/`; produce a single plain
    `style.css` and enqueue it directly.

## Phase E — Design import

19. **HTML mockup or design assets?**
    - **(a) HTML mockup directory** — ask for path. Skill copies to
      `design/source/` and writes `docs/import-html.md`. Sets `HTML_MOCKUP`.
    - **(b) Screenshots / PDFs only** — ask for path. Copies to `design/`.
      Sets `HTML_MOCKUP`.
    - **(c) None / sketch only** *[default]* — no `design/` folder.

## Phase F — Plugins to seed

20. **Seeded plugins?** Multi-select. Defaults:
    - `[x]` Contact Form 7 → `PLUGIN_CF7`
    - `[x]` WP Super Cache → `PLUGIN_SUPER_CACHE`
    - `[ ]` Rank Math SEO → `PLUGIN_RANK_MATH`
    - `[ ]` UpdraftPlus → `PLUGIN_UPDRAFT`
    - `[ ]` Wordfence (auto-deactivated locally) → `PLUGIN_WORDFENCE`
    - `[ ]` Better Search Replace → `PLUGIN_BSR`
    - Polylang → auto-on if `MULTI_LANG`, otherwise hidden.

## Phase G — Wrap

21. **Initialize git repo and make initial commit?** *[Yes]*

22. **Print quick-start card?** *[Yes]*

## After all questions

Present a confirmation summary. Example:

```
Configuration:
  Project:      Kardiologia Opole (kardiologia)
  Theme:        kardiologia-theme (prefix: kard)
  Description:  Custom WordPress site for Kardiologia Opole.
  Hostname:     kardiologia.local
  Ports:        WP=8092  phpMyAdmin=8093  DB=3308
  Languages:    Single (en_US)
  Extras:       FTP=yes  prod-bootstrap=no  custom-plugin=no  SCSS=yes
  Design:       none
  Plugins:      Contact Form 7, WP Super Cache
  Wrap:         git init=yes  print card=yes

Proceed? (yes/no)
```

Only proceed once the user confirms.
