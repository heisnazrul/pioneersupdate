#!/usr/bin/env bash
#
# Deploy Backendupdate to live shared hosting (app.pioneersedu.com)
#
# Usage:
#   ./sync.sh --sync full    # full codebase (excludes .env, vendor, uploads)
#   ./sync.sh --sync core    # app code only (faster day-to-day deploys)
#   ./sync.sh --sync seeders # database/seeders only (classes + assets/)
#   ./sync.sh --sync full --dry-run
#   ./sync.sh --ssh-guide          Manual SSH login & migrate instructions
#
# Config: .deploy.env (gitignored) or environment variables.
# Protected files: see .deploy-exclude (.env, sync.sh, vendor, uploads, etc.)
# macOS password auth: brew install hudochenkov/sshpass/sshpass
#
set -euo pipefail

ROOT="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
ENV_FILE="$ROOT/.deploy.env"
EXCLUDE_FILE="$ROOT/.deploy-exclude"

if [[ -f "$ENV_FILE" ]]; then
  # shellcheck disable=SC1090
  source "$ENV_FILE"
fi

DEPLOY_HOST="${DEPLOY_HOST:-199.188.200.183}"
DEPLOY_PORT="${DEPLOY_PORT:-21098}"
DEPLOY_USER="${DEPLOY_USER:-pionueoc}"
DEPLOY_PATH="${DEPLOY_PATH:-/home/pionueoc/app.pioneersedu.com}"
DEPLOY_PHP="${DEPLOY_PHP:-php}"
DEPLOY_COMPOSER="${DEPLOY_COMPOSER:-composer}"
DEPLOY_PASSWORD="${DEPLOY_PASSWORD:-}"

REMOTE="${DEPLOY_USER}@${DEPLOY_HOST}:${DEPLOY_PATH}/"
# Short path required — macOS Unix sockets max ~104 chars (%C = hash of host/user/port)
SSH_CONTROL_PATH="${HOME}/.ssh/pione-cm-%C"

DRY_RUN=0
SYNC_MODE=""

