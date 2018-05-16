<?php

namespace App\Services\Response;

class JsonService
{
    /**
     * The array of the response builder.
     *
     * @var array
     */
    protected $response = [];

    /**
     * The response code to return.
     *
     * @var int
     */
    protected $responseCode = 200;

    /**
     * Set the array of the object's attributes, or the collection of objects and their attributes.
     *
     * @param             $array
     * @param string|null $type
     * @param string      $entityStatus
     *
     * @return $this
     */
    public function setReturnObject($array, $type = null, $entityStatus = 'exists')
    {
        // check if $array is an array of entities, or a single entity.
        if (isset($array[0]) && is_array($array[0])) {

            // collection of entities
            foreach ($array as $item) {
                $this->response['data'][] = $this->generateEntityArrayObject($item, $type, $entityStatus);
            }
        } elseif (count($array) > 0) {

            // single entity
            $this->response['data'] = $this->generateEntityArrayObject($array, $type, $entityStatus);
        } else {

            // array is empty
            $this->response['data'] = null;
        }

        return $this;
    }

    /**
     * Return an array formatting the given object.
     *
     * @param $array
     * @param $type
     * @param $entityStatus
     *
     * @return array
     */
    protected function generateEntityArrayObject($array, $type, $entityStatus)
    {
        $object = [];

        // set the entity's ID if it exists
        if (isset($array['id']) && is_int($array['id'])) {
            $object['id'] = $array['id'];
            unset($array['id']);
        }

        // set the object's type
        if (!empty($type)) {
            $object['type'] = $type;
        }

        // set the object's status
        if (!empty($entityStatus)) {
            $object['status'] = $entityStatus;
        }

        // set the object's attributes
        if (!empty($array)) {
            $object['attributes'] = $array;
        }

        return $object;
    }

    /**
     * Set the errors to be included in the response.
     *
     * @param $array
     *
     * @return $this
     */
    public function setErrors($array)
    {
        $this->response['errors'] = $array;

        return $this;
    }

    /**
     * Return resource not found error response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function resourceNotFound()
    {
        $this->setErrors(['Resource not found']);
        $this->setResponseCode(404);

        return $this->render();
    }

    /**
     * Return unauthorised request response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function unauthorisedRequest()
    {
        $this->setErrors(['Unauthorized']);
        $this->setResponseCode(401);

        return $this->render();
    }

    /**
     * Set the HTTP response code.
     *
     * @param int $code
     *
     * @return $this
     */
    public function setResponseCode($code = 200)
    {
        $this->responseCode = $code;

        return $this;
    }

    /**
     * Return the rendered response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function render()
    {
        return response()->json($this->response, $this->responseCode);
    }
}
