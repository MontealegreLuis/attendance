<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup;

use DateTime;

class Bootcamp
{
    /** @var DateTime */
    private $startDate;

    /** @var string */
    private $cohortName;

    /**
     * @param DateTime $startDate
     * @param string $cohortName
     */
    private function __construct(DateTime $startDate, $cohortName)
    {
        $this->setCohortName($cohortName);
        $this->startDate = $startDate;
    }

    /**
     * @param DateTime $onDate
     * @param string $cohortName
     * @return Bootcamp
     */
    public static function start(Datetime $onDate, $cohortName)
    {
        return new Bootcamp($onDate, $cohortName);
    }

    /**
     * @return string
     */
    public function cohortName()
    {
        return $this->cohortName;
    }

    /**
     * @param string $name
     */
    private function setCohortName($name)
    {
        AssertValueIs::notBlank(trim($name), "Cohort's name cannot be empty");
        $this->cohortName = $name;
    }
}
