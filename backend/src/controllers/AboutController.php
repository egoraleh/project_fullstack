<?php

declare(strict_types=1);

namespace app\controllers;

use app\core\Application;
use app\core\Logger;
use app\core\Request;
use app\core\Response;
use app\core\Template;

class AboutController
{
    private Logger $logger;
    public function __construct()
    {
        $this->logger = Application::$app->getLogger();
    }
    public function getView(Request $request, Response $response): void
    {
        $this->logger->debug('About page accessed');

        $data = [
            'title'       => 'О нашем сервисе',
            'description' => '<p>
                Добро пожаловать на онлайн-доску объявлений! Здесь вы можете быстро и удобно покупать и продавать товары, а также предлагать и находить услуги.
            </p>
            <p>
                Наш сайт позволяет каждому пользователю размещать свои объявления в разных категориях: от недвижимости и автомобилей до электроники и бытовых услуг.
            </p>
            <p>
                Благодаря удобному поиску и фильтрам вы легко найдёте именно то, что вам нужно. А если хотите продать или предложить что-то своё — просто зарегистрируйтесь и разместите объявление, это займёт всего пару минут!
            </p>
            <p>
                Мы стремимся создать безопасную и удобную площадку для общения между продавцами и покупателями. Добавляйте товары в избранное, связывайтесь с авторами объявлений и находите выгодные предложения поблизости.
            </p>'
        ];

        ob_start();
        Template::View('about.html', $data);
        $html = ob_get_clean();

        $response->setStatusCode(\app\enums\HttpStatusCodeEnum::HTTP_OK);
        header('Content-Type: text/html; charset=utf-8');
        echo $html;
        $this->logger->debug('About page response sent');
    }
}
