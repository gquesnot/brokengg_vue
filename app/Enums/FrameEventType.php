<?php

namespace App\Enums;

enum FrameEventType: string
{
    case PAUSE_END = 'PAUSE_END';
    case OTHER = 'OTHER';
    case TOWER = 'TOWER';
    case ITEM_UNDO = 'ITEM_UNDO';
    case MINION = 'MINION';
    case GAME_END = 'GAME_END';
    case CHAMPION_KILL = 'CHAMPION_KILL';
    case CHAMPION_SPECIAL_KILL = 'CHAMPION_SPECIAL_KILL';
    case LEVEL_UP = 'LEVEL_UP';
    case ITEM_DESTROYED = 'ITEM_DESTROYED';
    case WARD_PLACED = 'WARD_PLACED';
    case BUILDING_KILL = 'BUILDING_KILL';
    case PORO_KING_SUMMON = 'PORO_KING_SUMMON';
    case ITEM_PURCHASED = 'ITEM_PURCHASED';
    case SKILL_LEVEL_UP = 'SKILL_LEVEL_UP';
    case ITEM_SOLD = 'ITEM_SOLD';
    case CHAMPION_TRANSFORM = 'CHAMPION_TRANSFORM';

    public function isRelatedToItems(): bool
    {
        return in_array($this, [
            self::ITEM_UNDO,
            self::ITEM_DESTROYED,
            self::ITEM_PURCHASED,
            self::ITEM_SOLD,
        ]);
    }

    public static function itemTypes(): array
    {
        return [
            self::ITEM_UNDO,
            self::ITEM_DESTROYED,
            self::ITEM_PURCHASED,
            self::ITEM_SOLD,
        ];
    }
}
