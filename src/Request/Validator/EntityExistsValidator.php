<?php declare(strict_types=1);

namespace App\Request\Validator;

use App\Request\Validator\Constraint\EntityExists;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class EntityExistsValidator extends ConstraintValidator
{
    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(protected EntityManagerInterface $entityManager)
    {
    }

    /**
     * @param mixed $value
     * @param Constraint $constraint
     */
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof EntityExists) {
            throw new \InvalidArgumentException('Unexpected constraint type');
        }

        if (!$value) {
            return;
        }

        $repository = $this->entityManager->getRepository($constraint->entityClass);
        $result = $repository->findOneBy([$constraint->field => $value]);

        if (!$result) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $value)
                ->addViolation();
        }
    }
}