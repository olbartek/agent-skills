---
name: agent-setup
description: Set up a project for multi-agent compatibility (Claude, Gemini, OpenAI Codex). Creates shared .agent directory with commands, rules, skills, and agent-specific directories (.claude, .codex, .gemini) with symlinks. Handles migration of existing agent configs.
---

# Agent Setup

Set up a project so it is compatible with the 3 most common AI coding agents: Claude Code, Gemini, and OpenAI Codex.

## Workflow

### Phase 1 — Detect Existing Setup

Before creating anything, scan the project root for existing agent configuration from **all three agents**:

1. Check for existing files/directories:

   **Claude Code:**
   - `.claude/` directory (settings.json, settings.local.json, commands/, rules/, skills/, hooks/)
   - `CLAUDE.md` (root-level, may be a real file or symlink)

   **Gemini:**
   - `.gemini/` directory (commands/, rules/, skills/, any config files)
   - `GEMINI.md` (root-level, may be a real file or symlink)

   **OpenAI Codex:**
   - `.codex/` directory (commands/, prompts/, rules/, skills/, any config files)
   - `.codexrc` (root-level config)
   - `AGENTS.md` (root-level, used by Codex/generic agents)

   **Shared:**
   - `.agent/` directory and its contents (already unified setup)

2. If any existing configuration is found, present a summary to the user organized by source agent, and ask:
   - **Overwrite**: replace everything with a fresh setup
   - **Merge**: incorporate existing content into the new unified structure

3. If merging, collect content from **all detected agents** into `.agent/`:

   **Agent instruction files → `.agent/AGENTS.md`:**
   - If multiple instruction files exist (`CLAUDE.md`, `GEMINI.md`, `AGENTS.md`, or instruction files inside `.gemini/`, `.codex/`), read all of them.
   - If they contain identical or near-identical content, use one as the source.
   - If they differ, present the differences to the user and ask how to reconcile (pick one, merge manually, or let the agent merge the non-overlapping sections).

   **Rules → `.agent/rules/`:**
   - Collect from `.claude/rules/`, `.gemini/rules/`, `.codex/rules/` (real files only, skip existing symlinks pointing to `.agent/`)
   - If files with the same name exist across agents, present conflicts to the user.

   **Commands → `.agent/commands/`:**
   - Collect from `.claude/commands/`, `.gemini/commands/`, `.codex/commands/`, `.codex/prompts/` (real files only, skip existing symlinks)
   - If files with the same name exist across agents, present conflicts to the user.

   **Skills → `.agent/skills/`:**
   - Collect from `.claude/skills/`, `.gemini/skills/`, `.codex/skills/` (real files only, skip existing symlinks)

   **Claude-specific (preserve in `.claude/`, do NOT move to `.agent/`):**
   - `.claude/settings.json` and `.claude/settings.local.json`
   - `.claude/hooks/` directory

### Phase 2 — Ask About Permissions

Ask the user which permissions level they want for `.claude/settings.json`:

**Extended permissions** (wide access for autonomous workflows):
```json
{
  "permissions": {
    "allow": [
      "Read",
      "Edit",
      "Write",
      "Glob",
      "Grep",
      "Bash(git status*)",
      "Bash(git diff*)",
      "Bash(git log*)",
      "Bash(git show*)",
      "Bash(git branch*)",
      "Bash(git checkout*)",
      "Bash(git add*)",
      "Bash(git commit*)",
      "Bash(git stash*)",
      "Bash(git push*)",
      "Bash(git pull*)",
      "Bash(git fetch*)",
      "Bash(git merge*)",
      "Bash(git rebase*)",
      "Bash(git remote*)",
      "Bash(git tag*)",
      "Bash(git worktree*)",
      "Bash(ln*)",
      "Bash(mkdir*)",
      "Bash(ls*)",
      "Bash(cat*)",
      "Bash(find*)",
      "Bash(grep*)",
      "Bash(wc*)",
      "Bash(head*)",
      "Bash(tail*)",
      "Bash(sort*)",
      "Bash(cloc*)"
    ]
  }
}
```

**Standard permissions** (minimal, ask-based):
```json
{
  "permissions": {
    "allow": [
      "Read",
      "Edit",
      "Write",
      "Glob",
      "Grep",
      "Bash(git status*)",
      "Bash(git diff*)",
      "Bash(git log*)",
      "Bash(git show*)",
      "Bash(git branch*)"
    ]
  }
}
```

If the user already has a `settings.json` or `settings.local.json`, ask whether to keep it, replace it, or merge the allow-lists.

### Phase 3 — Create Directory Structure

Create the following structure:

