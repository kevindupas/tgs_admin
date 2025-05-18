<?php

namespace App\Filament\Pages;

use App\Models\Salon;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Pages\Tenancy\RegisterTenant as BaseRegisterTenant;
use Illuminate\Support\Facades\Auth;

class RegisterTenant extends BaseRegisterTenant
{
    public static function getLabel(): string
    {
        return 'Enregistrer votre salon';
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Nom')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    protected function handleRegistration(array $data): Salon
    {
        /** @var Salon $salon */
        $salon = Salon::create($data);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $user->salons()->attach($salon->id);

        return $salon;
    }
}
