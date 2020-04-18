<?php

namespace Melanth\Http\Concerns;

use Melanth\Support\Str;

trait HasContentTypes
{
    /**
     * Determine whetehr the request is for XML docuemnt.
     *
     * @return bool
     */
    public function isXMLHttpRequest() : bool
    {
        return $this->headers->get('X-Requested-With') === 'XMLHttpRequest';
    }

    /**
     * Determine whether the request is the result of an AJAX call.
     *
     * @return bool
     */
    public function ajax() : bool
    {
        return $this->isXMLHttpRequest();
    }

    /**
     * Determine whether the request is the result of a PAJAX call.
     *
     * @return bool
     */
    public function pajax() : bool
    {
        return $this->headers->get('X-PJAX') === true;
    }

    /**
     * Determine whether the request is sending json format.
     *
     * @param string|null $type The content type.
     *
     * @return bool
     */
    public function isJson(string $type = null) : bool
    {
        $type = $type ?: $this->headers->get('CONTENT_TYPE');

        return Str::contains($type, ['/json', '+json']);
    }
}
