---
name: wp-init
description: Scaffold a new WordPress project with Docker local dev, lightweight custom theme, FTP deploy, and multi-agent setup. Asks the user when to opt in to extras (multi-language via Polylang, DeepL pipeline, prod-bootstrap, custom plugin scaffold). Use when the user wants to start a new WordPress site or migrate one to this team's conventions.
---

# wp-init — WordPress project scaffolder

Scaffolds a new WordPress project matching the `wp-villawierchy` reference shape:
lightweight custom theme (Gulp + SCSS), Docker local stack, FTPS deploy,
multi-agent setup, with opt-in extras for multi-language / translation /
prod-bootstrap / custom plugins.

## When to use

The user wants to start a new WordPress project, or wants to set up an empty
directory with this team's conventions. Run from inside the target project
directory (which should be empty or contain only `design/`, `docs/`, `.git/`,
`README.md`).

## Workflow (follow exactly)

### Phase 1 — Pre-flight

1. Confirm you are in the intended target directory: `pwd` and ask user if unclear.
2. Refuse to proceed unless the directory is empty OR contains only a subset of
   `{design/, docs/, .git/, README.md}`. If anything else is present, list it
   and ask the user to confirm or move it before proceeding.
3. Check tools on PATH:
   - `docker` (required) — `command -v docker`
   - `node` and `npm` (required) — `command -v node && command -v npm`
   - `lftp` (warn-only; required later if FTP enabled) — `command -v lftp`
   - `php` (warn-only; nice-to-have for local linting) — `command -v php`
4. Read `lib/intake.md` for the full ordered question list.

### Phase 2 — Intake

Ask intake questions one at a time using AskUserQuestion. Use the branching
rules in `lib/intake.md` — irrelevant phases are skipped (e.g. DeepL is hidden
unless multi-language was chosen).

After intake, present a one-screen summary of the configuration and ask the
user to confirm before any files are written.

### Phase 3 — Resolve free ports

Follow `lib/port-detect.md` to pick free WP / phpMyAdmin / DB ports if the
user accepted the defaults.

### Phase 4 — Build substitution map

Compute the full variable map listed in the plan header (PROJECT_NAME, slugs,
ports, locales, today's ISO date, AUTHOR from `git config user.name`,
COMPOSE_PROJECT_NAME, conditional flags). For prod-bootstrap, generate a
32-char hex token via `openssl rand -hex 16`.

### Phase 5 — Apply `core/` templates

For each file under `templates/core/`:

1. **Plain copy** if the path does not end in `.tmpl` and contains no
   `{{VAR}}` markers — use `cp` or `Read` + `Write`.
2. **Render** if the path ends in `.tmpl` OR contains `{{VAR}}` / `{{#if}}`
   markers:
   - Read the template.
   - Substitute every `{{VAR}}` with its value.
   - For each `{{#if FLAG}} … {{/if}}` block, KEEP the inner content if
     `FLAG` is true and the user opted in, REMOVE the block (including the
     `{{#if}}` and `{{/if}}` lines and everything between) if false.
   - Write the rendered file at the corresponding project path,
     stripping the `.tmpl` extension.

Files in `templates/core/root/` go to the project root. Files in
`templates/core/agent/` go to `.agent/`. Files in `templates/core/theme/` go
to `wp-content/themes/{{THEME_SLUG}}/`. Files in `templates/core/scripts/`
go to `scripts/`. Files in `templates/core/docs/` go to `docs/`.

### Phase 6 — Apply opted-in `extras/` overlays

For each overlay the user opted into, in order — `ftp-deploy`, `polylang`,
`deepl`, `prod-bootstrap`, `custom-plugin` — run the overlay's `apply.md`
which lists files to add (same render rules as Phase 5) and patch instructions
to apply at named sentinel comments.

If a sentinel is missing in the file being patched, ABORT with a clear error
naming the file and the sentinel — do not silently continue.

### Phase 7 — Agent layer

1. Create `.agent/{commands,rules,skills}/.gitkeep`.
2. The rendered `templates/core/agent/AGENTS.md.tmpl` is already at
   `.agent/AGENTS.md` from Phase 5.
3. Create symlinks (relative):

```bash
ln -s .agent/AGENTS.md CLAUDE.md
ln -s .agent/AGENTS.md AGENTS.md
ln -s .agent/AGENTS.md GEMINI.md
mkdir -p .claude .codex .gemini
ln -s ../.agent/commands .claude/commands
ln -s ../.agent/rules    .claude/rules
ln -s ../.agent/skills   .claude/skills
ln -s ../.agent/commands .codex/commands
ln -s ../.agent/rules    .codex/rules
ln -s ../.agent/skills   .codex/skills
ln -s ../.agent/commands .gemini/commands
ln -s ../.agent/rules    .gemini/rules
ln -s ../.agent/skills   .gemini/skills
```

`.claude/settings.json` is a real file written from
`templates/core/root/.claude/settings.json.tmpl` in Phase 5.

### Phase 8 — Make scripts executable

```bash
chmod +x scripts/*.sh
```

### Phase 9 — Optional `git init`

If the user opted in (Phase G #21):

```bash
git init -q
git add .
git commit -q -m "chore: initial scaffolding via wp-init"
```

If the directory was already a git repo (e.g. `.git/` was tolerated by
pre-flight), skip `git init` and just `git add . && git commit`.

### Phase 10 — Post-setup card

Render the text in `lib/post-setup.md` with the substitution map and print it
to chat. Include the `prod-bootstrap` token block only if that overlay was
applied.

## Important rules

- Do NOT mention specific brands, domains, or content from the reference
  project (`villawierchy`, `kardiologia`, `hotres`, `cyberfolks`) in any
  rendered output. Use only the user's chosen names and the generic templates.
- If the user halts midway, do not retry destructive cleanup unless they
  explicitly ask. Just report what was done.
- The skill produces the same `.agent/` shape as `agent-setup`. Do not invoke
  `agent-setup` from this skill — the AGENTS.md here is WordPress-tailored at
  write time.

## Files in this skill

- `SKILL.md` (this file) — workflow.
- `lib/intake.md` — full intake question list with branching.
- `lib/port-detect.md` — port collision detection recipe.
- `lib/post-setup.md` — post-setup card text.
- `templates/core/` — always-applied templates.
- `templates/extras/{ftp-deploy,polylang,deepl,prod-bootstrap,custom-plugin}/` — opt-in overlays. Each contains its own `apply.md`.
