#!/usr/bin/env bash

dependencies="${COMPOSER_INSTALL_DEPENDENCIES}"

if [[ ${dependencies} == "lowest" ]]; then
  composer update --no-interaction --no-progress --prefer-lowest

  exit $?
fi

if [[ ${dependencies} == "locked" ]]; then
  composer install --no-interaction --no-progress

  exit $?
fi

if [[ ${dependencies} == "highest" ]]; then
  composer update --no-interaction --no-progress

  exit $?
fi

echo "::error::The value for the \"dependencies\" input needs to be one of \"lowest\", \"locked\"', \"highest\"' - got \"${dependencies}\" instead."

exit 1
