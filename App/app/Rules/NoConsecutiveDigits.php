<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Closure;

class NoConsecutiveDigits implements ValidationRule
{
    protected $length;

    /**
     * Crea una nueva instancia de la regla.
     *
     * @param  int  $length
     * @return void
     */
    public function __construct(int $length = 4)
    {
        $this->length = $length;
    }

    /**
     * Ejecuta la regla de validación.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Verifica si el valor contiene dígitos consecutivos iguales según la longitud especificada
        if (preg_match('/(\d)\1{' . ($this->length - 1) . '}/', $value)) {
            $fail(__('No puede tener más de :length dígitos consecutivos', ['length' => $this->length]));
        }
    }
}
