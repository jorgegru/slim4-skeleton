#!/bin/bash

# Exit immediately if a command exits with a non-zero status
set -e

# Check if we can connect to the database and execute a simple query
if mariadb -h localhost -u root -p"$MYSQL_ROOT_PASSWORD" -e "SELECT 1;" >/dev/null 2>&1; then
    exit 0
fi

exit 1
