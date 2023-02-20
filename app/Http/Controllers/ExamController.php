<?php

namespace App\Http\Controllers;

use App\Models\Exam\Candidate;
use App\Models\Exam\Exam;
use App\Models\Exam\ExamStep;
use App\Models\Exam\ExamStepAbility;
use App\Models\ExamTemplate\ExamTemplate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Gate;

class ExamController extends Controller
{

    public function create()
    {
        $exam_templates = ExamTemplate::orderBy('title')->get();
        $candidates = DiscordController::getGuildMembers(env('DISCORD_GUILD_ID'));

        return view('exam.create', [
            'exam_templates' => $exam_templates,
            'candidates' => $candidates,
        ]);
    }

    public function renderExam(int $exam_id)
    {
        $exam = Exam::findOrFail($exam_id);

        Gate::authorize("exam.examiner", $exam);

        return view('exam.exam', [
            'exam' => $exam,
        ]);
    }

    public function createDiscordChannel(int $exam_id)
    {
        $exam = Exam::findOrFail($exam_id);

        Gate::authorize("exam.examiner", $exam);

        ExamController::createExamDiscordChannels($exam);

        return redirect()->back();
    }

    public function start(int $exam_id)
    {
        $exam = Exam::findOrFail($exam_id);

        Gate::authorize("exam.examiner", $exam);

        if (is_null($exam->started_at)) {
            $exam->started_at = now();
            $exam->save();
        }


        return redirect()->route('exam.during', ['exam' => $exam->id, 'step' => $exam->steps->first()->id]);
    }

    public function during(int $exam_id, int $step_id)
    {
        $exam = Exam::findOrFail($exam_id);

        Gate::authorize("exam.examiner", $exam);

        $step = ExamStep::findOrFail($step_id);

        if ($step->exam->id !== $exam->id) {
            abort(403);
        }

        return view('exam.during', [
            'exam' => $exam,
            'currentStep' => $step,
        ]);
    }

    public function previousStep(int $exam_id, int $step_id)
    {
        $exam = Exam::findOrFail($exam_id);

        Gate::authorize("exam.examiner", $exam);

        $currentStep = ExamStep::findOrFail($step_id);

        if ($currentStep->exam->id !== $exam->id) {
            abort(403);
        }

        $flag = false;

        foreach ($exam->steps->sortByDesc('id') as $step) {
            if ($flag) {
                return redirect()->route('exam.during', ['exam' => $exam->id, 'step' => $step->id]);
            }

            if ($step->id === $currentStep->id) {
                $flag = true;
            }
        }

        return redirect()->route('exam.during', ['exam' => $exam->id, 'step' => $currentStep->id]);
    }

    public function nextStep(int $exam_id, int $step_id)
    {
        $exam = Exam::findOrFail($exam_id);

        Gate::authorize("exam.examiner", $exam);

        $currentStep = ExamStep::findOrFail($step_id);

        if ($currentStep->exam->id !== $exam->id) {
            abort(403);
        }

        $flag = false;

        foreach ($exam->steps as $step) {
            if ($flag) {
                return redirect()->route('exam.during', ['exam' => $exam->id, 'step' => $step->id]);
            }

            if ($step->id === $currentStep->id) {
                $flag = true;
            }
        }

        return redirect()->route('exam.during', ['exam' => $exam->id, 'step' => $currentStep->id]);
    }

    public function end(int $exam_id)
    {
        $exam = Exam::findOrFail($exam_id);

        Gate::authorize("exam.examiner", $exam);

        if (is_null($exam->ended_at)) {
            $exam->ended_at = now();
            $exam->save();
        }

        return view('exam.end', [
            'exam' => $exam,
        ]);
    }

