# agent-skills

Personal collection of Claude Code skills, packaged as a plugin so they can be installed and updated from any machine.

## Skills included

- **cloc** — count lines of code, language breakdown, version comparisons.
- **agent-setup** — set up a project for multi-agent compatibility (Claude, Gemini, Codex).
- **codebase-analysis** — generate quantitative reports on a codebase (LOC, dependencies, coupling, hotspots).

## Install

In Claude Code:

```
/plugin marketplace add olbartek/agent-skills
/plugin install agent-skills@agent-skills
```

## Update

```
/plugin update agent-skills@agent-skills
```

Or to refresh the marketplace catalog first (e.g. after adding a new skill):

```
/plugin marketplace update agent-skills
/plugin update agent-skills@agent-skills
```

## Adding a new skill

1. Drop a new folder under `skills/<skill-name>/` containing a `SKILL.md` with the standard frontmatter (`name`, `description`, etc.).
2. Bump `version` in `.claude-plugin/plugin.json`.
3. Commit and push.
4. On any machine, run the update commands above.
