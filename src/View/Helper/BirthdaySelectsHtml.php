<?php
namespace LeoGalleguillos\User\View\Helper;

use LeoGalleguillos\User\Model\Entity as UserEntity;
use LeoGalleguillos\User\Model\Service as UserService;
use Laminas\View\Helper\AbstractHelper;

class BirthdaySelectsHtml extends AbstractHelper
{
    public function __invoke(): string
    {
        return $this->getMonthSelectHtml()
            . $this->getDaySelectHtml()
            . $this->getYearSelectHtml();
    }

    protected function getDaySelectHtml(): string
    {
        $dayOptions = [
            '' => 'Day',
        ];
        for ($dayIteration = 1; $dayIteration <= 31; $dayIteration++) {
            $dayIterationPadded = sprintf("%02d", $dayIteration);
            $dayOptions[$dayIterationPadded] = $dayIteration;
        }

        $selectHtml = '<select name="birthday-day">';
        foreach ($dayOptions as $key => $value) {
            $optionHtml = '<option value="' . $key . '">';
            $optionHtml .= $value;
            $optionHtml .= '</option>';
            $selectHtml .= $optionHtml;
        }
        $selectHtml .= '</select>';

        return $selectHtml;
    }

    protected function getMonthSelectHtml(): string
    {
        $monthOptions = [
            '' => 'Month',
            '01' => 'January',
            '02' => 'February',
            '03' => 'March',
            '04' => 'April',
            '05' => 'May',
            '06' => 'June',
            '07' => 'July ',
            '08' => 'August',
            '09' => 'September',
            '10' => 'October',
            '11' => 'November',
            '12' => 'December',
        ];

        $selectHtml = '<select name="birthday-month">';
        foreach ($monthOptions as $key => $value) {
            $optionHtml = '<option value="' . $key . '">';
            $optionHtml .= $value;
            $optionHtml .= '</option>';
            $selectHtml .= $optionHtml;
        }
        $selectHtml .= '</select>';

        return $selectHtml;
    }

    protected function getYearSelectHtml(): string
    {
        $yearOptions = [
            '' => 'Year',
        ];
        for ($yearIteration = date('Y'); $yearIteration >= date('Y') - 120; $yearIteration--) {
            $yearOptions[$yearIteration] = $yearIteration;
        }

        $selectHtml = '<select name="birthday-year">';
        foreach ($yearOptions as $key => $value) {
            $optionHtml = '<option value="' . $key . '">';
            $optionHtml .= $value;
            $optionHtml .= '</option>';
            $selectHtml .= $optionHtml;
        }
        $selectHtml .= '</select>';

        return $selectHtml;
    }
}
