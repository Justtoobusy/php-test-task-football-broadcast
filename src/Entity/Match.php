<?php

namespace App\Entity;

use App\Service\PositionTimeCalculator;

class Match
{
    public const INFO_MESSAGE_TYPE = 'info';
    public const DANGEROUS_MOMENT_MESSAGE_TYPE = 'dangerousMoment';
    public const GOAL_MESSAGE_TYPE = 'goal';
    public const YELLOW_CARD_MESSAGE_TYPE = 'yellowCard';
    public const RED_CARD_MESSAGE_TYPE = 'redCard';
    public const REPLACE_PLAYER_MESSAGE_TYPE = 'replacePlayer';

    private const MESSAGE_TYPES = [
        self::INFO_MESSAGE_TYPE,
        self::DANGEROUS_MOMENT_MESSAGE_TYPE,
        self::GOAL_MESSAGE_TYPE,
        self::YELLOW_CARD_MESSAGE_TYPE,
        self::RED_CARD_MESSAGE_TYPE,
        self::REPLACE_PLAYER_MESSAGE_TYPE,
    ];

    private string $id;
    private \DateTime $date;
    private string $tournament;
    private Stadium $stadium;
    private Team $homeTeam;
    private Team $awayTeam;
    private array $messages;
    private PositionTimeCalculator $positionTimeCalculator;

    public function __construct(string $id, \DateTime $date, string $tournament, Stadium $stadium, Team $homeTeam, Team $awayTeam)
    {
        $this->id = $id;
        $this->date = $date;
        $this->tournament = $tournament;
        $this->stadium = $stadium;
        $this->homeTeam = $homeTeam;
        $this->awayTeam = $awayTeam;
        $this->messages = [];
        $this->positionTimeCalculator = new PositionTimeCalculator();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getDate(): \DateTime
    {
        return $this->date;
    }

    public function getTournament(): string
    {
        return $this->tournament;
    }

    public function getStadium(): Stadium
    {
        return $this->stadium;
    }

    public function getHomeTeam(): Team
    {
        return $this->homeTeam;
    }

    public function getAwayTeam(): Team
    {
        return $this->awayTeam;
    }

    public function getMessages(): array
    {
        return $this->messages;
    }

    public function addMessage(string $minute, string $text, string $type): void
    {
        $this->assertCorrectType($type);

        $this->messages[] = [
            'minute' => $minute,
            'text' => $text,
            'type' => $type,
        ];
    }
   //записываем в наш калькулятор игрового времени информацию по игрокам домашней и гостевой команды, а затем возвращаем итоговые значения
    public function getPositionTime()
    {
        $this->positionTimeCalculator->getTimeFromTeam($this->homeTeam);
        $this->positionTimeCalculator->getTimeFromTeam($this->awayTeam);
        return $this->positionTimeCalculator->getTimeByPosition();
    }

    private function assertCorrectType(string $type): void
    {
        if (!in_array($type, self::MESSAGE_TYPES, true)) {
            throw new \Exception(
                sprintf(
                    'Message type "%s" not supported. Available types: "%s".',
                    $type,
                    implode('", "', self::MESSAGE_TYPES)
                )
            );
        }
    }
}