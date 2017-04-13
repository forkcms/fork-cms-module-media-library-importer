<?php

namespace Backend\Modules\MediaLibraryImporter\Component;

use Backend\Modules\MediaLibrary\Domain\MediaGroup\MediaGroup;
use Backend\Modules\MediaLibrary\Domain\MediaGroup\Type;
use PHPUnit\Framework\TestCase;

class MediaGroupToUpdateTest extends TestCase
{
    public function testEntireClass()
    {
        $mediaGroup = MediaGroup::create(Type::image());
        $mediaGroupToUpdate = new MediaGroupToUpdate($mediaGroup);
        $this->assertEquals($mediaGroup, $mediaGroupToUpdate->getMediaGroup());
    }
}
