import {EventType} from "@/enums/event_type";


export interface ItemEventInterface  {
    type:EventType;
    item_id:number;
    summoner_match_frame_id: number;
    item_count: number;
    item:ItemInterface;
}
