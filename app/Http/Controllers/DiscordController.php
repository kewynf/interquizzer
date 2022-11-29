<?php

namespace App\Http\Controllers;

use App\Models\Exam\Exam;
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
            env('DISCORD_API_URL', self::DISCORD_DEFAULT_API_URL) . $uri,
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
            env('DISCORD_API_URL', self::DISCORD_DEFAULT_API_URL) . $uri,
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
            env('DISCORD_API_URL', self::DISCORD_DEFAULT_API_URL) . $uri,
            $content
        );

        return $response->json();
    }

    public static function getRoleIdFromGuild(string $guild_id, string $role_name)
    {
        $roles = self::getFromApi("/guilds/$guild_id/roles");

        foreach ($roles as $role) {
            if ($role['name'] === $role_name) {
                return $role['id'];
            }
        }

        return null;
    }



    /**
     * Sends a Message to a Discord Text channel
     * 
     * @param string $message
     * @param string $channel_id
     * 
     * @return string $message_id
     */
    public static function sendMessage(string $channel_id, array $message)
    {
    }

    public static function createExamChannels(Exam $exam, bool $refresh = false)
    {
        if ($refresh) {
            self::deleteExamChannels($exam);
            $exam->refresh();
        }

        if ($exam->discord_voice_channel_id !== null && $exam->discord_text_channel_id !== null) {
            return;
        }

        $everyone_role_id = self::getRoleIdFromGuild(env('DISCORD_GUILD_ID'), '@everyone');

        if (is_null($everyone_role_id)) {
            abort(500, 'Could not find @everyone role');
        }

        $voiceChannel = [
            'name' => "Exam " . $exam->id,
            'type' => 2,
            'topic' => "Exam Channel",
            'parent_id' => env('DISCORD_CATEGORY_ID'),
            'permission_overwrites' => self::getOverwritesForExamVoiceChannel($exam, $everyone_role_id),
        ];

        $textChannel = [
            'name' => "Exam " . $exam->id,
            'type' => 0,
            'topic' => "Exam Channel",
            'parent_id' => env('DISCORD_CATEGORY_ID'),
            'permission_overwrites' => self::getOverwritesForExamTextChannel($exam, $everyone_role_id),
        ];

        $voiceChannel = self::postToApi('/guilds/' . env('DISCORD_GUILD_ID') . '/channels', $voiceChannel);
        $textChannel = self::postToApi('/guilds/' . env('DISCORD_GUILD_ID') . '/channels', $textChannel);

        $exam->discord_voice_channel_id = $voiceChannel['id'];
        $exam->discord_text_channel_id = $textChannel['id'];

        $exam->save();
    }

    public static function deleteExamChannels(Exam $exam)
    {
        self::deleteChannel($exam->discord_voice_channel_id);
        self::deleteChannel($exam->discord_text_channel_id);

        $exam->discord_voice_channel_id = null;
        $exam->discord_text_channel_id = null;

        $exam->save();
    }

    public static function getOverwritesForExamTextChannel(Exam $exam, string $everyone_role_id)
    {
        $overwrites = [];

        $overwrites[] = [
            'id' => $everyone_role_id,
            'type' => 0,
            'allow' => 0,
            'deny' => 1049600,
        ];

        $overwrites[] = [
            'id' => $exam->candidate->discord_id,
            'type' => 1,
            'allow' => 3072,
            'deny' => 0,
        ];

        return $overwrites;
    }


    public static function getOverwritesForExamVoiceChannel(Exam $exam, string $everyone_role_id)
    {
        $overwrites = [];

        $overwrites[] = [
            'id' => $everyone_role_id,
            'type' => 0,
            'allow' => 0,
            'deny' => 1049600,
        ];

        $overwrites[] = [
            'id' => $exam->candidate->discord_id,
            'type' => 1,
            'allow' => 36701184,
            'deny' => 0,
        ];

        return $overwrites;
    }

    public static function deleteChannel(string $channel_id)
    {
        return self::deleteFromApi('/channels/' . $channel_id);
    }
}
