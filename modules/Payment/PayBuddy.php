<?php

namespace Modules\Payment;

use NumberFormatter;
use Illuminate\Support\Str;

final class PayBuddy
{
    public function charge(string $token, int $amountInCents, string $statementDescription): array
    {
        $this->validateToken($token);
        
        $numberFormater  = new NumberFormatter('en-US', NumberFormatter::CURRENCY);

        return [
            'id' => (string) Str::uuid(),
            'amount_in_cents' => $amountInCents,
            'localized_amount' => $numberFormater->format($amountInCents / 100),
            'statement_description' => $statementDescription,
            'created_at' => now()->toDateTimeString()
        ]; 
    }

    public function make(): PayBuddy
    {
        return new self();
    }
    
    public static function validToken(): string
    {
        return (string) Str::uuid();
    }

    public static function invalidToken(): string
    {
        return substr(self::validToken(), -35);
    }

    public function validateToken(string $token): void
    {
        if(! Str::isUuid($token)) {
            throw new \RuntimeException('Invalid token.');
        }
    }
}