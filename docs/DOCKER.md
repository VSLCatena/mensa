## **Docker**

- With the exeption of the database root password every parameter / environment variable is retrieved using .env
- Change .env and docker-compose to specific values
#### **Build image yourself**
- Check your current user id using `id -u` , should be the owner of the files of this repo
- Set `WWW_UID` argument in docker-compose.yml to that specific value.
- Execute `docker-compose up --build --force-recreate --detach` to build and serve the app

#### **Use image from repository**
- Login to GitHub and save token `export CR_PAT=YOUR_TOKEN; echo $CR_PAT | docker login ghcr.io -u USERNAME --password-stdin`
- Execute `docker-compose up --detach` to serve the app

#### **Clear the docker environment to default**
> **Note:** Some directories and files in the repo may have been changed like `vendor`, `storage` and `composer.lock`
- Execute `docker-compose down --volumes`

#### **Debugging**
- Read logs using `docker-compose logs --tail="50" [optional: container_name]`
- Execute `artisan` or `compose` commands use `docker exec -it app /data/entrypoint.sh COMMANDS HERE`
- To use `phpMyAdmin` add `--profile testing` before the `up` argument.


<a href="https://mermaid-js.github.io/mermaid-live-editor/edit/##eyJjb2RlIjoiXG5mbG93Y2hhcnQgVERcbnN1YmdyYXBoIERvY2tlciBFbnZpcm9ubWVudFxuREMoKGRvY2tlci1jb21wb3NlLnltbCkpXG5BUFB7YXBwfVxuc3ViZ3JhcGggT3B0aW9uYWxcblB7UGhwTXlBZG1pbn1cbmVuZFxuREJbKGRiKV1cbkRJKFtEb2NrZXJmaWxlXSlcbmVuZFxuQlJbW1dlYiBJbnRlcmZhY2VdXVxuUltNZW5zYSBSZXBvc2l0b3J5XVxuREIgPD09PiBBUFBcblAgPT1WaWV3L0FsdGVyPT0-IERCXG5EQyAtLi0gUlxuREMgLS5idWlsZCBpbWFnZS4tPiBESVxuREkgLS5zdGFydCBjb250YWluZXIuLT4gQVBQXG5EQyAtLnN0YXJ0IGNvbnRhaW5lci4tPiBEQlxuQVBQIDw9PW1vdW50OnJ3ID09PiBSXG5CUiA9PSBwb3J0IDgwODEgPT0-IFBcbkRDIC0uc3RhcnQgY29udGFpbmVyLi0-IFBcbkJSID09cG9ydCAxMjM0PT0-IEFQUFxuXG4iLCJtZXJtYWlkIjoie1xuICBcInRoZW1lXCI6IFwiZGFyXCIsXG4gIFwidGhlbWVWYXJpYWJsZXNcIjogeyBcbiAgICAgIFwiZGFya21vZGVcIjpcInRydWVcIlxuICAgICAgfVxufSIsInVwZGF0ZUVkaXRvciI6ZmFsc2UsImF1dG9TeW5jIjp0cnVlLCJ1cGRhdGVEaWFncmFtIjpmYWxzZX0">
<img src="https://mermaid.ink/img/eyJjb2RlIjoiXG5mbG93Y2hhcnQgVERcbnN1YmdyYXBoIERvY2tlciBFbnZpcm9ubWVudFxuREMoKGRvY2tlci1jb21wb3NlLnltbCkpXG5BUFB7YXBwfVxuc3ViZ3JhcGggT3B0aW9uYWxcblB7UGhwTXlBZG1pbn1cbmVuZFxuREJbKGRiKV1cbkRJKFtEb2NrZXJmaWxlXSlcbmVuZFxuQlJbW1dlYiBJbnRlcmZhY2VdXVxuUltNZW5zYSBSZXBvc2l0b3J5XVxuREIgPD09PiBBUFBcblAgPT1WaWV3L0FsdGVyPT0-IERCXG5EQyAtLi0gUlxuREMgLS5idWlsZCBpbWFnZS4tPiBESVxuREkgLS5zdGFydCBjb250YWluZXIuLT4gQVBQXG5EQyAtLnN0YXJ0IGNvbnRhaW5lci4tPiBEQlxuQVBQIDw9PW1vdW50OnJ3ID09PiBSXG5CUiA9PSBwb3J0IDgwODEgPT0-IFBcbkRDIC0uc3RhcnQgY29udGFpbmVyLi0-IFBcbkJSID09cG9ydCAxMjM0PT0-IEFQUFxuXG4iLCJtZXJtYWlkIjp7InRoZW1lIjoiZGFyayIsInRoZW1lVmFyaWFibGVzIjp7ImRhcmttb2RlIjoidHJ1ZSJ9fSwidXBkYXRlRWRpdG9yIjpmYWxzZSwiYXV0b1N5bmMiOnRydWUsInVwZGF0ZURpYWdyYW0iOmZhbHNlfQ" alt="
<!--
https://mermaid-js.github.io/mermaid-live-editor/edit
flowchart TD
subgraph Docker Environment
DC((docker-compose.yml))
APP{app}
subgraph Optional
P{PhpMyAdmin}
end
DB[(db)]
DI([Dockerfile])
end
BR[[Web Interface]]
R[Mensa Repository]
DB <==> APP
P ==View/Alter==> DB
DC -.- R
DC -.build image.-> DI
DI -.start container.-> APP
DC -.start container.-> DB
APP <==mount:rw ==> R
BR == port 8081 ==> P
DC -.start container.-> P
BR ==port 1234==> APP
-->
" width="" height="700">
</a>
