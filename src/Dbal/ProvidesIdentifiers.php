<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Dbal;

use Doctrine\DBAL\Connection;

trait ProvidesIdentifiers
{
    /**
     * @param Connection $connection
     * @param string $sequence
     * @return int
     */
    public function nextIdentifierValue(Connection $connection, $sequence)
    {
        $this->updateNextIdentityValue($connection, $sequence);

        $builder = $connection->createQueryBuilder();
        $builder
            ->select('next_val')
            ->from($sequence)
            ->setMaxResults(1)
        ;

        return (int) $builder->execute()->fetchColumn();
    }

    /**
     * @param Connection $connection
     * @param string $sequence
     * @return \Doctrine\DBAL\Driver\Statement
     * @throws InvalidPlatform
     */
    private function updateNextIdentityValue(Connection $connection, $sequence)
    {
        $platform = $connection->getDatabasePlatform()->getName();
        if ($platform === 'mysql') {
            return $connection->executeQuery(
                "UPDATE {$sequence} SET next_val = LAST_INSERT_ID(next_val + 1)"
            );
        } elseif ($platform === 'sqlite') {
            return $connection->executeQuery(
                "UPDATE {$sequence} SET next_val = next_val + 1"
            );
        }
        throw InvalidPlatform::named($platform);
    }
}
