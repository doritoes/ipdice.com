FROM trafex/php-nginx
MAINTAINER doritoes <seth.holcomb@gmail.com>
USER nobody
COPY --chown=nobody app/ /var/www/html/
# Expose the port nginx is reachable on
EXPOSE 8080
