export type SubscriptionItem = {
    id: number;
    name: string;
    description: string;
    price: number;
    total_duration: string;
    billing_frequency: string;
    created_at: string;
}

export type SubscriptionList = {
    data: SubscriptionItem[];
};