    public function generate(Request $request)
    {
        $request->validate([
            'exam_template_id' => 'required|exists:exam_templates,id',
            'candidate_id' => 'required',
        ]);

        $candidate = User::where('discord_id', $request->input('candidate_id'))->first();

        if (!$candidate) {
            $discordUser = DiscordController::getGuildMembers(env('DISCORD_GUILD_ID'));

            $user = [];

            foreach ($discordUser as $user) {
                if ($user['user']['id'] == $request->input('candidate_id')) {
                    $user = $user;
                }
            }

            if (!$user) {
                abort(404);
            }

            $candidate = User::create([
                'name' => $user['nick'] ?? $user['user']['username'],
                'email' => $user['user']['id'] . '@discord.com',
                'password' => 0,
                'discord_id' => $user['user']['id'],
            ]);
        }

        $exam_template = ExamTemplate::findOrFail($request->input('exam_template_id'));

        $exam = Exam::create([
            'title' => $exam_template->title,
            'description' => $exam_template->description,
            'icon' => $exam_template->icon,
        ]);

        $exam->users()->attach([$candidate->id => ['role' => 'candidate']]);
        $exam->users()->attach([$request->user()->id => ['role' => 'examiner']]);

        foreach ($exam_template->steps as $step) {
            $exam_step = ExamStep::create([
                'exam_id' => $exam->id,
                'title' => $step->title,
                'description' => $step->description,
                'icon' => $step->icon,
            ]);

            foreach ($step->abilities as $ability) {


                $newAbility = ExamStepAbility::create([
                    'exam_step_id' => $exam_step->id,
                    'title' => $ability->title,
                    'description' => $ability->description,
                ]);

                if ($ability->collection) {

                    $content = $ability->collection->contents->random();

                    while ($exam->abilities->contains('content_title', $content->title)) {
                        $content = $ability->collection->contents->random();
                    }

                    $newAbility->content_title = $content->title;
                    $newAbility->content_description = $content->description;
                    $newAbility->content_type = $content->type;
                    $newAbility->content_path = $content->path;

                    $newAbility->save();
                }
            }
        }

        return redirect()->route('exam.render', ['exam' => $exam->id]);
    }

    public function addObserver(Request $request)
    {



        $exam_id = $request->input('exam_id');
        $user_id = $request->input('user_id');

        $exam = Exam::findOrFail($exam_id);

        Gate::authorize("exam.examiner", $exam);

        $user = User::findOrFail($user_id);

        if ($exam->observers->contains($user)) {
            redirect()->back();
        }

        $exam->observers()->attach($user);

        return redirect()->back();
    }


    public static function createExamDiscordChannels(Exam $exam, bool $refresh = false)
    {

        if ($exam->discord_voice_channel_id !== null && $exam->discord_text_channel_id !== null) {
            if ($refresh) {
                ExamController::deleteExamDiscordChannels($exam);
                $exam->refresh();
            } else
                return;
        }

        $everyone_role_id = DiscordController::getRoleIdFromGuild(env('DISCORD_GUILD_ID'), '@everyone');

        if (is_null($everyone_role_id)) {
            abort(500, 'Could not find @everyone role');
        }

        $voiceChannel = [
            'name' => "Exam " . $exam->id,
            'type' => 2,
            'topic' => "Exam Channel",
            'parent_id' => env('DISCORD_CATEGORY_ID'),
            'permission_overwrites' => ExamController::getOverwritesForExamVoiceDiscordChannel($exam, $everyone_role_id),
        ];

        $textChannel = [
            'name' => "Exam " . $exam->id,
            'type' => 0,
            'topic' => "Exam Channel",
            'parent_id' => env('DISCORD_CATEGORY_ID'),
            'permission_overwrites' => ExamController::getOverwritesForExamTextDiscordChannel($exam, $everyone_role_id),
        ];

        $voiceChannel = DiscordController::postToApi('/guilds/' . env('DISCORD_GUILD_ID') . '/channels', $voiceChannel);
        $textChannel = DiscordController::postToApi('/guilds/' . env('DISCORD_GUILD_ID') . '/channels', $textChannel);

        $exam->discord_voice_channel_id = $voiceChannel['id'];
        $exam->discord_text_channel_id = $textChannel['id'];

        $exam->save();
    }

    public static function deleteDiscordChannel(int $exam_id)
    {
        $exam = Exam::findOrFail($exam_id);

        Gate::authorize("exam.examiner", $exam);

        ExamController::deleteExamDiscordChannels($exam);

        return redirect()->back();
    }

