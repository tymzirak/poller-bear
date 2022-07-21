# poller-bear

**1. Server Setup**

- Install [MySQL](https://www.mysql.com/) (or any other DBMS you prefer)

- Install [Nginx](https://nginx.org/) or [Apache](https://httpd.apache.org/):

> Set your web server root directory to **/public**

- Install [PHP](https://www.php.net/)

- Install [Composer](https://getcomposer.org/)

> Run ```php composer install``` in the project directory

---

**2. Database Setup**

- Change the values according to your database settings, in the **.env** (or **.env.local**) file.

> [More Info Here](https://symfony.com/doc/current/doctrine.html)

```
###> doctrine/doctrine-bundle ###
DATABASE_URL="mysql://YOUR_USERNAME:YOUR_PASSWORD@127.0.0.1:3306/poller_bear?&charset=utf8mb4"
###< doctrine/doctrine-bundle ###
```

> Run ```php bin/console doctrine:migrations:migrate``` in the project directory

---

**3. Email Setup**

- Change the values according to your email DSN settings, in the **.env** (or **.env.local**) file.

> [More Info Here](https://docs.hcaptcha.com/#verify-the-user-response-server-side)

```
###> symfony/mailer ###
MAILER_DSN=smtp://YOUR_USERNAME:YOUR_PASSWORD@smtp.example.com:2525/?encryption=ssl&auth_mode=login
###< symfony/mailer ###
```

---

**3. Captcha Setup**

- Change the value according to your hCaptcha secret key, in the **.env** (or **.env.local**) file.

> [More Info Here](https://symfony.com/doc/current/mailer.html)

```
CAPTCHA_SECRET=YOUR_HCAPTCHA_SECRET_KEY
```
