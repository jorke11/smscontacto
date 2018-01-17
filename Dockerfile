FROM nimmis/apache-php5

MAINTAINER jorke11 <jpinedom@hotmail.com>
EXPOSE 80
EXPOSE 443

ADD apache-config.conf /etc/apache2/sites-enabled/000-default.conf

RUN a2enmod rewrite
RUN service apache2 restart

CMD ["/usr/sbin/apache2ctl","-D","FOREGROUND"]


