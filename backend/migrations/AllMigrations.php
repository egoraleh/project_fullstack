<?php

use migrations\Migration_0;
use migrations\Migration_1;
use migrations\Migration_2;
use migrations\Migration_3;
use migrations\Migration_4;

function getMigrations(): array
{
    return [
        new Migration_0(),
        new Migration_1(),
        new Migration_2(),
        new Migration_3(),
        new Migration_4(),
    ];
}