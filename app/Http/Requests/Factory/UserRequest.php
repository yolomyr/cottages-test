<?php


namespace App\Http\Requests\Factory;


use App\Http\Requests\Factory\BaseRequest;
use App\Models\UserEstate;

class UserRequest extends BaseRequest
{

    final public function unsetUnchanged(): void
    {
        $user = auth()->user();
        $post_data = $this->post();

        foreach ($post_data as $post_key => $post_field) {
            if (isset($user->{$post_key}) && $post_field === $user->{$post_key}) {
                $this->request->remove($post_key);
                $this->json->remove($post_key);
            }
        }
    }

    final public function unsetEmpty(): void
    {
    }
}
