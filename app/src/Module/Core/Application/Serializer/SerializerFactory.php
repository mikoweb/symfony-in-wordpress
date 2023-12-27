<?php

namespace App\Module\Core\Application\Serializer;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

final class SerializerFactory
{
    private ?SerializerInterface $serializer = null;

    public function getSerializer(): SerializerInterface
    {
        if (is_null($this->serializer)) {
            $encoders = [new JsonEncoder()];
            $normalizers = [
                new ObjectNormalizer(),
                new DateTimeNormalizer(),
            ];

            $this->serializer = new Serializer($normalizers, $encoders);
        }

        return $this->serializer;
    }
}
