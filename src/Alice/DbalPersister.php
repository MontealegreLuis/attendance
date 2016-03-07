<?php
/**
 * PHP version 5.6
 *
 * This source file is subject to the license that is bundled with this package in the file LICENSE.
 */
namespace Codeup\Alice;

use Codeup\Bootcamps\BootcampId;
use Codeup\Bootcamps\Bootcamps;
use Nelmio\Alice\PersisterInterface;

class DbalPersister implements PersisterInterface
{
    /** @var Bootcamps */
    private $bootcamps;

    /**
     * @param Bootcamps $bootcamps
     */
    public function __construct(Bootcamps $bootcamps)
    {
        $this->bootcamps = $bootcamps;
    }

    public function persist(array $objects)
    {
        foreach ($objects as $object) {
            $this->bootcamps->add($object);
        }
    }

    public function find($class, $id)
    {
        return $this->bootcamps->with(BootcampId::fromLiteral($id));
    }
}
