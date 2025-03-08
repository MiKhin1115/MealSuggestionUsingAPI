<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class BlockedEmailDomains implements Rule
{
    /**
     * List of blocked email domains
     *
     * @var array
     */
    protected $blockedDomains;

    /**
     * Create a new rule instance.
     *
     * @param array $additionalDomains Additional domains to block
     * @return void
     */
    public function __construct(array $additionalDomains = [])
    {
        // Default list of blocked domains
        $this->blockedDomains = array_merge(
            ['emaple.com', 'example.com'], // Our blocked domains
            $additionalDomains
        );
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
        // If not an email or empty, let other validation rules handle it
        if (empty($value) || !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            return true;
        }

        // Extract domain from email
        $domain = strtolower(explode('@', $value)[1] ?? '');

        // Check if domain is in the blocked list
        return !in_array($domain, $this->blockedDomains);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This email domain is not allowed for registration.';
    }
} 