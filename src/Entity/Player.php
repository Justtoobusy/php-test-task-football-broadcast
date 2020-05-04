<?php

namespace App\Entity;

class Player
{
    private const PLAY_PLAY_STATUS = 'play';
    private const BENCH_PLAY_STATUS = 'bench';

    private int $number;
    private string $name;
    private string $playStatus;
    private int $inMinute;
    private int $outMinute;
    private int $goalsScored;
    private int $redCard;
    private int $yellowCard;
    private string $position;

    public function __construct(int $number, string $name, string $position)
    {
        $this->number = $number;
        $this->name = $name;
        $this->playStatus = self::BENCH_PLAY_STATUS;
        $this->inMinute = 0;
        $this->outMinute = 0;
        $this->goalsScored = 0;
        $this->redCard = 0;
        $this->yellowCard = 0;
        $this->position = $position;
    }

    public function getNumber(): int
    {
        return $this->number;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getInMinute(): int
    {
        return $this->inMinute;
    }

    public function getOutMinute(): int
    {
        return $this->outMinute;
    }

    public function isPlay(): bool
    {
        return $this->playStatus === self::PLAY_PLAY_STATUS;
    }

    public function getPlayTime(): int
    {
        if (!$this->outMinute) {
            return 0;
        }
        //Bug: Исправить ошибку с подсчетом времени, проведенном на поле"
        //нас интересует не математическая разница между минутами, а реально кол-во этих минут
        // к примеру, если наш игрок вышел на поле на 1 мтинуте, а заменен был на 2 - математически он провел на поле 2-1 = 1 целую минуту, когда по факту был на поле 2 минуты.
        return $this->outMinute - $this->inMinute + 1;
    }

    public function goToPlay(int $minute): void
    {
        $this->inMinute = $minute;
        $this->playStatus = self::PLAY_PLAY_STATUS;
    }

    public function goToBench(int $minute): void
    {
        $this->outMinute = $minute;
        $this->playStatus = self::BENCH_PLAY_STATUS;
    }
    //Improvement: Добавить иконку мячика, если футболист забил гол
    //добавляем игроку забитый мяч
    public function goalScored(): void
    {
        $this->goalsScored += 1;
    }

    //возвращаем колчиество мячей забитых игроком
    public function getGoalsScored(): int
    {
        return $this->goalsScored;
    }
    //Improvement: Добавить отображение желтых и красных карточек напротив футболистов, которые получили карточки
    //добавляем игроку жёлтую
    public function addYellowCard(): void
    {
        $this->yellowCard += 1;
        if ($this->yellowCard == 2) {
            $this->addRedCard();
        }
    }
    //возвращаем жёлтую
    public function getYellowCard(): int
    {
        return $this->yellowCard;
    }
    //добавляем игроку красную
    public function addRedCard(): void
    {
        $this->redCard += 1;
    }
    //возвращаем красную
    public function getRedCard(): int
    {
        return $this->redCard;
    }
    //возвращаем позицию игрока
    public function getPosition(): string
    {
        return $this->position;
    }

}