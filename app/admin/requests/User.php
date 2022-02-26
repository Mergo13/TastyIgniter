<?php

namespace Admin\Requests;

use System\Classes\FormRequest;

class User extends FormRequest
{
    public function attributes()
    {
        return [
            'name' => lang('admin::lang.label_name'),
            'email' => lang('admin::lang.label_email'),
            'username' => lang('admin::lang.staff.label_username'),
            'password' => lang('admin::lang.staff.label_password'),
            'password_confirm' => lang('admin::lang.staff.label_confirm_password'),
            'status' => lang('admin::lang.label_status'),
            'language_id' => lang('admin::lang.staff.label_language_id'),
            'user_role_id' => lang('admin::lang.staff.label_role'),
            'groups' => lang('admin::lang.staff.label_group'),
            'locations' => lang('admin::lang.staff.label_location'),
            'groups.*' => lang('admin::lang.staff.label_group'),
            'locations.*' => lang('admin::lang.staff.label_location'),
        ];
    }

    public function rules()
    {
        $passwordRule = optional($this->getForm())->context != 'create'
            ? 'sometimes' : 'required_if:send_invite,0';

        $rules = [
            'name' => ['required', 'between:2,128'],
            'email' => ['required', 'max:96', 'email:filter', 'unique:users,email'],
            'username' => ['required', 'alpha_dash', 'between:2,32', 'unique:users,username'],
            'password' => [$passwordRule, 'between:6,32', 'same:password_confirm'],
        ];

        if (optional($this->getForm())->context != 'account') {
            $rules = array_merge($rules, [
                'status' => ['boolean'],
                'language_id' => ['nullable', 'integer'],
                'user_role_id' => ['sometimes', 'required', 'integer'],
                'groups' => ['required', 'array'],
                'locations' => ['nullable', 'array'],
                'groups.*' => ['integer'],
                'locations.*' => ['integer'],
            ]);
        }

        return $rules;
    }
}
