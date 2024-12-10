<?php
namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
 
enum ReligionStatus: string implements HasLabel
{
    case Islam = 'Islam';
    case Katolik = 'Katolik';
    case Protestan = 'Protestan';
    case Hindu = 'Hindu';
    case Buddha = 'Buddha';
    case Khonghucu = 'Khonghucu';
    
    public function getLabel(): ?string
    {
        return $this->name;
        
        // or
    
        return match ($this) {
            self::Islam => 'Islam',
            self::Katolik => 'Katolik',
            self::Protestan => 'Protestan',
            self::Hindu => 'Hindu',
            self::Buddha => 'Buddha',
            self::Khonghucu => 'Khonghucu'
        };
    }
}