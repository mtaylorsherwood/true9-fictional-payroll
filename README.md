# Fictional Payroll System for True9 Technical Test

This codebase covers the feature requirement of the True9 technical test, where it is to calculate the date payment should be made by the employer, to ensure it reaches the employee bank account by
the second-to-last working day of the month.

## Running this Code Locally

This app has a lightweight Docker Compose setup to enable local development and testing.

To get up and running, use the following command, in the root of the project:

```shell
docker compose up -d --build
```

This will pull down the Alpine image and install the necessary dependencies to run the app. There is no database layer, as this was not required for the test. Laravel will create an SQLite database for 
you to handle some of its framework database requirements.

You can then access the app at [http://localhost:8001](http://localhost:8001) which will allow you to test the feature via the frontend.

You can also use the API endpoint at [http://localhost:8001/api/calculatePaymentDate](http://localhost:8001/api/calculatePaymentDate)

I have included a Postman collection and a PHPStorm HTTP client file (True9.http) which you can use to test the API endpoint.

## Testing

To run the local test suite, you can use the Artisan command within the container:

```shell
docker compose exec app php artisan test
```
