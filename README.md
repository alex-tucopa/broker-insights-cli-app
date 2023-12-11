# broker-insights-cli-app
Broker Insights Demo 

## Setup

`git clone https://github.com/alex-tucopa/broker-insights-cli-app.git`

`cd ./broker-insights-cli-app`

`docker-compose up -d`

`docker exec -it broker-insights-cli-app bash`

### Tests

`XDEBUG_MODE=coverage ./vendor/bin/pest --coverage`

### Importing Data

The Dockerfile creates the database and inserts some initial data, setting up 2 brokers with names:

- Broker One (id 1)
- Broker Two (id 2)

The 2 sample CSV files are in the `sample_data` directory. The import command takes 3 arguments:

- broker id
- filename
- format

The formats are stored in a config file - `config/broker_csv_data_map.php` - and there is one format for each broker:

- Broker One - `format_1`
- Broker Two - `format_2`

To import run:

`php command app:import-broker-policy-data 1 sample_data/broker1.csv format_1`

and 

`php command app:import-broker-policy-data 2 sample_data/broker2.csv format_2`

### Reports

Running the reports command without options shows the summary data for all brokers:

`php command app:policy-report`

To show the summary for a broker along with a list of policies you can filter by broker id:

`php command app:policy-report --broker-id=1`

or broker name:

`php command app:policy-report --broker-name="Broker Two"`