<?php declare(strict_types=1);

namespace App\Request\Validator\Constraint;

use App\Request\Validator\EntityExistsValidator;
use Symfony\Component\Validator\Constraint;

#[\Attribute]
class EntityExists extends Constraint
{
    public string $message = 'Value does not exist';
    public string $entityClass;
    public string $field;

    /**
     * @param string $entityClass
     * @param string $field
     * @param string|null $message
     * @param array|null $groups
     * @param mixed|null $payload
     */
    public function __construct(string $entityClass, string $field = 'id', string $message = null, array $groups = null, mixed $payload = null)
    {
        parent::__construct([], $groups, $payload);
        $this->entityClass = $entityClass;
        $this->field = $field;
        if ($message) {
            $this->message = $message;
        }
    }

    /**
     * @return string
     */
    public function validatedBy(): string
    {
        return EntityExistsValidator::class;
    }
}