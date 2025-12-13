#!/bin/bash

VERSION=$(node -p "require('./package.json').version")
DATE=$(date +%Y%m%d)
COUNT=$(git rev-list --count HEAD)

echo "v$VERSION-build-$DATE-$COUNT"