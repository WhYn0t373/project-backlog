#!/usr/bin/env bash
set -euo pipefail

# Variables
REPO="WhYn0t373/project-backlog"
ORG="WhYn0t373"
TEAM_SLUG="project-backlog-team"
DESCRIPTION="Project backlog for WhYn0t373"
HOMEPAGE="https://whyn0t373.github.io/project-backlog"

# Create the repository
gh repo create "$REPO" \
  --public \
  --description "$DESCRIPTION" \
  --homepage "$HOMEPAGE"

# Add core team as collaborators with admin permissions
gh repo set-collaborator "$REPO" \
  --team "$TEAM_SLUG" \
  --permission admin

# Enable default branch protection on 'main'
gh api -X PATCH repos/"$REPO"/branches/main \
  -F required_status_checks.strict=true \
  -F enforce_admins=true \
  -F required_pull_request_reviews.required_approving_review_count=1

# Create CODEOWNERS file
cat > CODEOWNERS <<EOF
# Code owners for the project
# All files: @$ORG/$TEAM_SLUG
* @$ORG/$TEAM_SLUG
EOF