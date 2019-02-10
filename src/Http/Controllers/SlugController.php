<?php

namespace Drobee\NovaSluggable\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SlugController
{
    protected $options = [
        'generateUniqueSlugs' => true,
        'maximumLength' => 255,
        'slugSeparator' => '-',
        'slugLanguage' => 'en',
    ];

    protected $attribute;
    protected $model;
    protected $slug = '';

    public function getSlug(Request $request)
    {
        dd($request->input('field'));
        if ($request->filled('attribute')) {
            $this->attribute = $request->input('attribute');
        }

        //return $this->sendErrorResponse();
        if (!$request->filled('value')) {
            return $this->sendResponse();
        }

        if ($request->filled('model')) {
            $this->getModelOptions($request->input('model'));
        }

        if ($request->filled('options')) {
            $this->mergeOptions($request->input('options'));
        }

        return $this->generateSlug($request->input('value'));
    }

    protected function getModelOptions(string $modelClass)
    {
        if (!class_exists($modelClass)) {
            return;
        }

        $model = new $modelClass;
        $this->model = $modelClass;

        if (!method_exists($model, 'getSlugOptions')) {
            return;
        }

        if ($modelOptions = $model->getSlugOptions()) {
            $this->mergeOptions($modelOptions);
        }
    }

    /**
     * Validate and merge options from array
     *
     * @param $options
     */
    protected function mergeOptions($options)
    {
        if (!is_array($options)) {
            return;
        }

        $validator = Validator::make($options, [
            'generateUniqueSlugs' => 'boolean',
            'maximumLength' => 'numeric',
            'slugSeparator' => 'string',
            'slugLanguage' => 'string',
        ]);

        $options = $validator->validate();

        if ($options) {
            $this->options = array_merge($this->options, $options);
        }
    }

    protected function generateSlug(string $value)
    {
        $slug = Str::slug($value, $this->options['slugSeparator'], $this->options['slugLanguage']);

        if ($this->options['maximumLength']) {
            $slug = substr($slug, 0, $this->options['maximumLength']);
        }

        if (!$this->options['generateUniqueSlugs']) {
            $this->slug = $slug;
            return $this->sendResponse();
        } else {
            $this->slug = $this->generateUniqueSlug($slug);
            return $this->sendResponse();
        }
    }

    protected function generateUniqueSlug(string $originalSlug): string
    {
        $slug = $originalSlug;
        $i = 1;

        while ($this->otherRecordExistsWithSlug($slug)) {
            $slug = $originalSlug . $this->options['slugSeparator'] . $i++;
        }

        return $slug;
    }

    /**
     * Send JSON response with slug
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function sendResponse()
    {
        return response()->json([
            'slug' => $this->slug,
        ]);
    }

    protected function sendErrorResponse()
    {
        return response()->json([
            'errors' => [
                $this->attribute => [
                    'valami',
                ],
            ],
        ], 422);
    }

    protected function otherRecordExistsWithSlug(string $slug)
    {
        $model = $this->model;
        return (bool) $model::where($this->attribute, $slug)
            ->withoutGlobalScopes()
            ->first();
    }
}
