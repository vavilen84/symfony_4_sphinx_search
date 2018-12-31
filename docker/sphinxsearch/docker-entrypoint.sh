#!/bin/bash

/usr/bin/indexer --config /etc/sphinxsearch/sphinx.conf --all --rotate
/usr/bin/searchd --config /etc/sphinxsearch/sphinx.conf --nodetach