    public static function sendContentToExamDiscordChannel(ExamStepAbility $ability)
    {
        switch ($ability->content_type) {
            case 'text':
                $content = ExamController::buildAbilityTextEmbed($ability);
                break;
            case 'image':
                $content = ExamController::buildAbilityImageEmbed($ability);
                break;
            default:
                abort(500, 'Unknown content type');
                break;
        }

        $message =  DiscordController::postToApi("/channels/" . $ability->step->exam->discord_text_channel_id . "/messages", $content);

        $ability->discord_message_id = $message['id'];
        $ability->save();
    }

    public static function buildAbilityTextEmbed(ExamStepAbility $ability)
    {
        $message = [
            "embed" => [
                'title' => $ability->title,
                'description' => $ability->description,
                'timestamp' => now()->format("Y-m-d\TH:i:s"),
                'fields' => [
                    [
                        'name' => $ability->content_title,
                        'value' => '`' . $ability->content_description . '`',
                    ]
                ],
                'footer' => [
                    'text' => 'Powered by REPORTIK',
                ],
            ],
        ];

        return $message;
    }

    public static function buildAbilityImageEmbed(ExamStepAbility $ability)
    {
        $message = [
            "embed" => [
                'title' => $ability->title,
                'description' => $ability->description,
                'timestamp' => now()->format("Y-m-d\TH:i:s"),
                'fields' => [
                    [
                        'name' => $ability->content_title,
                        'value' =>  $ability->content_description . " \n (Click on the image to expand)",
                    ]
                ],
                "image" => [
                    "url" => $ability->content_path,
                ],
                'footer' => [
                    'text' => 'Powered by REPORTIK',
                ],
            ],
        ];

        return $message;
    }

    public static function deleteExamDiscordChannels(Exam $exam)
    {
        DiscordController::deleteChannel($exam->discord_voice_channel_id);
        DiscordController::deleteChannel($exam->discord_text_channel_id);

        $exam->discord_voice_channel_id = null;
        $exam->discord_text_channel_id = null;

        $exam->save();
    }

    public static function getOverwritesForExamTextDiscordChannel(Exam $exam, string $everyone_role_id)
    {
        $overwrites = [];

        $overwrites[] = [
            'id' => $everyone_role_id,
            'type' => 0,
            'allow' => 0,
            'deny' => 1049600,
        ];

        foreach ($exam->candidates as $candidate)
            $overwrites[] = [
                'id' => $candidate->discord_id,
                'type' => 1,
                'allow' => 3072,
                'deny' => 0,
            ];


        foreach ($exam->examiners as $examiner)
            $overwrites[] = [
                'id' => $examiner->discord_id,
                'type' => 1,
                'allow' => 3072,
                'deny' => 0,
            ];

        foreach ($exam->invigilators as $invigilator)
            $overwrites[] = [
                'id' => $invigilator->discord_id,
                'type' => 1,
                'allow' => 1024,
                'deny' => 2048,
            ];

        return $overwrites;
    }


    public static function getOverwritesForExamVoiceDiscordChannel(Exam $exam, string $everyone_role_id)
    {
        $overwrites = [];

        $overwrites[] = [
            'id' => $everyone_role_id,
            'type' => 0,
            'allow' => 0,
            'deny' => 1049600,
        ];

        foreach ($exam->examiners as $examiner)
            $overwrites[] = [
                'id' => $examiner->discord_id,
                'type' => 1,
                'allow' => 66061568,
                'deny' => 0,
            ];

        foreach ($exam->candidates as $candidate)
            $overwrites[] = [
                'id' => $candidate->discord_id,
                'type' => 1,
                'allow' => 36701184,
                'deny' => 0,
            ];

        foreach ($exam->invigilators as $invigilator)
            $overwrites[] = [
                'id' => $invigilator->discord_id,
                'type' => 1,
                'allow' => 3146752,
                'deny' => 33555200,
            ];

        return $overwrites;
    }
}
