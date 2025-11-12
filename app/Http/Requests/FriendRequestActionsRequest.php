<?php

namespace App\Http\Requests;

class FriendRequestActionsRequest extends BaseFormRequest
{
    /**
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return string[]
     */
    public function rules(): array
    {
        if ($this->route()->getName() === 'friend-request.send') {
            return [
                'receiver_id' => 'required|int|min:1',
            ];
        }

        return [
            'sender_id' => 'required|int|min:1',
        ];
    }
}
