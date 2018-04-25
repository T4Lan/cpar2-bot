#!/bin/bash
# set -x

# Defined Variables
# $SERVER_HOST
# $SERVER_PORT
# $SERVER_USERNAME
# $DEPLOY_DIR

echo "-- SSH Config --"

# configures ssh agent and private key
eval "$(ssh-agent -s)" # Start ssh-agent cache
openssl aes-256-cbc -K $encrypted_0c5ce02c3461_key -iv $encrypted_0c5ce02c3461_iv -in deploy/molotec.enc -out deploy/molotec -d
chmod 600 "deploy/molotec" # Allow read access to the private key
ssh-add "deploy/molotec" # Add the private key to SSH

echo "-- Local Work --"

# generates prod env config
# envsubst < .env.prod > .env
# cat .env

# path to upload to dir
DEPLOY_DIR="cpar2-bot"

echo "-- Remote Work Previous to Upload--"

ssh -t -p $SERVER_PORT $SERVER_USERNAME@$SERVER_HOST <<EOF
  cd $DEPLOY_DIR
  docker-compose down
  exit
EOF

echo "-- Upload to Remote --"

# send every file to server
# ssh -p $SERVER_PORT $SERVER_USERNAME@$SERVER_HOST "mkdir $DEPLOY_DIR"
rsync -a -e "ssh -p $SERVER_PORT" "$PWD" "$SERVER_USERNAME@$SERVER_HOST:~/" --exclude=".git" --exclude="deploy/" --exclude="storage/"
# ssh -p $SERVER_PORT $SERVER_USERNAME@$SERVER_HOST "ln -s $DEPLOY_DIR/public public_html"

echo "-- Remote Work Post Upload--"

# ssh -p $SERVER_PORT $SERVER_USERNAME@$SERVER_HOST "ls -la $DEPLOY_DIR"
ssh -t -p $SERVER_PORT $SERVER_USERNAME@$SERVER_HOST <<EOF
 cd $DEPLOY_DIR
 cp .env.prod .env
 docker-compose up -d
 docker-compose exec workspace composer install
 docker-compose exec workspace php artisan config:clear
 docker-compose exec workspace php artisan key:generate
 docker-compose exec workspace php artisan migrate
 exit
EOF

echo "-- Deploy Ended --"
