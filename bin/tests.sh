#!/bin/bash
docker build -t dataswitcher_logistics_client .
docker run --rm --network host dataswitcher_logistics_client