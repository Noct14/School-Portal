<?php

namespace App\Filament\Auth;

use Filament\Pages\Auth\Login as AuthLogin;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;

class Login extends AuthLogin
{
//    public function form(Form $form): Form
//    {
//       return $form
//          ->schema([
//             $this->getNameFormComponent(),
//             $this->getEmailformComponent(),
//             $this->getPasswordFormComponent(),

//             TextInput::make('phone')
//          ])
//          ->statePath('data');
//    }

    public function mount(): void
    {
        parent::mount();

        if (app()->environment('local')) {
            $this->form->fill([
                'email' => 'admin@123.com',
                'password' => '123',
                'remember' => true,
            ]);
        }
    }
}