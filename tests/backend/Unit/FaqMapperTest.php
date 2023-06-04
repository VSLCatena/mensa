<?php

namespace Tests\backend\Unit;

use App\Http\Controllers\Api\v1\Faq\Mappers\FaqMapper;
use App\Models\Faq;
use Tests\TestCase;

class FaqMapperTest extends TestCase
{
    private readonly FaqMapper $faqMapper;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faqMapper = new FaqMapper();
    }

    public function test_if_a_faq_is_correctly_mapped()
    {
        // Arrange
        $faq = Faq::factory()->make([
            'id' => 'id',
            'question' => 'question',
            'answer' => 'answer',
        ]);

        // Act
        $result = $this->faqMapper->map($faq);

        // Assert
        $this->assertEquals('id', $result->id);
        $this->assertEquals('question', $result->question);
        $this->assertEquals('answer', $result->answer);
    }
}
