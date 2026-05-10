#!/usr/bin/env bash
set -euo pipefail

ROOT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"
BUILD_DIR="$ROOT_DIR/build"
THEME_SLUG="hostinger-woo-starter"
PLUGIN_SLUG="visual-feedback-overlay"

rm -rf "$BUILD_DIR"
mkdir -p "$BUILD_DIR"

if ! command -v zip >/dev/null 2>&1; then
  echo "zip command is required. Install zip or use Hostinger/File Manager to upload folders directly." >&2
  exit 1
fi

(
  cd "$ROOT_DIR/wp-content/themes"
  zip -qr "$BUILD_DIR/$THEME_SLUG.zip" "$THEME_SLUG" -x "*/.DS_Store"
)

(
  cd "$ROOT_DIR/wp-content/plugins"
  zip -qr "$BUILD_DIR/$PLUGIN_SLUG.zip" "$PLUGIN_SLUG" -x "*/.DS_Store"
)

echo "Created:"
echo "- $BUILD_DIR/$THEME_SLUG.zip"
echo "- $BUILD_DIR/$PLUGIN_SLUG.zip"
