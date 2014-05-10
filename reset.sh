#!/bin/bash -x
app/console doctrine:database:drop --force
app/console doctrine:database:create
app/console doctrine:schema:create
app/console doctrine:fixtures:load
