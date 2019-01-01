FROM debian:latest

ARG sphinx_config_folder
ARG sphinx_data_folder

ARG postgres_host
ARG postgres_user
ARG postgres_db
ARG postgres_password

RUN apt-get update
RUN apt-get install -y wget sed libpq-dev

RUN wget -nv -O - http://sphinxsearch.com/files/sphinx-3.1.1-612d99f-linux-amd64.tar.gz | tar zx

RUN cd sphinx-3.1.1/bin && cp searchd /usr/bin/searchd && cp indexer /usr/bin/indexer

RUN rm -Rf $sphinx_config_folder

RUN mkdir -p $sphinx_data_folder
RUN mkdir -p $sphinx_config_folder
RUN mkdir -p /var/log
RUN mkdir -p /usr/local/var/data
RUN mkdir -p /var/lib/sphinx
RUN mkdir -p /var/lib/sphinx
RUN mkdir -p /var/lib/sphinx/data

ADD sphinx.conf $sphinx_config_folder/sphinx.conf

RUN sed -i -e 's/{{POSTGRES_HOST}}/'$postgres_host'/g' $sphinx_config_folder/sphinx.conf
RUN sed -i -e 's/{{POSTGRES_USER}}/'$postgres_user'/g' $sphinx_config_folder/sphinx.conf
RUN sed -i -e 's/{{POSTGRES_PASSWORD}}/'$postgres_password'/g' $sphinx_config_folder/sphinx.conf
RUN sed -i -e 's/{{POSTGRES_DB}}/'$postgres_db'/g' $sphinx_config_folder/sphinx.conf

COPY ./docker-entrypoint.sh /
RUN chmod a+x docker-entrypoint.sh

ENTRYPOINT ["/docker-entrypoint.sh"]