```
project-root/
├── .agent/
│   ├── AGENTS.md          # Primary agent instructions (source of truth)
│   ├── README.md           # Explains the .agent/ folder purpose
│   ├── commands/
│   │   └── .gitkeep
│   ├── rules/
│   │   └── .gitkeep
│   └── skills/
│       └── .gitkeep
├── .claude/
│   ├── settings.json       # Claude-specific (real file, NOT symlinked)
│   ├── commands → ../.agent/commands
│   ├── rules → ../.agent/rules
│   └── skills → ../.agent/skills
├── .codex/
│   ├── commands → ../.agent/commands
│   ├── rules → ../.agent/rules
│   └── skills → ../.agent/skills
├── .gemini/
│   ├── commands → ../.agent/commands
│   ├── rules → ../.agent/rules
│   └── skills → ../.agent/skills
├── CLAUDE.md → .agent/AGENTS.md
├── AGENTS.md → .agent/AGENTS.md
└── GEMINI.md → .agent/AGENTS.md
```

### Phase 4 — Create Files

#### .agent/AGENTS.md

If migrating from existing agent instruction files (`CLAUDE.md`, `GEMINI.md`, `AGENTS.md`, or any instruction file found inside `.gemini/`, `.codex/`), use their content (merged if needed per Phase 1). Otherwise create a minimal template:

```markdown
# <Project Name>

<Brief project description — infer from README.md, package.json, Cargo.toml, etc.>

## Quick Reference

- **Language/Framework:** <detected>
- **Package manager:** <detected>

## Build & Run

<Infer from project files or leave as TODO>

## Key Commands

<Infer from project files or leave as TODO>

## Architecture

<Leave as TODO for the user to fill in>

## Workflow

- Always use dedicated branches for changes
- Never work directly on main
```

#### .agent/README.md

```markdown
# Agent Instructions

This folder centralizes guidance for LLM coding agents used on this repo.

- `AGENTS.md`: primary agent instructions referenced by CLAUDE.md, AGENTS.md, GEMINI.md
- `commands/`: custom agent commands
- `rules/`: task-scoped rule files
- `skills/`: optional agent skills with task-specific workflows
```

#### .claude/settings.json

Use the permissions level chosen by the user in Phase 2. This file is Claude-specific and is NOT symlinked from `.agent/`.

If the user already has a `settings.local.json`, leave it untouched.

### Phase 5 — Create Symlinks

Use relative paths for all symlinks so they remain portable:

```bash
# Root-level agent file symlinks
ln -sf .agent/AGENTS.md CLAUDE.md
ln -sf .agent/AGENTS.md AGENTS.md
ln -sf .agent/AGENTS.md GEMINI.md

# .claude/ symlinks (settings.json stays as real file)
ln -sf ../.agent/commands .claude/commands
ln -sf ../.agent/rules .claude/rules
ln -sf ../.agent/skills .claude/skills

# .codex/ symlinks
ln -sf ../.agent/commands .codex/commands
ln -sf ../.agent/rules .codex/rules
ln -sf ../.agent/skills .codex/skills

# .gemini/ symlinks
ln -sf ../.agent/commands .gemini/commands
ln -sf ../.agent/rules .gemini/rules
ln -sf ../.agent/skills .gemini/skills
```

### Phase 6 — Update .gitignore

Add the following entries to `.gitignore` if not already present:

```
# Agent local settings (not shared)
.claude/settings.local.json
```

Do NOT gitignore `.agent/`, `.claude/`, `.codex/`, `.gemini/`, or the root-level symlinks — these should be committed.

### Phase 7 — Summary

Print a summary of what was created:

```
Agent setup complete!

Created:
  .agent/           — shared agent config (source of truth)
  .claude/          — Claude Code config (settings.json + symlinks)
  .codex/           — OpenAI Codex config (symlinks)
  .gemini/          — Gemini config (symlinks)
  CLAUDE.md         — symlink → .agent/AGENTS.md
  AGENTS.md         — symlink → .agent/AGENTS.md
  GEMINI.md         — symlink → .agent/AGENTS.md

Edit .agent/AGENTS.md to customize agent instructions.
Add rules to .agent/rules/, commands to .agent/commands/.
```

## Important Notes

- **Do NOT generate language/technology-specific rules, commands, or skills.** The setup must be generic and tech-agnostic. The user will add project-specific content later.
- **`.claude/settings.json` is Claude-specific** — it does NOT belong in `.agent/` and is NOT symlinked.
- **`.claude/hooks/` is Claude-specific** — if it exists, preserve it as-is. Do not move or symlink it.
- **`.claude/settings.local.json` is user-local** — if it exists, preserve it. It should be gitignored.
- All symlinks must use **relative paths** for portability.
- Prefer `ln -sf` to overwrite stale symlinks safely.
- When detecting the project name and description, read `README.md`, `package.json`, `Cargo.toml`, `Package.swift`, `pyproject.toml`, `build.gradle`, or similar project manifest files.

## If Blocked

- If unable to determine the project name or description, ask the user.
- If existing configuration is ambiguous (e.g., conflicting rules in `.claude/rules/` and a standalone `CLAUDE.md`), present both and ask the user how to reconcile.
