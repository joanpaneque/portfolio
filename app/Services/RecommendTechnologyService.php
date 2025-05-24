<?php

namespace App\Services;

use App\Models\RecommendedTechnology;

class RecommendTechnologyService
{
    public static function recommendTechnology($technology, $sessionId)
    {
        $canRecommend = self::canRecommendTechnology($technology, $sessionId);

        if ($canRecommend["success"]) {
            $recommendedTechnology = RecommendedTechnology::create([
                'technology' => $technology,
                'session_id' => $sessionId,
                'is_undone' => false,
            ]);

            return [
                "success" => true,
                "status" => $canRecommend["status"],
                "recommendedTechnology" => $recommendedTechnology,
                "timeRemaining" => 0
            ];
        }

        return [
            "success" => false,
            "status" => $canRecommend["status"],
            "recommendedTechnology" => self::getLastRecommendedTechnology($sessionId),
            "timeRemaining" => $canRecommend["timeRemaining"] ?? 0
        ];
    }

    public static function getLastRecommendedTechnology($sessionId)
    {
        return RecommendedTechnology::query()
            ->where('session_id', $sessionId)
            ->where('is_undone', false)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    public static function canRecommendTechnology($technology = null, $sessionId)
    {
        $lastRecommendedTechnology = self::getLastRecommendedTechnology($sessionId);

        if (!$lastRecommendedTechnology) {
            return [
                "success" => true,
                "status" => "CAN_RECOMMEND",
                "timeRemaining" => 0
            ];
        }

        $minutesPassed = $lastRecommendedTechnology->created_at->diffInMinutes(now());
        $timeRemaining = max(0, 5 - $minutesPassed);

        if ($minutesPassed >= 5) {
            return [
                "success" => true,
                "status" => "CAN_RECOMMEND",
                "timeRemaining" => 0
            ];
        }

        if ($lastRecommendedTechnology->technology == $technology) {
            return [
                "success" => false,
                "status" => "SAME_TECHNOLOGY",
                "timeRemaining" => $timeRemaining
            ];
        }

        return [
            "success" => false,
            "status" => "FREQUENCY_LIMIT",
            "timeRemaining" => $timeRemaining
        ];
    }

    public static function getTimeRemaining($sessionId)
    {
        $lastRecommendedTechnology = self::getLastRecommendedTechnology($sessionId);

        if (!$lastRecommendedTechnology) {
            return 0;
        }

        $minutesPassed = $lastRecommendedTechnology->created_at->diffInMinutes(now());
        return max(0, 5 - $minutesPassed);
    }

    public static function undoLastRecommendation($sessionId)
    {
        $lastRecommendedTechnology = self::getLastRecommendedTechnology($sessionId);

        if (!$lastRecommendedTechnology) {
            return [
                "success" => false,
                "message" => "No hay recomendación para deshacer"
            ];
        }

        // Marcar como deshecha
        $lastRecommendedTechnology->update(['is_undone' => true]);

        return [
            "success" => true,
            "message" => "Recomendación deshecha exitosamente"
        ];
    }
}