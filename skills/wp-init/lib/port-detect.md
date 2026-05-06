# Port collision detection

For each candidate port, check both Docker port mappings and any host-side
listener:

```bash
is_port_free() {
  local port=$1
  # Docker container publishing this host port?
  if docker ps --format '{{.Ports}}' 2>/dev/null | grep -E "(^|,| )(0\.0\.0\.0:|:::|127\.0\.0\.1:)?${port}->" >/dev/null; then
    return 1
  fi
  # Any listener on this port (any interface)?
  if lsof -nP -iTCP:"${port}" -sTCP:LISTEN >/dev/null 2>&1; then
    return 1
  fi
  return 0
}
```

Search order per role:

| Role        | Candidates                  |
| ----------- | --------------------------- |
| WP          | 8090, 8092, 8094, 8096, 8098 |
| phpMyAdmin  | wp_port + 1                 |
| Database    | 3307, 3308, 3309, 3310      |

If all candidates are taken, prompt the user for a port instead of failing.
