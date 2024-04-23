<?php

declare(strict_types=1);

use VPremiss\Crafty\Facades\Crafty;

it('has a reverseString method that works for multibyte strings', function () {
    $arabicText = <<<Arabic
وإنّي وإنِّي ثم إنّي وإنَّنِي ... إذا انقطعت نعلي جعلت لها شسعا
Arabic;
    $reversedArabicText = <<<Arabic
اعسش اهل تلعج يلعن تعطقنا اذإ ... يِنَّنإو يّنإ مث يِّنإو يّنإو
Arabic;

    $result = Crafty::reverseString($arabicText);

    expect($result)->toBe($reversedArabicText);
});
