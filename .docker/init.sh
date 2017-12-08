#!/usr/bin/env bash

sed -i "s~.*\(XDEBUG_CONFIG\s*=\s*\).*~\1$(/sbin/ip route|awk '/default/ { print $3 }')~" $(pwd)/.env