#!/bin/bash
set -eou pipefail

source "scripts/lib.sh"

docker run -it --rm --name "${CONTAINER_NAME}_dev" -v $(pwd):/var/www/html -p 8888:80 "${IMAGE_NAME}_dev" /bin/bash
