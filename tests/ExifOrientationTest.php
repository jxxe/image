<?php

use Spatie\Image\Drivers\Gd\GdDriver;
use Spatie\Image\Drivers\Imagick\ImagickDriver;

dataset('raw_drivers', [
    'imagick' => [fn () => new ImagickDriver()],
    'gd' => [fn () => new GdDriver()],
]);

it('can rotate an image based on EXIF orientation using orientation(null)', function (Closure $driverFactory) {
    $driver = $driverFactory();
    $image = $driver->loadFile(getTestFile('testOrientation.jpg'), false);

    expect($image->getWidth())->toEqual(280);
    expect($image->getHeight())->toEqual(340);

    $image->orientation(null);

    expect($image->getWidth())->toEqual(340);
    expect($image->getHeight())->toEqual(280);
})->with('raw_drivers');

it('can read EXIF orientation data', function (Closure $driverFactory) {
    $driver = $driverFactory();
    $image = $driver->loadFile(getTestFile('testOrientation.jpg'), false);

    $exif = $image->exif();

    expect($exif)->toHaveKey('Orientation');
    expect((int) $exif['Orientation'])->toEqual(6);
})->with('raw_drivers');

it('produces the same result with autoRotate as with orientation(null)', function (Closure $driverFactory) {
    $autoRotated = $driverFactory()->loadFile(getTestFile('testOrientation.jpg'), true);

    $manualRotated = $driverFactory()->loadFile(getTestFile('testOrientation.jpg'), false);
    $manualRotated->orientation(null);

    expect($manualRotated->getWidth())->toEqual($autoRotated->getWidth());
    expect($manualRotated->getHeight())->toEqual($autoRotated->getHeight());
})->with('raw_drivers');
