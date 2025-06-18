<?php

declare(strict_types=1);

namespace app\controllers;

use app\core\Application;
use app\core\Request;
use app\core\Response;
use app\core\Logger;
use app\database\dao\CategoryDAO;
use app\enums\HttpStatusCodeEnum;
use Throwable;

class CategoryController
{
    private CategoryDAO $categoryDAO;
    private Logger $logger;

    public function __construct()
    {
        $this->categoryDAO = new CategoryDAO();
        $this->logger = Application::$app->getLogger();
    }

    public function getCategoryById(Request $request, Response $response, array $params): void
    {
        $id = (int)($params['id'] ?? 0);
        $this->logger->info("Запрос категории по ID", ['category_id' => $id]);

        try {
            $category = $this->categoryDAO->get($id);

            if ($category) {
                $response->json($category);
            } else {
                $this->logger->warning("Категория не найдена", ['category_id' => $id]);
                $response->setStatusCode(HttpStatusCodeEnum::HTTP_NOT_FOUND);
                $response->json(['error' => 'Категория не найдена']);
            }
        } catch (Throwable $e) {
            $this->logger->error("Ошибка при получении категории", [
                'exception' => get_class($e),
                'message'   => $e->getMessage()
            ]);

            $response->setStatusCode(HttpStatusCodeEnum::HTTP_SERVER_ERROR);
            $response->json(['error' => 'Internal Server Error']);
        }
    }
}