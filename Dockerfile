FROM php:8.3-apache

# curl y mbstring ya vienen incluidos en la imagen oficial de PHP.
RUN a2enmod rewrite headers

# Bloquear acceso web directo a includes/ y sql/ (el PHP los lee desde disco).
RUN printf '<DirectoryMatch "^/var/www/html/(includes|sql)">\n    Require all denied\n</DirectoryMatch>\nServerTokens Prod\nServerSignature Off\n' > /etc/apache2/conf-available/gl-protect.conf \
    && a2enconf gl-protect

COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html

EXPOSE 10000

# Render entrega el puerto en la variable PORT; Apache escucha ahí en vez del 80.
CMD ["sh", "-c", "sed -ri \"s/Listen 80/Listen ${PORT:-10000}/\" /etc/apache2/ports.conf && sed -ri \"s/:80>/:${PORT:-10000}>/\" /etc/apache2/sites-available/000-default.conf && apache2-foreground"]
