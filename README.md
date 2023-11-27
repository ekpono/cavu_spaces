# Quick Start - Cavu Spaces
### Follow these steps to set up and run the Parking and Booking API in a Laravel environment:


### Step by step
Clone this Repository
```sh
git clone https://github.com/ekpono/cavu_spaces.git
```

Create the .env file
```sh
cd cavu_spaces/
cp .env.example .env
```

Setup `.env` database connection variables

Install project dependencies
```sh
composer install
```

Migrate and seed the database
```sh
php artisan migrate --seed
```

Generate the Laravel project key
```sh
php artisan key:generate
```



Run test. Create a database called `cavu_spaces_test` and run the following command
```sh
php artisan test
```


Start application server
```sh
php artisan serve
```



(The API is now accessible at http://127.0.0.1:8000.)

### Queue
To run the queue, run the following command
```sh
php artisan queue:work
```

### Additional Information
Review the API documentation for detailed information on each endpoint.

Access the project API documentation
[https://documenter.getpostman.com/view/4508256/2s9YeEcXuH](https://documenter.getpostman.com/view/4508256/2s9YeEcXuH)
