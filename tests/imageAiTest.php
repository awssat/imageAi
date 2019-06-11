<?php

namespace Awssat\ImageAi\Test;

use Awssat\ImageAi\imageAi;
use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use PHPUnit\Framework\TestCase;

class imageAiTest extends TestCase
{

    /** @test * */
    public function it_run()
    {
        $manager = new ImageManager();

        $img = $manager->make('foo.jpg');

        $imageAi = imageAi::image($img)->detect();
        $imageAi->results[0]->image->save('car.jpg');
        $this->assertEquals('car', $imageAi->results[0]->name);
    }
}