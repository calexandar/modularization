<?php

namespace Modules\Order\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Spatie\MailcoachSdk\Facades\Mailcoach;

class MailcoachTest extends Controller
{
    public function __invoke()
    {
        $subscriber = Mailcoach::createSubscriber(
        emailListUuid: 'c3ac59df-504c-4771-a311-aa3e62403e0b',
        attributes: [
            'email' => 'calexandar@hotmail.com',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'tags' => ['Newsletter'],
        ]);

       

        return new JsonResponse($subscriber->toArray());
    }
}