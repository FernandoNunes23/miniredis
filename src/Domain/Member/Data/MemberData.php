<?php


namespace App\Domain\Member\Data;

/**
 * Class MemberData
 *
 * @package App\Domain\Member\Data
 */
final class MemberData
{
    /** @var float */
    public $score;

    /** @var string */
    public $data;

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            "score" => $this->score,
            "data"  => $this->data
        ];
    }
}