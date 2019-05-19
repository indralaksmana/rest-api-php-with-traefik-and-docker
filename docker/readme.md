# Containers

Each application may require different services & configuration.
For now all applications are using the same `php:7.1-apache` image


Setup
-------------

**Create traefik _web_ network**
```
docker network create -d bridge --subnet 172.19.0.0/16 \
--gateway=172.19.0.1 \
--opt com.docker.network.bridge.enable_icc=true \
--opt com.docker.network.bridge.enable_ip_masquerade=true \
--opt com.docker.network.bridge.host_binding_ipv4=0.0.0.0 \
--opt com.docker.network.driver.mtu=1500 \
traefik
```

**Create second _webgateway_ network**
```
docker network create -d bridge --subnet 172.20.0.0/16 \
--gateway=172.20.0.1 \
--opt com.docker.network.bridge.enable_icc=true \
--opt com.docker.network.bridge.enable_ip_masquerade=true \
--opt com.docker.network.bridge.host_binding_ipv4=0.0.0.0 \
--opt com.docker.network.driver.mtu=1500 \
webgateway
```

**Composer install on each folders (api-gateway, inventory, order, user)**