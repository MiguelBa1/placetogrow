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

        $micrositeField = new MicrositeField([
            'name' => $request->validated('name'),
            'label' => $request->validated('name'),
            'type' => $request->validated('type'),
            'validation_rules' => $request->validated('validation_rules'),
            'options' => $request->validated('options'),
            'modifiable' => true,
        ]);

        $microsite->fields()->save($micrositeField);

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

        return back();
    }

    /**
     * @throws AuthorizationException
     */
    public function update(UpdateMicrositeFieldRequest $request, Microsite $microsite, MicrositeField $field): RedirectResponse
    {
        $this->authorize(PolicyName::UPDATE->value, $microsite);

        if (!$field->modifiable) {
            return back()->withErrors(__('microsite_fields.not_modifiable'));
        }

        $field->update([
            'name' => $request->validated('name'),
            'label' => $request->validated('name'),
            'type' => $request->validated('type'),
            'validation_rules' => $request->validated('validation_rules'),
            'options' => $request->validated('options'),
        ]);

        $field->translations->where('locale', 'en')->first()->update([
            'label' => $request->validated('translation_en'),
        ]);

        $field->translations->where('locale', 'es')->first()->update([
            'label' => $request->validated('translation_es'),
        ]);

        return back();
    }

    /**
     * @throws AuthorizationException
     */
    public function destroy(Microsite $microsite, MicrositeField $field): RedirectResponse
    {
        $this->authorize(PolicyName::DELETE->value, $microsite);

        if (!$field->modifiable) {
            return back()->withErrors(__('microsite_fields.not_modifiable'));
        }

        $field->translations()->delete();

        $field->delete();

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
