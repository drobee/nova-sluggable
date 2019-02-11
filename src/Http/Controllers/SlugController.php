<?php

namespace Drobee\NovaSluggable\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class SlugController
{
    protected $options = [
        'generateUniqueSlugs' => false,
        'maximumLength' => 255,
        'slugSeparator' => '-',
        'slugLanguage' => 'en',
    ];

    protected $attribute;
    protected $model;
    protected $slug = '';
    
    protected $updating = false;
    protected $initialValue = '';

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function getSlug(Request $request)
    {
        if ($request->filled('attribute')) {
            $this->attribute = $request->input('attribute');
        }

        if ($request->filled('updating')) {
            $this->updating = $request->input('updating');
        }

        if ($request->filled('initialValue')) {
            $this->initialValue = $request->input('initialValue');
        }

        if (!$request->filled('value')) {
            return $this->sendResponse();
        }

        if ($request->filled('model')) {
            $this->getModelOptions($request->input('model'));
        }

        if ($request->filled('options')) {
            $this->mergeOptions($request->input('options'));
        }
        
        if ($this->options['generateUniqueSlugs'] && !$this->model) {
            throw new \Exception('Slug model undefined! Use slugModel() on the slug field!');
        }

        return $this->generateSlug($request->input('value'));
    }

    /**
     * @param string $modelClass
     */
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

        if ($modelSlugOptions = $model->getSlugOptions()) {
            $modelOptions = [
                'generateUniqueSlugs' => $modelSlugOptions->generateUniqueSlugs,
                'maximumLength' => $modelSlugOptions->maximumLength,
                'slugSeparator' => $modelSlugOptions->slugSeparator,
                'slugLanguage' => $modelSlugOptions->slugLanguage,
            ];
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

    /**
     * @param string $value
     * @return \Illuminate\Http\JsonResponse
     */
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

    /**
     * @param string $originalSlug
     * @return string
     */
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
     * @param string $slug
     * @return bool
     */
    protected function otherRecordExistsWithSlug(string $slug)
    {
        $initialValue = $this->initialValue;
        $modelAttribute = $this->attribute;
        
        $model = $this->model;
        
        return (bool) $model::where($this->attribute, $slug)
            ->when($this->updating, function ($query) use ($modelAttribute, $initialValue) {
                return $query->where($modelAttribute, '!=', $initialValue);
            })
            ->withoutGlobalScopes()
            ->first();
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

    /**
     * @param string $error
     * @return mixed
     */
    protected function sendErrorResponse(string $error)
    {
        return response()->json([
            'errors' => [
                $this->attribute => [
                    $error,
                ],
            ],
        ], 422);
    }
}
