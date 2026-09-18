---
name: save-to-brain
description: Use when the user wants to save, write up, file or remember something in their markdown knowledge base ("the brain", a work vault, an Obsidian vault) — a guide or runbook from the current session, research findings, a project idea, or a source to ingest — especially from a repo other than the vault itself.
argument-hint: "[guide|research|idea|ingest] [topic, source, or nothing to use this session]"
---

# Save to brain

## Overview

Files knowledge into the user's markdown vault(s). **Each vault's `AGENTS.md` is the single source of truth** for layout, frontmatter, workflows, commits and what may be stored. This skill only finds the right vault and hands off to it. Don't carry conventions over from memory or from another vault.

## Steps

1. **Find the vaults.** Look for vault paths and routing rules in the global `CLAUDE.md` already in context (a "brain" pointer or a "Knowledge bases" table). If none is there, ask the user for the vault path. Don't guess.
2. **Read the target vault's `AGENTS.md` and `index.md`** before writing. If a page already covers the topic, extend it.
3. **Pick the content.**
   - If the arguments name a topic or source, run that vault workflow (guide, research, idea or ingest) on it.
   - Otherwise, take what's worth keeping from this session: setups that worked, gotchas, findings, ideas. Write one page per distinct topic.
4. **Route each page** using the `CLAUDE.md` rules. If one topic mixes general and client-specific knowledge, split it: the general part goes to the personal vault, and the specifics go to the client vault.
5. **Finish each page** with that vault's "Every change" steps, and run git with `git -C <vault>`.
6. **Report:**
   - the pages created or updated in each vault, with their paths
   - the commits, and whether they were pushed
   - anything deliberately left out, and why

## Quick reference

| User says | Do |
|---|---|
| "save this to the brain" | Session content, routed per `CLAUDE.md` |
| `/save-to-brain guide` | Guide from this session's work |
| `/save-to-brain research <topic>` | Run the vault's Research workflow on the topic |
| `/save-to-brain idea <pitch>` | Idea page in the personal vault |
| `/save-to-brain ingest <url>` | The vault's Ingest workflow |
| Several things at once ("guide for X, plus that idea") | One page and one commit per item, each routed separately |