manual_ssh_guide() {
  cat <<EOF
================================================================================
  Manual SSH login & migrate (shared hosting)
================================================================================

Migrations are NOT run automatically by sync.sh — run them yourself after deploy.

--- 1. SSH into the server ---

  ssh -p ${DEPLOY_PORT} ${DEPLOY_USER}@${DEPLOY_HOST}

  Password: see Backendupdate/.deploy.env (DEPLOY_PASSWORD)

  Or one-liner with sshpass (from Backendupdate folder):

  source .deploy.env && SSHPASS="\$DEPLOY_PASSWORD" sshpass -e \\
    ssh -p "\$DEPLOY_PORT" "\${DEPLOY_USER}@\${DEPLOY_HOST}"

--- 2. Go to the Laravel project ---

  cd ${DEPLOY_PATH}
  pwd          # should show: ${DEPLOY_PATH}
  ls artisan   # confirm Laravel root

--- 3. Run migrations ---

  php artisan migrate --force

  If \`php\` is too old on shared hosting, use cPanel PHP 8.3 instead:

  /usr/local/bin/ea-php83 artisan migrate --force

  Check pending migrations first (optional):

  php artisan migrate:status

--- 4. Clear & rebuild caches (after migrate or code deploy) ---

  php artisan config:clear
  php artisan route:clear
  php artisan view:clear
  php artisan cache:clear
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
  php artisan cache:clear-api

--- 5. Full deploy only: install Composer dependencies (if needed) ---

  composer install --no-dev --optimize-autoloader --no-interaction

  Or:

  /usr/local/bin/ea-php83 /usr/local/bin/composer install --no-dev --optimize-autoloader

--- 6. Verify ---

  https://app.pioneersedu.com/up
  https://app.pioneersedu.com/login

--- 7. Exit SSH ---

  exit

================================================================================
EOF
}

usage() {
  cat <<'EOF'
Backendupdate deploy script

Usage:
  ./sync.sh --sync full              Upload full project (recommended after dependency changes)
  ./sync.sh --sync core              Upload app/config/routes/views/migrations only
  ./sync.sh --sync seeders            Upload database/seeders only (classes + assets)
  ./sync.sh --sync full --dry-run    Preview rsync changes without uploading
  ./sync.sh --ssh-guide              Show manual SSH login & migrate steps

Options:
  --dry-run      Show what would be synced (rsync --dry-run)
  -h, --help     Show this help

Config file: Backendupdate/.deploy.env
  DEPLOY_HOST, DEPLOY_PORT, DEPLOY_USER, DEPLOY_PASSWORD, DEPLOY_PATH
  DEPLOY_PHP, DEPLOY_COMPOSER (optional shared-hosting paths)

Post-deploy (automatic — migrate is manual):
  config:cache, route:cache, view:cache, cache:clear, cache:clear-api
  (full sync also runs: composer install)

Migrations: NOT automated — run manually after deploy:
  ./sync.sh --ssh-guide

Never pushed to server (see .deploy-exclude):
  .env, .deploy.env, sync.sh, vendor/, uploads, logs, tests, IDE/git files
EOF
  exit "${1:-0}"
}

log() {
  printf '\033[1;34m==>\033[0m %s\n' "$*"
}

warn() {
  printf '\033[1;33m!!>\033[0m %s\n' "$*" >&2
}

die() {
  printf '\033[1;31mERROR:\033[0m %s\n' "$*" >&2
  exit 1
}

build_rsync_shell() {
  local ssh_cmd="ssh -p ${DEPLOY_PORT} -o StrictHostKeyChecking=accept-new -o ConnectTimeout=30 -o ControlMaster=auto -o ControlPath=${SSH_CONTROL_PATH} -o ControlPersist=300"

  if [[ -n "${SSHPASS:-}" ]] && command -v sshpass >/dev/null 2>&1; then
    printf 'sshpass -e %s' "$ssh_cmd"
    return
  fi

  if [[ -n "$DEPLOY_PASSWORD" ]]; then
    warn "sshpass not found — trying SSH key auth."
    warn "Install: brew install hudochenkov/sshpass/sshpass"
  fi

  printf '%s' "$ssh_cmd"
}

setup_ssh_control() {
  mkdir -p "${HOME}/.ssh"
  chmod 700 "${HOME}/.ssh"
}

close_ssh_control() {
  ssh -p "$DEPLOY_PORT" -O exit \
    -o "ControlPath=${SSH_CONTROL_PATH}" \
    "${DEPLOY_USER}@${DEPLOY_HOST}" 2>/dev/null || true
}

prepare_ssh_auth() {
  if [[ -n "$DEPLOY_PASSWORD" ]] && command -v sshpass >/dev/null 2>&1; then
    export SSHPASS="$DEPLOY_PASSWORD"
  fi
}

run_remote() {
  local remote_cmd="$1"
  local ssh_cmd=(
    ssh -p "$DEPLOY_PORT"
    -o StrictHostKeyChecking=accept-new
    -o ConnectTimeout=30
    -o ControlMaster=auto
    -o "ControlPath=${SSH_CONTROL_PATH}"
    -o ControlPersist=300
  )

  if [[ -n "$DEPLOY_PASSWORD" ]] && command -v sshpass >/dev/null 2>&1; then
    SSHPASS="$DEPLOY_PASSWORD" sshpass -e "${ssh_cmd[@]}" "${DEPLOY_USER}@${DEPLOY_HOST}" "$remote_cmd"
  else
    "${ssh_cmd[@]}" "${DEPLOY_USER}@${DEPLOY_HOST}" "$remote_cmd"
  fi
}

rsync_exclude_args() {
  [[ -f "$EXCLUDE_FILE" ]] || die "Missing $EXCLUDE_FILE"
  printf '%s\n' --exclude-from="$EXCLUDE_FILE"
}

rsync_to_remote() {
  local -a extra_args=("$@")
  local -a rsync_args=(-avz --human-readable --progress)

  if [[ "$DRY_RUN" -eq 1 ]]; then
    rsync_args+=(--dry-run)
  fi

  rsync "${rsync_args[@]}" $(rsync_exclude_args) "${extra_args[@]}" \
    -e "$(build_rsync_shell)" \
    "$ROOT/" "$REMOTE"
}

sync_full() {
  log "Full sync → ${DEPLOY_USER}@${DEPLOY_HOST}:${DEPLOY_PATH}"
  log "Protected from upload: .env, sync.sh, vendor/, server uploads (see .deploy-exclude)"
  rsync_to_remote --delete
  post_deploy full
}

sync_core() {
  log "Core sync → ${DEPLOY_USER}@${DEPLOY_HOST}:${DEPLOY_PATH}"
  log "Protected from upload: .env, sync.sh, vendor/, server uploads (see .deploy-exclude)"
  log "Using one rsync + one SSH connection (shared hosting friendly)"

  local -a rsync_args=(-avz --human-readable --progress --timeout=120)
  local -a core_includes=(
    --include='*/'
    --include='app/***'
    --include='bootstrap/app.php'
    --include='bootstrap/providers.php'
    --include='config/***'
    --include='database/migrations/***'
    --include='database/seeders/***'
    --include='.htaccess'
    --include='index.php'
    --include='public/.htaccess'
    --include='public/favicon.ico'
    --include='public/index.php'
    --include='public/robots.txt'
    --exclude='public/storage'
    --exclude='public/storage/**'
    --include='resources/***'
    --include='routes/***'
    --include='artisan'
    --include='composer.json'
    --include='composer.lock'
    --exclude='*'
  )

  if [[ "$DRY_RUN" -eq 1 ]]; then
    rsync_args+=(--dry-run)
  fi

  rsync "${rsync_args[@]}" "${core_includes[@]}" $(rsync_exclude_args) \
    -e "$(build_rsync_shell)" \
    "$ROOT/" "$REMOTE" || die "Core sync failed."

  post_deploy core
}

sync_seeders() {
  local src="$ROOT/database/seeders/"
  local dest="${DEPLOY_USER}@${DEPLOY_HOST}:${DEPLOY_PATH}/database/seeders/"

  [[ -d "$src" ]] || die "Missing local folder: database/seeders/"

  log "Seeders sync → ${DEPLOY_USER}@${DEPLOY_HOST}:${DEPLOY_PATH}/database/seeders/"
  log "Uploading seeder classes and assets/ only (no post-deploy cache rebuild)"

  local -a rsync_args=(-avz --human-readable --progress --timeout=120)

  if [[ "$DRY_RUN" -eq 1 ]]; then
    rsync_args+=(--dry-run)
  fi

  rsync "${rsync_args[@]}" \
    -e "$(build_rsync_shell)" \
    "$src" "$dest" || die "Seeders sync failed."

  if [[ "$DRY_RUN" -eq 1 ]]; then
    warn "Dry run — no files uploaded."
    return
  fi

  log "Seeders upload complete."
  warn "Run on server after SSH:"
  warn "  cd ${DEPLOY_PATH}"
  warn "  php artisan db:seed --class=YourSeeder --force"
  warn "  php artisan cache:clear-api"
}

post_deploy() {
  local mode="${1:-core}"

  if [[ "$DRY_RUN" -eq 1 ]]; then
    warn "Dry run — skipping remote post-deploy commands."
    return
  fi

  log "Post-deploy on server ($mode)"

  local remote_cmd="set -e
cd '${DEPLOY_PATH}'"

  if [[ "$mode" == "full" ]]; then
    remote_cmd+="
if command -v '${DEPLOY_COMPOSER}' >/dev/null 2>&1; then
  ${DEPLOY_COMPOSER} install --no-dev --optimize-autoloader --no-interaction
else
  ${DEPLOY_PHP} ${DEPLOY_COMPOSER} install --no-dev --optimize-autoloader --no-interaction
fi"
  fi

  remote_cmd+="
${DEPLOY_PHP} artisan config:clear
${DEPLOY_PHP} artisan route:clear
${DEPLOY_PHP} artisan view:clear
${DEPLOY_PHP} artisan cache:clear
${DEPLOY_PHP} artisan config:cache
${DEPLOY_PHP} artisan route:cache
${DEPLOY_PHP} artisan view:cache
${DEPLOY_PHP} artisan cache:clear-api
chmod -R 775 storage bootstrap/cache 2>/dev/null || true"

  run_remote "$remote_cmd"

  log "Deploy complete."
  log "Verify: https://app.pioneersedu.com/up"
  warn "Migrations were NOT run automatically."
  warn "Run manually: ./sync.sh --ssh-guide"
}

parse_args() {
  while [[ $# -gt 0 ]]; do
    case "$1" in
      --sync)
        SYNC_MODE="${2:-}"
        shift 2
        ;;
      --dry-run)
        DRY_RUN=1
        shift
        ;;
      --ssh-guide)
        manual_ssh_guide
        exit 0
        ;;
      -h|--help)
        usage 0
        ;;
      *)
        die "Unknown argument: $1 (try --help)"
        ;;
    esac
  done
}

main() {
  parse_args "$@"

  command -v rsync >/dev/null 2>&1 || die "rsync is required."
  command -v ssh >/dev/null 2>&1 || die "ssh is required."
  setup_ssh_control
  prepare_ssh_auth
  trap close_ssh_control EXIT

  case "$SYNC_MODE" in
    full)
      sync_full
      ;;
    core)
      sync_core
      ;;
    seeders)
      sync_seeders
      ;;
    "")
      usage 1
      ;;
    *)
      die "Unknown sync mode: $SYNC_MODE (use 'full', 'core', or 'seeders')"
      ;;
  esac
}

main "$@"
