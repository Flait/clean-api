<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Authorization;

use App\Enum\Action;
use App\Enum\Role;
use App\Service\Authorization\ReaderAuthorizationStrategy;
use App\Tests\Trait\CreatesUserWithId;
use PHPUnit\Framework\TestCase;

final class ReaderAuthorizationStrategyTest extends TestCase
{
    use CreatesUserWithId;
    private ReaderAuthorizationStrategy $strategy;

    protected function setUp(): void
    {
        $this->strategy = new ReaderAuthorizationStrategy();
    }

    public function testReaderCanOnlyViewArticle(): void
    {
        $reader = $this->createUserWithId('admin@example.com', 'secret', 'test', Role::READER, 1);

        $this->assertTrue($this->strategy->canAccess($reader, Action::ARTICLE_DETAIL));

        $disallowed = [
            Action::ARTICLE_CREATE,
            Action::ARTICLE_UPDATE,
            Action::ARTICLE_DELETE,
        ];

        foreach ($disallowed as $action) {
            $this->assertFalse($this->strategy->canAccess($reader, $action), "Reader should NOT access {$action->value}");
        }
    }
}
