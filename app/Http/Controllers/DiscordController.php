<?php

namespace App\Http\Controllers;

use App\Models\Exam\Exam;
use App\Models\Exam\ExamStepAbility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DiscordController extends Controller
{
    const DISCORD_DEFAULT_API_URL = 'https://discord.com/api';

    public static function getFromApi(string $uri, array $query = [])
    {
        $response = Http::withToken(
            env('DISCORD_BOT_TOKEN'),
            'Bot'
        )->get(
            env('DISCORD_API_URL', DiscordController::DISCORD_DEFAULT_API_URL) . $uri,
            $query
        );

        return $response->json();
    }
    public static function postToApi(string $uri, array $content = [])
    {
        $response = Http::withToken(
            env('DISCORD_BOT_TOKEN'),
            'Bot'
        )->post(
            env('DISCORD_API_URL', DiscordController::DISCORD_DEFAULT_API_URL) . $uri,
            $content
        );

        return $response->json();
    }

    public static function deleteFromApi(string $uri, array $content = [])
    {
        $response = Http::withToken(
            env('DISCORD_BOT_TOKEN'),
            'Bot'
        )->delete(
            env('DISCORD_API_URL', DiscordController::DISCORD_DEFAULT_API_URL) . $uri,
            $content
        );

        return $response->json();
    }

    public static function getRoleIdFromGuild(string $guild_id, string $role_name)
    {
        $roles = DiscordController::getFromApi("/guilds/$guild_id/roles");

        foreach ($roles as $role) {
            if ($role['name'] === $role_name) {
                return $role['id'];
            }
        }

        return null;
    }

    public static function deleteChannel(string $channel_id)
    {
        return DiscordController::deleteFromApi('/channels/' . $channel_id);
    }
}
