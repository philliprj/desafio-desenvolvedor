

# Desafio Oliveira Trust

### Tecnologias utilizadas
- PHP 8
- Docker
- MySql 8
- Nginx

### Iniciar o projeto

Após clonar o projeto, executar os comandos na raiz:

- Criar o arquivo .env na raiz do projeto e copiar o conteúdo .env.example

- Para iniciar os containers: 
``
docker composer up -d
``
- Para executar o composer, migrations e supervisor
``
docker exec ot_app "./commands.sh"
``

obs: supervisor ficará executando no terminal

### Guia da API 

#### Upload de arquivo:

``
curl --request POST \
  --url http://localhost:4001/api/upload \
  --header 'Accept: application/json' \
  --header 'content-type: multipart/form-data; \
  --form file=@PATH_DO_ARQUIVO
``

- Respostas:

status 200
``
{
	"message": "Upload recebido. O processamento ocorrerá em breve.",
	"history_id": 1
}
``

#### Verificar processamento do arquivo:

Status: QUEUED / PROCESSING / FINISHED

``
curl --request GET \
  --url http://localhost:4001/api/upload/1/status \
  --header 'Accept: application/json' 
``

status 200
``
{
	"id": 1,
	"status": "QUEUED"
}
``

#### Buscar histórico

``
curl --request GET \
  --url 'http://localhost:4001/api/upload-history?reference_date=2025-01-31&page=1&per_page=10' \
  --header 'Accept: application/json'
``

#### Buscar dados

``
curl --request GET \
  --url 'http://localhost:4001/api/uploads?TckrSymb=AMZO34&RptDt=2024-08-26&page=1&per_page=20' \
  --header 'Accept: application/json'
``

Thiago Phillip Barbosa dos Santos
thiagophrj@gmail.com


