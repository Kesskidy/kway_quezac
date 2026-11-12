<?php

namespace App\Http\Requests;

use App\Services\Game\GameEngine;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

/**
 * Validates the player's inputs (regle.md, section 6). The real-time check is done
 * in the controller, since it needs the replayed duration.
 */
class FinishGameRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'inputs' => ['present', 'array', 'list', 'max:'.config('game.max_inputs')],
            'inputs.*' => ['array:tick,action'],
            'inputs.*.tick' => ['required', 'integer', 'min:0', 'max:'.(config('game.max_ticks') - 1)],
            'inputs.*.action' => ['required', 'in:'.GameEngine::COVER.','.GameEngine::UNCOVER],
        ];
    }

    /**
     * Order rules: ticks strictly increasing, actions alternating, starting with `cover`.
     *
     * @return list<callable>
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $previousTick = -1;
                $expectedAction = GameEngine::COVER;

                foreach ($this->input('inputs') as $index => $input) {
                    if ((int) $input['tick'] <= $previousTick) {
                        $validator->errors()->add("inputs.{$index}.tick", 'Les entrées doivent être dans l’ordre.');

                        return;
                    }
                    if ($input['action'] !== $expectedAction) {
                        $validator->errors()->add("inputs.{$index}.action", 'Les entrées doivent alterner, en commençant par « cover ».');

                        return;
                    }

                    $previousTick = (int) $input['tick'];
                    $expectedAction = $expectedAction === GameEngine::COVER ? GameEngine::UNCOVER : GameEngine::COVER;
                }
            },
        ];
    }

    /**
     * Inputs ready for the GameEngine (integer ticks, only the expected keys).
     *
     * @return list<array{tick: int, action: string}>
     */
    public function inputs(): array
    {
        return array_map(
            fn (array $input) => ['tick' => (int) $input['tick'], 'action' => $input['action']],
            $this->validated('inputs'),
        );
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'inputs.present' => 'Les entrées de la partie sont manquantes.',
            'inputs.array' => 'Les entrées de la partie sont invalides.',
            'inputs.list' => 'Les entrées de la partie sont invalides.',
            'inputs.max' => 'Trop d’entrées pour une seule partie.',
            'inputs.*.array' => 'Une entrée de la partie est invalide.',
            'inputs.*.tick.*' => 'Une entrée de la partie est invalide.',
            'inputs.*.action.*' => 'Une entrée de la partie est invalide.',
        ];
    }
}
