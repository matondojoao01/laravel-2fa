# Authentication Package with 2FA

**Matondo TwoFactorAuth** is a package developed to add two-factor authentication (2FA) to Laravel applications. This package offers a comprehensive authentication process, including login, password reset, two-factor authentication, and other essential features. It provides an easy-to-use interface for enabling 2FA, enhancing security without complicating the authentication flow.

The authentication code is sent via email and SMS, with internationalization support for Portuguese and English. The default language of the application is automatically set based on the configuration in config/app.php (locale). By default, the package comes with Bootstrap-based views for the authentication interface, offering a clean and modern design. However, the package is highly customizable, making it easy to modify the visuals if you need to adjust the design to match your application's theme or branding. This package stands out compared to other popular packages like Laravel Breeze and Bootstrap by providing two-factor authentication, adding an extra layer of security to application access.

# Installation

To install the package, use Composer:

```bash
composer require matondo/twofactorauth
```

# Service Provider Registration

After installation, register the TwoFactorAuthServiceProvider in the config/app.php file under the providers section:

```php
'providers' => [
    // Other providers
    Matondo\TwoFactorServiceProvider::class,
],
```

# Publishing Package Files

Next, publish the package's files with the following command:

```bash
php artisan vendor:publish --provider="Matondo\TwoFactorServiceProvider"
```

**Models:** Models required for two-factor authentication.
**Controllers:** Controllers to manage authentication logic.
**Middleware:** Middleware to protect routes requiring 2FA.
**Views:** Bootstrap-based views necessary for the authentication process.
**Routes:** A route file containing new routes for two-factor authentication.

The default authentication views can be easily edited if you want to use a different CSS framework or modify the layout. Simply update the published view files to match your design preferences.

# Including Routes

Add the following to your routes/web.php file to include the newly published routes:

```php
require base_path('routes/twofactorauth.php');
```

# Running Migrations

Before running the migrations, ensure that your environment variables are set up correctly in the .env file to avoid database errors. This includes configuring the database connection settings.

Also, configure email settings to ensure that emails can be sent during authentication processes, such as during password resets or 2FA code verification. These settings include:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
```
Once configured, run the following command to migrate the necessary tables:

```bash
php artisan migrate
```

# Environment Setup for Redis and Queue

To ensure proper functioning of email and message queuing, configure the following Redis settings in your .env file:

```env
REDIS_CLIENT=predis
BROADCAST_DRIVER=log
CACHE_DRIVER=redis
FILESYSTEM_DISK=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
SESSION_LIFETIME=120
```

These settings enable Redis to manage queues, sessions, and cache, improving the performance of the 2FA process.

# Twilio SMS Configuration

To send SMS-based authentication codes, configure Twilio in your .env file with the following example credentials (replace with your own Twilio credentials from the Twilio console):

```env
TWILIO_SID=your_twilio_sid
TWILIO_TOKEN=your_twilio_token
TWILIO_FROM=your_twilio_phone_number
```

To obtain the Account SID, Auth Token, and Twilio phone number, visit the Twilio Console: Go to https://console.twilio.com/.

# Middleware Registration

Register the TwoFactorVerify middleware in the app/Http/Kernel.php file under the $routeMiddleware array:


```php
protected $routeMiddleware = [
    // Other middlewares
    '2fa' => \App\Http\Middleware\TwoFactorVerify::class,
];
```

# Using Middleware for Protected Routes

Apply the 2fa middleware to routes that require two-factor authentication:

```php
Route::group(['middleware' => '2fa'], function () {
    Route::get('/home', function () {
        // Dashboard content
    });
});
```

# Internationalization Support

The package supports internationalization (i18n), allowing you to set the locale to either English or Brazilian Portuguese. To change the locale, adjust the app.locale setting in the config/app.php file:

```php
'locale' => 'pt_BR', // Default: en
```

# Important: Running Queues

To ensure that emails and messages are processed efficiently, you should run the command:

```bash
php artisan queue:work
```
If you're in a testing environment, make sure your server is configured to run queues so that notifications are sent promptly.


# Accessing the Login

Once all steps are completed, you can access the /login route to initiate the authentication process. After initial login with credentials, the system sends a 2FA code via SMS, which the user must enter to complete the login process, providing an extra layer of security.

# Conclusion

Matondo TwoFactorAuth simplifies the process of adding a full authentication flow to your Laravel application, including features like login, password reset, two-factor authentication, and internationalization. The package also provides a Bootstrap-based UI for authentication that can be easily customized if necessary.

# License

This project is licensed under the MIT License. See the LICENSE file for more details.

# Get in Touch

LinkedIn: [matondojoao](https://www.linkedin.com/in/matondojoao) | WhatsApp: https://api.whatsapp.com/send?phone=244947224896&text=Hello%21+I+am+interested+in+collaborating+on+projects...
. Feel free to reach out for project collaborations, sharing ideas, or any opportunities to connect!