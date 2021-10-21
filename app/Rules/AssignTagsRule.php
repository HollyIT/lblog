<?php

namespace App\Rules;

use App\Models\Tag;
use Illuminate\Contracts\Validation\Rule;

class AssignTagsRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $ids = [];
        foreach ($value as $input) {
            if (isset($input['id'])) {
                $ids[] = $input['id'];
            } else {
                $tag = $input['tag'] ?? null;
                if (! trim($tag)) {
                    return false;
                }
            }
        }
        $ids = array_unique($ids);
        if (! empty($ids)) {
            $tags = Tag::whereIn('id', $ids)->count();
            if ($tags !== count($ids)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The validation error message.';
    }
}
