<?php

declare(strict_types = 1);

namespace App\Tests\Unit;

use App\Entity\User;

class Helper
{
    public static function getUsers(): array
    {
        return [
            (new User())
                ->setId(1)
                ->setName('John Doe')
                ->setEmail('john@randommail.local')
                ->setCreatedAtNow()
            ,
            (new User())
                ->setId(2)
                ->setName('Jane Smith Johnson')
                ->setEmail('jane@webmail.dev')
                ->setCreatedAtNow()
                ->setUpdatedAtNow()
            ,
            (new User())
                ->setId(3)
                ->setName('Michael Brown')
                ->setEmail('michael@techcorp.localhost')
                ->setCreatedAtNow()
            ,
            (new User())
                ->setId(4)
                ->setName('Emily Davis')
                ->setEmail('emily.d@internetprovider.local')
                ->setCreatedAtNow()
                ->setUpdatedAtNow()
            ,
            (new User())
                ->setId(5)
                ->setName('William Roberts')
                ->setEmail('william@fastmail.dev')
                ->setCreatedAtNow()
            ,
            (new User())
                ->setId(6)
                ->setName('Jessica Wilson Thompson')
                ->setEmail('jessica@supermail.local')
                ->setCreatedAtNow()
                ->setUpdatedAtNow()
            ,
            (new User())
                ->setId(7)
                ->setName('David Miller')
                ->setEmail('david@companymail.localhost')
                ->setCreatedAtNow()
            ,
            (new User())
                ->setId(8)
                ->setName('Sarah Taylor Green')
                ->setEmail('sarah@personalmail.dev')
                ->setCreatedAtNow()
                ->setUpdatedAtNow()
            ,
            (new User())
                ->setId(9)
                ->setName('Christopher Anderson Lee')
                ->setEmail('chris.anderson@freemail.local')
                ->setCreatedAtNow()
            ,
            (new User())
                ->setId(10)
                ->setName('Amanda Thompson Silva')
                ->setEmail('amanda@customdomain.localhost')
                ->setCreatedAtNow()
                ->setUpdatedAtNow()
            ,
        ];
    }
}
