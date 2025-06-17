# Use the official MariaDB 11.2 image as the base
FROM mariadb:11.2

# Install the MariaDB client tools
RUN apt-get update && \
    apt-get install -y mariadb-client && \
    rm -rf /var/lib/apt/lists/*

# Copy the initialization script
COPY ./docker/mysql/init.sql /docker-entrypoint-initdb.d/

# Copy the healthcheck script
COPY ./docker/healthcheck/mariadb-healthcheck.sh /usr/local/bin/healthcheck.sh
RUN chmod +x /usr/local/bin/healthcheck.sh

# Health check
HEALTHCHECK --interval=10s --timeout=5s --start-period=30s --retries=5 \
  CMD /usr/local/bin/healthcheck.sh
