export interface FiltersInterface {
    champion_id: number | undefined;
    queue_id: number | undefined;
    start_date: string | undefined;
    end_date: string | undefined;
    should_filter_encounters: boolean;
}
