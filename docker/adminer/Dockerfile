FROM hardware/adminer

RUN apk update && apk upgrade && \
    apk add \
        wget \
        php7-mysqli@community \
        php7-pgsql@community \
        php7-mongodb@community && \
    apk del wget ca-certificates && \
    rm -rf /var/cache/apk/* /tmp/*

CMD ["/sbin/tini", "--", "run.sh"]