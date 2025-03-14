**TASK.md**: Description of the task
- **README.md**: Project documentation.
- **.env.example**:
  Example environment settings file to copy to `.env` to configure the project.

### Instructions for setting up the project
- php 8.1
- postgres 17.0
- Docker 
- make

1. Clone the repository to your local machine:
```bash
git clone https://github.com/andrewspacecore/symfony_test_task_1_crypto_api.git
```
 ```bash
git clone git@github.com:andrewspacecore/symfony_test_task_1_crypto_api.git
```
2. Create an .env file based on .env.dist and configure the environment (eg configure the database).
 ```bash
cp .env.dist .env
 ```
3. Installation of project dependencies
 ```bash
  composer install
 ```
4. Create docker file
 ```bash
  cp docker-compose.dev.dist.yml docker-compose.dev.yml
 ```
5. Run docker file-dev
 ```bash
  make build-dev
 ```
```bash
  docker compose -f docker-compose.dev.yml up --force-recreate -d --build
 ```
6. After configuring the env file and starting docker, you need to run migrations and fixtures, but don't forget to run them from the container.
 ```bash
  make bash or docker exec -it app_dev bash
 ```
```bash
  make migrate-run or bin/console doctrine:migrations:migrate
 ```
```bash
  make fixture-run or bin/console doctrine:fixtures:load
 ```
7. We will also run the sh command to load the price
 ```bash
  make command-crypto-price or sh ./docker/dev/cron/check-crypto-price-command.sh
 ```

Send a GET request to /api/price/{cryptoCode}/{fiatCode}/{sort?} with the following JSON body:
- api/price/btc/usd/day
- cryptocode = BTC, ETH, LTC, DOGE, XRP
- fiatCode = USD, EUR, UAH, GBP, JPY
- sort = hour, twohour, day, week, month

```json
[
  {
    "price": 83696.42,
    "recordedAt": "2025-03-14 13:51:05"
  },
  {
    "price": 83647.76,
    "recordedAt": "2025-03-14 13:50:45"
  },
  {
    "price": 83503.15,
    "recordedAt": "2025-03-14 13:50:24"
  },
  {
    "price": 83446.05,
    "recordedAt": "2025-03-14 13:50:01"
  },
  {
    "price": 83386.94,
    "recordedAt": "2025-03-14 13:49:41"
  },
  {
    "price": 83304.88,
    "recordedAt": "2025-03-14 13:49:19"
  },
  {
    "price": 83298.7,
    "recordedAt": "2025-03-14 13:48:59"
  },
  {
    "price": 83419.42,
    "recordedAt": "2025-03-14 13:48:38"
  }
]
```
- /api/price/btc/usd
```json
{
  "price": 83824.56
}
```