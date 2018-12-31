FROM nginx:1.12

ARG NGINX_TEMPLATE
ARG NGINX_SERVER_NAME
ARG NGINX_ROOT

RUN rm /etc/nginx/conf.d/default.conf
ADD $NGINX_TEMPLATE /etc/nginx/conf.d/

RUN /usr/bin/envsubst '$$NGINX_SERVER_NAME $$NGINX_ROOT' < /etc/nginx/conf.d/$NGINX_TEMPLATE > /etc/nginx/conf.d/default.conf
RUN rm /etc/nginx/conf.d/$NGINX_TEMPLATE

EXPOSE 80 443

CMD ["nginx", "-g", "daemon off;"]