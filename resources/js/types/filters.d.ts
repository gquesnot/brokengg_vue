export interface FiltersInterface {
    champion_id?: number;
    queue_id?: number;
    start_date?: string;
    end_date?: string;
    should_filter_encounters: boolean;
}

export interface BeforeFiltersInterface {
    champion_id?: string;
    queue_id?: string;
    start_date?: string;
    end_date?: string;
    should_filter_encounters?: string;
}
