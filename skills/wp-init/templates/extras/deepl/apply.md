# deepl overlay

Add the DeepL `.po` translation pipeline. **Requires `polylang` overlay (or
multi-language) — abort if `MULTI_LANG` is false.**

## Add files

- `scripts/translate-po.mjs`
- `scripts/.env.deepl.example`
- `scripts/package.json`
- `docs/translations.md`

No core file patches are required — `.gitignore` already conditionally
ignores `scripts/.env.deepl` when `DEEPL_ENABLED` is true (see
`core/root/.gitignore.tmpl`), and the AGENTS.md / README.md templates have
conditional sections that activate from `DEEPL_ENABLED`.
