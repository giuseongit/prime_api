#!/bin/bash
set -eou pipefail

apachectl -D BACKGROUND
exec "$@"