#!/bin/bash
#
# Project Initialization Script
#
# This script renames the project to use a custom name.
# Run this immediately after cloning, before pnpm install.
#
# Usage:
#   ./init.sh
#

set -e

# Default values
OLD_NAME="piiiqcy"
OLD_SCOPE="@piiiqcy"
OLD_DISPLAY="piiiQcy"
OLD_THEME_DISPLAY="piiiQcy Theme"

echo ""
echo "=== piiiQcy Project Setup ==="
echo ""
echo "Current project name: $OLD_NAME"
echo ""

# Prompt for new name
read -p "Enter new project name (lowercase, hyphens allowed): " NEW_NAME

# Validate input
if [[ -z "$NEW_NAME" ]]; then
    echo "Error: Project name is required"
    exit 1
fi

if [[ ! "$NEW_NAME" =~ ^[a-z][a-z0-9-]*$ ]]; then
    echo "Error: Name must start with a letter and contain only lowercase letters, numbers, and hyphens"
    exit 1
fi

# Generate derived values
NEW_SCOPE="@${NEW_NAME}"
read -p "Enter display name (default: $NEW_NAME): " NEW_DISPLAY
NEW_DISPLAY="${NEW_DISPLAY:-$NEW_NAME}"
NEW_THEME_DISPLAY="${NEW_DISPLAY} Theme"

# Port configuration (optional)
echo ""
echo "Port configuration (press Enter to use defaults):"
read -p "WordPress port (default: 8000): " WP_PORT
WP_PORT="${WP_PORT:-8000}"
read -p "phpMyAdmin port (default: 8080): " PMA_PORT
PMA_PORT="${PMA_PORT:-8080}"
read -p "Vite dev server port (default: 3000): " VITE_PORT
VITE_PORT="${VITE_PORT:-3000}"

# Environment URLs (optional)
echo ""
echo "Environment URLs for DB export (press Enter to use defaults):"
read -p "Production URL (default: https://example.com): " URL_PROD
URL_PROD="${URL_PROD:-https://example.com}"
read -p "Staging URL (default: https://stg.example.com): " URL_STG
URL_STG="${URL_STG:-https://stg.example.com}"

echo ""
echo "New configuration:"
echo "  Name: $NEW_NAME"
echo "  Scope: $NEW_SCOPE"
echo "  Display: $NEW_DISPLAY"
echo "  Theme Display: $NEW_THEME_DISPLAY"
echo "  WordPress Port: $WP_PORT"
echo "  phpMyAdmin Port: $PMA_PORT"
echo "  Vite Port: $VITE_PORT"
echo "  Production URL: $URL_PROD"
echo "  Staging URL: $URL_STG"
echo ""

read -p "Proceed? (y/n): " CONFIRM
if [[ "$CONFIRM" != "y" && "$CONFIRM" != "Y" ]]; then
    echo "Cancelled"
    exit 0
fi

echo ""
echo "Renaming project..."

# Detect OS for sed compatibility
if [[ "$OSTYPE" == "darwin"* ]]; then
    # macOS
    SED_CMD="sed -i ''"
else
    # Linux
    SED_CMD="sed -i"
fi

# Helper function for sed replacement
replace_in_file() {
    local file=$1
    local search=$2
    local replace=$3
    if [[ -f "$file" ]]; then
        if [[ "$OSTYPE" == "darwin"* ]]; then
            sed -i '' "s|$search|$replace|g" "$file"
        else
            sed -i "s|$search|$replace|g" "$file"
        fi
        echo "  Updated: $file"
    fi
}

# Update project.config.ts
echo "Updating project.config.ts..."
replace_in_file "project.config.ts" "name: '$OLD_NAME'" "name: '$NEW_NAME'"
replace_in_file "project.config.ts" "scope: '$OLD_SCOPE'" "scope: '$NEW_SCOPE'"
replace_in_file "project.config.ts" "displayName: '$OLD_DISPLAY'" "displayName: '$NEW_DISPLAY'"
replace_in_file "project.config.ts" "displayName: '$OLD_THEME_DISPLAY'" "displayName: '$NEW_THEME_DISPLAY'"
replace_in_file "project.config.ts" "prefix: '$OLD_NAME'" "prefix: '$NEW_NAME'"
replace_in_file "project.config.ts" "network: '${OLD_NAME}_network'" "network: '${NEW_NAME}_network'"

# Update root package.json
echo "Updating package.json..."
replace_in_file "package.json" "\"name\": \"$OLD_NAME\"" "\"name\": \"$NEW_NAME\""
replace_in_file "package.json" "$OLD_SCOPE" "$NEW_SCOPE"

# Update packages/theme/package.json
echo "Updating theme package.json..."
replace_in_file "packages/theme/package.json" "$OLD_SCOPE" "$NEW_SCOPE"

# Update plugin package.json files
echo "Updating plugin package.json files..."
for file in packages/plugins/*/package.json; do
    if [[ -f "$file" ]]; then
        replace_in_file "$file" "$OLD_SCOPE" "$NEW_SCOPE"
    fi
done

# Update block.json files
echo "Updating block.json files..."
for file in packages/plugins/*/src/blocks/*/block.json; do
    if [[ -f "$file" ]]; then
        replace_in_file "$file" "\"name\": \"$OLD_NAME/" "\"name\": \"$NEW_NAME/"
    fi
done

# Update .env.example
echo "Updating .env.example..."
replace_in_file ".env.example" "PREFIX=$OLD_NAME" "PREFIX=$NEW_NAME"
replace_in_file ".env.example" "WP_PORT=8000" "WP_PORT=$WP_PORT"
replace_in_file ".env.example" "PMA_PORT=8080" "PMA_PORT=$PMA_PORT"
replace_in_file ".env.example" "VITE_PORT=3000" "VITE_PORT=$VITE_PORT"
replace_in_file ".env.example" "URL_LOCAL=http://localhost:8000" "URL_LOCAL=http://localhost:$WP_PORT"
replace_in_file ".env.example" "URL_STG=https://stg.example.com" "URL_STG=$URL_STG"
replace_in_file ".env.example" "URL_PROD=https://example.com" "URL_PROD=$URL_PROD"

# Update .env if it exists
if [[ -f ".env" ]]; then
    echo "Updating .env..."
    replace_in_file ".env" "PREFIX=$OLD_NAME" "PREFIX=$NEW_NAME"
    replace_in_file ".env" "WP_PORT=8000" "WP_PORT=$WP_PORT"
    replace_in_file ".env" "PMA_PORT=8080" "PMA_PORT=$PMA_PORT"
    replace_in_file ".env" "VITE_PORT=3000" "VITE_PORT=$VITE_PORT"
    replace_in_file ".env" "URL_LOCAL=http://localhost:8000" "URL_LOCAL=http://localhost:$WP_PORT"
    replace_in_file ".env" "URL_STG=https://stg.example.com" "URL_STG=$URL_STG"
    replace_in_file ".env" "URL_PROD=https://example.com" "URL_PROD=$URL_PROD"
fi

# Update Makefile
echo "Updating Makefile..."
replace_in_file "Makefile" "PREFIX=$OLD_NAME" "PREFIX=$NEW_NAME"

echo ""
echo "=== Rename Complete ==="
echo ""
echo "Next steps:"
echo "  1. cp .env.example .env  (if not already done)"
echo "  2. make setup"
echo "  3. pnpm dev"
echo ""
