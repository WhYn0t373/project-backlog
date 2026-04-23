#!/usr/bin/env bash
set -e

# Variables – customize as needed
REPO_NAME="project-backlog"
ORG="WhYn0t373"
DEFAULT_BRANCH="main"
TEAM_BACKEND="backend"
TEAM_FRONTEND="frontend"

# 1. Create repository if it does not exist
gh repo create "$ORG/$REPO_NAME" \
  --public --confirm \
  --description "Project backlog and issue tracking" \
  --push

# 2. Add team members
gh repo add-team "$ORG/$REPO_NAME" "$TEAM_BACKEND" --permission push
gh repo add-team "$ORG/$REPO_NAME" "$TEAM_FRONTEND" --permission push

# 3. Enable default branch protection
#   * Require at least one approving review
#   * Enforce admin permissions
gh api -X PATCH "/repos/$ORG/$REPO_NAME" \
  -F "required_pull_request_reviews[required_approving_review_count]=1" \
  -F "enforce_admins=true"

# 4. Commit and push CODEOWNERS
#   Initialise a local repo, set the remote, create the default branch, and push the CODEOWNERS file
git init
git remote add origin "git@github.com:$ORG/$REPO_NAME.git"
git checkout -b "$DEFAULT_BRANCH"
git add CODEOWNERS
git commit -m "Add CODEOWNERS file"
git push -u origin "$DEFAULT_BRANCH"