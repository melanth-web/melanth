<?php

if (! function_exists('value')) {
    /**
     * Return the default value of the given value.
     *
     * @param mixed $value The default value.
     *
     * @return mixed
     */
    function value($value)
    {
        return $value instanceof Closure ? $value() : $value;
    }
}
