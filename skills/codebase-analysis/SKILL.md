---
name: codebase-analysis
description: Analyze a codebase and generate quantitative reports (LOC stats, dependency graph, module coupling, largest files, service/interface inventory, third-party dependencies). Outputs markdown reports to docs/codebase-analytics/. Use when the user asks to analyze, profile, or get an overview of a codebase.
argument-hint: "[focus area or 'all']"
---

# Codebase Analysis

Generate quantitative, language-agnostic analysis reports for any codebase. Reports are point-in-time snapshots saved as markdown in `docs/codebase-analytics/`.

## When to Activate

- User asks to "analyze the codebase", "generate codebase report", or "profile the project"
- User wants an overview of project size, structure, dependencies, or complexity
- User asks about module coupling, dependency graphs, or largest files
- User says "codebase analytics" or "codebase analysis"

## Prerequisites

### cloc (required for stats report)

Check if installed:
```
which cloc
```

If not found, install it:
- macOS: `brew install cloc`
- npm: `npm install -g cloc`
- Debian/Ubuntu: `sudo apt install cloc`
- Red Hat/Fedora: `sudo yum install cloc`

### git (required for all reports)

The project must be a git repository. All file discovery uses `git ls-files` or `cloc --vcs=git` to respect `.gitignore` and exclude generated/vendored files.

## Reports

The skill produces up to 6 reports. The user can request all reports or specific ones.

| Report | File | What It Answers |
|--------|------|-----------------|
| **Stats** | `stats.md` | How big is the codebase? What languages? How much test code? |
| **Dependency Graph** | `dependency-graph.md` | How do internal modules depend on each other? |
| **Third-Party Dependencies** | `third-party-dependencies.md` | What external packages are used and why? |
| **Module Coupling** | `module-coupling.md` | How tightly coupled are modules? (import frequency) |
| **Largest Files** | `largest-files.md` | Where are the complexity hotspots? |
| **Service Inventory** | `service-inventory.md` | What interfaces exist and what implements them? |

## Workflow

### 1. Initialize

If `docs/codebase-analytics/` does not exist, create it with a `README.md` index.

### 2. Detect Project Structure

Before generating reports, understand the project layout:

1. **Identify the primary language(s)** — run `cloc --vcs=git --quiet` to see the language breakdown
2. **Identify module boundaries** — look for:
   - Package manifests: `Package.swift`, `package.json`, `Cargo.toml`, `go.mod`, `build.gradle`, `pyproject.toml`, `*.csproj`, `pom.xml`
   - Module directories: `src/`, `lib/`, `packages/`, `modules/`, `features/`, `apps/`
   - Test directories: `tests/`, `test/`, `__tests__/`, `*Tests/`, `spec/`
3. **Identify dependency management** — look for lock files and dependency declarations
4. **Identify interface/implementation patterns** — protocols, interfaces, abstract classes, traits

### 3. Generate Reports

For each requested report, follow the format specification below. Always include `**Generated:** YYYY-MM-DD` at the top of each report.

### 4. Update Index

After generating reports, update `docs/codebase-analytics/README.md` with links to all reports and a quick summary table.

## Report Specifications

### Stats (`stats.md`)

**Data collection:**
```bash
# Overall summary
cloc --vcs=git --quiet

# Per-module breakdown (adapt directory patterns to the project)
for dir in <module_dirs>; do
    name=$(basename "$dir")
    cloc --vcs=git "$dir" --include-lang=<primary_lang> --quiet --csv 2>/dev/null | grep '<primary_lang>' | awk -F, '{print $NF}'
done
```

**Required sections:**
1. Overall summary table (language, files, blank, comment, code)
2. Per-module source LOC breakdown (sorted by size descending)
3. Per-module test LOC breakdown
4. Distribution visualization (text-based bar chart)
5. Test coverage ratio by module (test LOC / source LOC)

**Notes section** explaining what was counted and what was excluded.

---

### Dependency Graph (`dependency-graph.md`)

**Data collection:**
- Read all package manifests to extract declared dependencies
- For monorepos, map which internal modules depend on which

**Required sections:**
1. Module hierarchy with dependency levels (level 0 = no deps, level N = depends on level N-1)
2. ASCII dependency diagram showing direction of dependencies
3. Inter-module dependency table (what depends on what, why)
4. Key constraints (circular dependency prevention, layering rules)

