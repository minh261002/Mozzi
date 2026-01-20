<?php

namespace App\Base;

use Illuminate\Foundation\Http\FormRequest;

abstract class BaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Return validation rules based on HTTP method.
     */
    public function rules(): array
    {
        return match (true) {
            $this->isMethod('GET') => $this->rulesGet(),
            $this->isMethod('POST') => $this->rulesPost(),
            $this->isMethod('PUT') => $this->rulesPut(),
            $this->isMethod('PATCH') => $this->rulesPatch(),
            $this->isMethod('DELETE') => $this->rulesDelete(),
            default => [],
        };
    }

    protected function rulesGet(): array
    {
        return [];
    }

    protected function rulesPost(): array
    {
        return [];
    }

    protected function rulesPut(): array
    {
        return [];
    }

    protected function rulesPatch(): array
    {
        return [];
    }

    protected function rulesDelete(): array
    {
        return [];
    }
}
