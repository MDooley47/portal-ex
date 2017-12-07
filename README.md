# portal-ex

## Volumes
Create a volume. For this README it will be called called `portal-ex-data`.
```
docker volume create portal-ex-data
```

## Developing
Use `docker-compose up --build --force-recreate --abort-on-container-exit` when developing this application.
Use `docker-compose up --build --force-recreate` when developing this application with database migrations.


## Deploying
Use the following commands to deploy this container.
```
docker build -t portal-ex .
docker run -d --mount source=portal-ex-data,target=/volumes/storage/ --name portal-ex -p 8080:80 portal-ex
```
