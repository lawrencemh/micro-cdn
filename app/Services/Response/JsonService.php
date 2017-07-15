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
     * @param $array
     * @param string|null $type
     * @param string $entityStatus
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
        } else {

            // single entity
            $this->response['data'] = $this->generateEntityArrayObject($array, $type, $entityStatus);
        }

        return $this;
    }

    /**
     * Return an array formatting the given object.
     *
     * @param $array
     * @param $type
     * @param $entityStatus
     * @return array
     */
    protected function generateEntityArrayObject($array, $type, $entityStatus)
    {
        $object = [];

        // set the entity's ID if it exists
        if (!empty($id = $array['id']) && is_int($id)) {
            $object['id'] = $id;
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
     * Return the rendered response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function render()
    {
        return response()->json($this->response, $this->responseCode);
    }
}