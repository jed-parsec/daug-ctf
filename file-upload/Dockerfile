# Use official PHP Apache image
FROM php:8.2-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    && rm -rf /var/lib/apt/lists/*

# Copy web app files
COPY app/ /var/www/html/

# Create uploads directory and set permissions
RUN mkdir -p /var/www/html/uploads \
    && chown -R www-data:www-data /var/www/html/uploads \
    && chmod -R 755 /var/www/html/uploads

# Copy flag
COPY secret/flag.txt /secret/flag.txt

# Set flag permissions
RUN chown root:root /secret/flag.txt \
    && chmod 444 /secret/flag.txt

# Copy start script
COPY start.sh /start.sh
RUN chmod +x /start.sh

# Expose port 80
EXPOSE 80

# Start Apache
CMD ["/start.sh"]
