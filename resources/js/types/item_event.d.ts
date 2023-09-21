import {EventType} from "@/enums/event_type";


export interface ItemEventInterface  {
    id: number;
    type:EventType;
    item_id:number;
    item:ItemInterface;
}
