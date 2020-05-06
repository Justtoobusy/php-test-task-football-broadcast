<?php


namespace App\Service;


use App\Entity\Player;
use App\Entity\Team;

//создаём класс-сервис, для подсчёта игрового времени по каждой из позиций вратари/защитники/полузащитники/нападающие
class PositionTimeCalculator
{
    //константы для сравнения со значениями из лога матча
    const GOALKEEPER = 'В';
    const DEFENDER = 'З';
    const MIDFIELDER = 'П';
    const ATTACKER = 'Н';
    //константы для отображения в шаблоне
    const FULL_GOALKEEPER = 'Вратари';
    const FULL_DEFENDER = 'Защитники';
    const FULL_MIDFIELDER = 'Полузащитники';
    const FULL_ATTACKER = 'Нападающие';
    //асс.массив для вывода в шаблон, позиция => время
    private array $timeByPosition;
    //асс. массив связывающий shortname с fullname
    private array $fullToShort = [
        self::GOALKEEPER => self::FULL_GOALKEEPER,
        self::DEFENDER => self::FULL_DEFENDER,
        self::MIDFIELDER => self::FULL_MIDFIELDER,
        self::ATTACKER => self::FULL_ATTACKER,
    ];

    public function __construct()
    {
        $this->timeByPosition = [
            self::FULL_GOALKEEPER => 0,
            self::FULL_DEFENDER => 0,
            self::FULL_MIDFIELDER => 0,
            self::FULL_ATTACKER => 0,
        ];
    }
    //записываем данные по игровому времени игроков конкретных позиций конкретно команды
    public function getTimeFromTeam(Team $team):void
    {
        /* @var $player Player */
        foreach ($team->getPlayers() as $player) {
            $position = $this->fullToShort[$player->getPosition()];
            $this->timeByPosition[$position] = $this->timeByPosition[$position] + $player->getPlayTime();
        }
    }
    //возвращаем массив позиция => время
    public function getTimeByPosition():array
    {
        return $this->timeByPosition;
    }

}