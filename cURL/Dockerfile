# Use official PHP Apache image
FROM php:8.2-apache

# Install dependencies (none needed, but keeping for extensibility)
RUN apt-get update && apt-get install -y \
    && rm -rf /var/lib/apt/lists/*

# Copy web app files
COPY app/ /var/www/html/

# Copy flag
COPY secret/flag.txt /secret/flag.txt

# Set permissions
RUN chown root:root /secret/flag.txt \
    && chmod 444 /secret/flag.txt \
    && chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Copy start script
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["/start.sh"]
