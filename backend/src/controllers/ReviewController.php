<?php

namespace app\controllers;

use app\core\Application;
use app\core\Logger;
use app\core\Request;
use app\core\Response;
use app\enums\HttpStatusCodeEnum;
use app\exceptions\ValidationException;
use app\services\AuthenticationService;
use app\services\ReviewService;
use Throwable;

class ReviewController
{
    private ReviewService $reviewService;
    private AuthenticationService $authenticationService;
    private Logger $logger;
    public function __construct() {
        $this->reviewService = new ReviewService();
        $this->authenticationService = new AuthenticationService();
        $this->logger = Application::$app->getLogger();
    }

    public function getReviewsByAdId(Request $request, Response $response, array $params): void
    {
        $adId = $params['id'];

        try {
            $reviews = $this->reviewService->getReviewsByAdId($adId);
            $response->json($reviews);
        } catch (Throwable $e) {
            $this->logger->error("Ошибка при получении отзывов", [
                'exception' => get_class($e),
                'message'   => $e->getMessage()
            ]);
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_SERVER_ERROR);
            $response->json(['error' => 'Internal Server Error']);
        }
    }

    public function createReview(Request $request, Response $response, array $params): void
    {
        $currentUser = $this->authenticationService->getCurrentUser();

        if (!$currentUser) {
            $this->logger->warning("Попытка добавить отзыв без авторизации");
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_UNAUTHORIZED);
            $response->json(['error' => 'Вы не авторизованы']);
            return;
        }

        $adId = (int)($params['id'] ?? 0);
        if ($adId === 0) {
            $this->logger->warning("Попытка добавить отзыв без указания id объявления");
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_BAD_REQUEST);
            $response->json(['error' => 'Не указан id объявления']);
            return;
        }

        $data = $request->getBody();

        $data['author_id'] = $currentUser->getId();
        $data['ad_id']     = $adId;

        $this->logger->info("Попытка добавления отзыва", [
            'ad_id'   => $adId,
            'user_id' => $currentUser->getId(),
            'rating'  => $data['rating'] ?? null,
        ]);

        try {
            $this->reviewService->createReview($data);
            $this->logger->info("Отзыв успешно добавлен", [
                'ad_id'   => $adId,
                'user_id' => $currentUser->getId(),
                'rating'  => $data['rating']
            ]);

            $response->setStatusCode(HttpStatusCodeEnum::HTTP_CREATED);
            $response->json(['message' => 'Отзыв успешно добавлен']);
        } catch (ValidationException $e) {
            $this->logger->warning("Ошибка валидации при добавлении отзыва", [
                'error' => $e->getMessage()
            ]);
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_BAD_REQUEST);
            $response->json(['error' => $e->getMessage()]);
        } catch (Throwable $e) {
            $this->logger->error("Ошибка при добавлении отзыва", [
                'exception' => get_class($e),
                'message'   => $e->getMessage()
            ]);
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_SERVER_ERROR);
            $response->json(['error' => 'Internal Server Error']);
        }
    }

    public function deleteReview(Request $request, Response $response, array $params): void
    {
        $currentUser = $this->authenticationService->getCurrentUser();

        if (!$currentUser) {
            $this->logger->warning("Попытка удалить отзыв без авторизации");
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_UNAUTHORIZED);
            $response->json(['error' => 'Вы не авторизованы']);
            return;
        }

        $reviewId = (int)($params['id'] ?? 0);
        if ($reviewId === 0) {
            $this->logger->warning("Попытка удалить отзыв без указания id");
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_BAD_REQUEST);
            $response->json(['error' => 'Не указан id отзыва']);
            return;
        }

        try {
            $data = $this->reviewService->getReviewById($reviewId);
            if (empty($data)) {
                $response->setStatusCode(HttpStatusCodeEnum::HTTP_NOT_FOUND);
                $response->json(['error' => 'Отзыв не найден']);
                return;
            }

            if ($data['author_id'] !== $currentUser->getId()) {
                $this->logger->warning("Попытка удалить чужой отзыв", [
                    'user_id'   => $currentUser->getId(),
                    'review_id' => $reviewId
                ]);
                $response->setStatusCode(HttpStatusCodeEnum::HTTP_FORBIDDEN);
                $response->json(['error' => 'Вы не можете удалить этот отзыв']);
                return;
            }

            $this->reviewService->deleteReviewById($reviewId);
            $this->logger->info("Отзыв успешно удалён", [
                'user_id'   => $currentUser->getId(),
                'review_id' => $reviewId
            ]);

            $response->json(['message' => 'Отзыв успешно удалён']);
        } catch (Throwable $e) {
            $this->logger->error("Ошибка при удалении отзыва", [
                'exception' => get_class($e),
                'message'   => $e->getMessage()
            ]);
            $response->setStatusCode(HttpStatusCodeEnum::HTTP_SERVER_ERROR);
            $response->json(['error' => 'Internal Server Error']);
        }
    }
}