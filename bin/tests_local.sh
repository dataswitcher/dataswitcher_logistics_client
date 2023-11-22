#!/bin/bash
docker build -t dataswitcher_logistics_client .
docker run --add-host=logistics.dataswitcher.test:127.0.0.1 --rm --network host dataswitcher_logistics_client