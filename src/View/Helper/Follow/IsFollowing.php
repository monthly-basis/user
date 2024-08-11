<?php
namespace MonthlyBasis\User\View\Helper\Follow;

use Laminas\View\Helper\AbstractHelper;
use MonthlyBasis\User\Model\Service as UserService;

class IsFollowing extends AbstractHelper
{
    public function __construct(
        protected UserService\Follow\IsFollowing $isFollowingService
    ) {
    }

    public function __invoke(
        UserEntity\User $userEntity1,
        UserEntity\User $userEntity2,
    ): bool {
        return $this->isFollowingService->isFollowing(
            $userEntity1,
            $userEntity2,
        );
    }
}
