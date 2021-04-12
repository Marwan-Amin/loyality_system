#!/bin/bash
start=`date +%s`

#### START Cleaning docker environment during development
sudo rm -r loyality_system_database/
docker rm loyality_system_app loyality_system_database loyality_system_phpmyadmin -f
docker system prune -f
docker image prune -f
docker container prune -f
docker volume prune -f
#### END Cleaning docker environment during development

#### START Builing docker containers -------------------------------

cd loyality_system && composer install && cd ..
docker-compose up -d
sudo chmod -R 777 loyality_system_database/
#### END Builing docker containers ---------------------------------

#### START Configuring (loyality_system_app) microservice ----------------------
while ! docker exec loyality_system_database mysqladmin --user=root --password=secret --host "127.0.0.1" ping --silent &> /dev/null ; do
    echo "... Waiting for loyality system's database to be deployed ..."
    sleep 10
done
echo "... loyality system's database has been deployed successfully ..."

docker exec -it loyality_system_app chmod -R 777 /var/www/html
docker exec -it loyality_system_app cp .env.example .env
docker exec -it loyality_system_app composer dump-autoload
docker exec -it loyality_system_app php artisan key:generate
docker exec -it loyality_system_app php artisan migrate:fresh --seed
# docker exec -it loyality_system_app php artisan config:cache
# docker exec -it loyality_system_app php artisan route:cache
#### END Configuring (loyality_system_app) microservice ----------------------

end=`date +%s`
runtime=$((end-start))
echo "loyality system is successfully deployed in" $runtime "seconds"