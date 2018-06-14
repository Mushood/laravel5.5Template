<?php

namespace App\Traits;

trait Publishable
{
    /**
     * Set entity to active
     *
     * @param Entity $entity Route Model Binding
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function publish(Entity $entity)
    {
        $entity->active = true;
        $entity->save();

        return response()->json(
            [
                'code' => 200,
            ]
        );
    }

    /**
     * Set entity to active
     *
     * @param Entity $entity Route Model Binding
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function unpublish(Entity $entity)
    {
        $entity->active = false;
        $entity->save();

        return response()->json(
            [
                'code' => 200
            ]
        );
    }
}