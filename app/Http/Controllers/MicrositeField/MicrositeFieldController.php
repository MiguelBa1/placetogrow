<?php

namespace App\Http\Controllers\MicrositeField;

use App\Constants\FieldType;
use App\Constants\PolicyName;
use App\Http\Controllers\Controller;
use App\Http\Requests\MicrositeField\CreateMicrositeFieldRequest;
use App\Http\Requests\MicrositeField\UpdateMicrositeFieldRequest;
use App\Models\FieldTranslation;
use App\Models\Microsite;
use App\Models\MicrositeField;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class MicrositeFieldController extends Controller
{
    /**
     * @throws AuthorizationException
     */
    public function store(CreateMicrositeFieldRequest $request, Microsite $microsite): RedirectResponse
    {
        $this->authorize(PolicyName::CREATE->value, Microsite::class);

        $micrositeField = MicrositeField::create([
            'name' => $request->validated('name'),
            'label' => $request->validated('name'),
            'type' => $request->validated('type'),
            'validation_rules' => $request->validated('validation_rules'),
            'options' => $request->validated('options'),
        ]);

        FieldTranslation::create([
            'field_id' => $micrositeField->id,
            'locale' => 'en',
            'label' => $request->validated('translation_en'),
        ]);

        FieldTranslation::create([
            'field_id' => $micrositeField->id,
            'locale' => 'es',
            'label' => $request->validated('translation_es'),
        ]);

        $microsite->fields()->attach($micrositeField->id, ['modifiable' => true]);

        return back();
    }

    /**
     * @throws AuthorizationException
     */
    public function update(UpdateMicrositeFieldRequest $request, Microsite $microsite, MicrositeField $field): RedirectResponse
    {
        $this->authorize(PolicyName::UPDATE->value, $microsite);

        $pivot = $microsite->fields()->where('microsite_field_id', $field->id)->first()->pivot;

        if (!$pivot->modifiable) {
            return back()->withErrors(__('microsite_fields.not_modifiable'));
        }

        $field->update([
            'name' => $request->validated('name'),
            'label' => $request->validated('name'),
            'type' => $request->validated('type'),
            'validation_rules' => $request->validated('validation_rules'),
            'options' => $request->validated('options'),
        ]);

        FieldTranslation::updateOrCreate(
            ['field_id' => $field->id, 'locale' => 'en'],
            ['label' => $request->validated('translation_en')]
        );

        FieldTranslation::updateOrCreate(
            ['field_id' => $field->id, 'locale' => 'es'],
            ['label' => $request->validated('translation_es')]
        );

        $microsite->fields()->updateExistingPivot($field->id, ['modifiable' => true]);

        return back();
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Microsite $microsite, MicrositeField $field): RedirectResponse
    {
        $this->authorize(PolicyName::DELETE->value, $microsite);

        $pivot = $microsite->fields()->where('microsite_field_id', $field->id)->first()->pivot;

        if (!$pivot->modifiable) {
            return back()->withErrors(__('microsite_fields.not_modifiable'));
        }

        $microsite->fields()->detach($field->id);

        if ($field->microsites()->count() === 0) {
            $field->translations()->delete();
            $field->delete();
        }

        return back();
    }

    /**
     * @throws AuthorizationException
     */
    public function getFieldTypes(): JsonResponse
    {
        $this->authorize(PolicyName::VIEW_ANY->value, Microsite::class);

        return response()->json(FieldType::toSelectArray());
    }
}
