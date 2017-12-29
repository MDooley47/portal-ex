# portal-ex

## Environmental Variables and Build Arguments
Configuration/environmental variables are stored in `.env`.
If there is no `.env` file available, we generate one from `.env.example`.
Build Arguments are used to provide a quick and easy environmental variable
change on build. To use a build argument, use the `--build-arg` flag like so.
```
docker build -t portal-ex --build-arg app_name="App Portal"
```
If you wish to add your own environmental variable as a build argument you must edit `Dockerfile`. Find the section labeled `Arguments for changing environmental variables` and add an argument on a new line.
```
ARG new_argument
```
Then, add the argument to the command in sub-section `Updates ./.env` of section `Run docker-scripts`.
```
RUN ./docker-scripts/update-env.sh new_argument "$new_argument"
```
The plain text `new_argument` is the key in `.env`. It is *very* important to place the value (`$new_argument`) in quotation marks. This allows the entering of strings with spaces.

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
