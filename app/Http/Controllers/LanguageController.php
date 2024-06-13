<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LanguageController extends Controller
{
    protected array $availableLocales = ['en', 'es'];

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'locale' => ['required', 'string', Rule::in($this->availableLocales)],
        ]);

        if (in_array($request->get('locale'), $this->availableLocales)) {
            $request->session()->put('locale', $request->get('locale'));
        }

        return redirect()->back();
    }
}
