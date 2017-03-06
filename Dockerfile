FROM php:5.6-apache
RUN apt-get update && \ 
	apt-get install -y php5-gmp libgmp-dev && \
	rm -rf /var/lib/apt/lists/*
RUN ln -s /usr/include/x86_64-linux-gnu/gmp.h /usr/include/gmp.h && \
	docker-php-ext-configure gmp --with-gmp=/usr/include/x86_64-linux-gnu && \
	docker-php-ext-install gmp
RUN a2enmod rewrite