export type SubscriptionListItem = {
    id: number;
    name: string;
    price: string;
    total_duration: number;
    billing_frequency: number;
    time_unit: string;
    created_at: string;
    deleted_at: string | null;
}

export type SubscriptionsList = {
    data: SubscriptionListItem[];
}
