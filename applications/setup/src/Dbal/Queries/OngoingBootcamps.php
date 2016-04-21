<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Dbal\Queries;

use DateTime;
use Doctrine\DBAL\Query\QueryBuilder;

class OngoingBootcamps extends QueryBuilder
{
    /**
     * @param DateTime $aDate
     * @return array
     */
    public function during(DateTime $aDate)
    {
        $this
            ->addSelect('b.bootcamp_id')
            ->from('bootcamps', 'b')
            ->where('b.start_date <= :aDate AND :aDate <= b.stop_date')
            ->setParameter('aDate', $aDate->format('Y-m-d'))
        ;

        return $this->execute()->fetchAll();
    }
}
