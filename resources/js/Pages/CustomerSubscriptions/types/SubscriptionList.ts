export type SubscriptionListItem = {
    id: number;
    subscription_name: string;
    price: number;
    start_date: string;
    end_date: string;
    status: string;
    actions: string;
}

export type SubscriptionsList = {
    data: SubscriptionListItem[];
}
