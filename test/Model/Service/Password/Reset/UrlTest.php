<?php
namespace MonthlyBasis\UserTest\Model\Service\Password\Reset;

use MonthlyBasis\User\Model\Service as UserService;
use PHPUnit\Framework\TestCase;

class UrlTest extends TestCase
{
    protected function setUp(): void
    {
        $this->urlService = new UserService\Password\Reset\Url();
    }

    public function test_getUrl_string()
    {
        $_SERVER['HTTP_HOST'] = 'example.com';

        $userId = 12345;
        $code   = 'code';
        $url    = $this->urlService->getUrl($userId, $code);

        $this->assertSame(
            'https://example.com/reset-password/12345/code',
            $url
        );
    }
}