**Formatting:**
- Use `→` for dependency direction
- Group by layer (foundation, infrastructure, feature, orchestrator)
- Note any circular or unusual dependencies

---

### Third-Party Dependencies (`third-party-dependencies.md`)

**Data collection:**
- Parse package manifests for external dependency declarations
- Extract: package name, version constraint, URL/registry
- Check lock files for transitive dependencies

**Required sections:**
1. Direct dependencies table (name, version, URL, used in which modules, purpose)
2. Per-dependency description — what it does and why it was chosen
3. Transitive dependencies table (name, pulled by, purpose)
4. Dependency minimalism notes — what the project deliberately does NOT use externally and why

If ADRs exist for dependency choices, link to them.

---

### Module Coupling (`module-coupling.md`)

**Data collection:**
```bash
# Count import/require/use statements per module
# Adapt the import pattern to the language:
#   Swift:      ^import ModuleName
#   TypeScript: ^import .* from ['"]module
#   Python:     ^(from module|import module)
#   Go:         "module/path"
#   Rust:       ^use crate::module
#   Java:       ^import package\.module

grep -r "^import " <module>/Sources/ | sort | uniq -c | sort -rn
```

**Required sections:**
1. Import frequency table by module (rows = importing module, columns = imported module)
2. Observations — which modules have unusually high or low coupling
3. Cross-module coupling — which modules import other same-level modules (horizontal coupling)
4. Coupling health summary table (metric, value, assessment)

---

### Largest Files (`largest-files.md`)

**Data collection:**
```bash
git ls-files '*.<ext>' | xargs wc -l | sort -rn | head -25
```

**Required sections:**
1. Top 20 files by line count (rank, lines, file path, category)
2. Distribution by category (test, view/UI, model, service, etc.)
3. Notable source files (non-test) — the largest non-test files with brief descriptions of why they are large and whether they warrant attention

---

### Service Inventory (`service-inventory.md`)

**Applicability:** Only generate this report if the codebase uses an interface/implementation pattern (protocols, interfaces, abstract classes, traits).

**Data collection:**
- Find all interface/protocol definitions
- For each, find implementations and test doubles
- Find dependency registration/wiring (DI container, module declarations)

**Required sections:**
1. Overview — total count, where interfaces/implementations/mocks live
2. Mapping table grouped by domain (name, implementation(s), mock, purpose)
3. Implementation patterns — naming conventions, multi-implementation services
4. Service directory layout (tree view of implementation files)

## Output Directory Structure

```
docs/
└── codebase-analytics/
    ├── README.md                    ← Index with summary + regeneration commands
    ├── stats.md                     ← Lines of code
    ├── dependency-graph.md          ← Internal module DAG
    ├── third-party-dependencies.md  ← External packages
    ├── module-coupling.md           ← Import frequency analysis
    ├── largest-files.md             ← Complexity hotspots
    └── service-inventory.md         ← Interface-implementation mapping
```

## README Index Format

```markdown
# Codebase Analytics

Quantitative analysis of the codebase. These reports are point-in-time snapshots.

**Last updated:** YYYY-MM-DD

## Reports

| Report | Description |
|--------|-------------|
| [stats.md](stats.md) | Lines of code by language, directory, and module |
| [dependency-graph.md](dependency-graph.md) | Internal module dependency DAG |
| ... |

## Quick Summary

| Metric | Value |
|--------|-------|
| Total source LOC | X |
| Files | X |
| Modules | X |
| External dependencies | X |
| Test LOC ratio | X% |

## Regenerating

<shell commands to regenerate each report>
```

## Guidelines

- **Language-agnostic** — adapt import patterns, file extensions, and package manifests to whatever language the project uses. Do not assume any specific language.
- **Git-aware** — always use `--vcs=git` or `git ls-files` to exclude generated files, build artifacts, and vendored dependencies.
- **Point-in-time** — every report includes the generation date. Reports become stale as the codebase evolves.
- **Non-destructive** — if reports already exist, regenerate them in place (overwrite with fresh data). Never delete reports the user may have manually edited without asking.
- **Focused** — the user can request a single report (e.g., "just the dependency graph") or all reports. Default to all when the user says "analyze the codebase".
- **Actionable observations** — don't just list data. Add brief observations highlighting unusual patterns, potential issues, or notable strengths.
- **Link to other docs** — if ADRs, feature docs, or architecture docs exist in the project, link to them where relevant.
