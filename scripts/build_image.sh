#!/bin/bash
set -eou pipefail

source "scripts/lib.sh"

dockerfile="dev.Dockerfile"
environ="dev"

for arg in "$@"
do
    if [ "$arg" == "--prod" ]
    then
        dockerfile="prod.Dockerfile"
        environ="prod"
    fi
done

docker build -t "${IMAGE_NAME}_${environ}" -f $dockerfile .