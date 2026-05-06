# ftp-deploy overlay

Add FTPS deploy scripts and docs.

## Add files (rendered with the substitution map)

- `.env.deploy.example`
- `scripts/deploy-ftp.sh` (chmod +x)
- `scripts/test-ftp.sh` (chmod +x)
- `docs/deployment-ftp.md`

## Patch existing files

### `README.md`

Find the line `<!-- WP_INIT:DEPLOY_SECTION -->` and append the following
**after** it:

```markdown

## Deploy

```bash
cp .env.deploy.example .env.deploy   # fill in FTP creds
./scripts/test-ftp.sh                # verify connection (dry run)
./scripts/deploy-ftp.sh              # mirror theme over FTPS
```

See [docs/deployment-ftp.md](docs/deployment-ftp.md) for details.
```

If the sentinel is missing, abort with: `ftp-deploy overlay: README.md is
missing the WP_INIT:DEPLOY_SECTION sentinel — re-render core/root/README.md.tmpl`.
