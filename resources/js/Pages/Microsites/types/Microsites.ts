export type Microsites = {
    id: number;
    name: string;
    responsible_name: string;
    category: {
        id: number;
        name: string;
    };
    type: string;
    payment_currency: string;
    payment_expiration: string;
};

export type PaginatedMicrosites = {
    data: Microsites[];
    current_page: number;
    first_page_url: string;
    from: number;
    last_page: number;
    last_page_url: string;
    links: {
        url: string | null;
        label: string;
        active: boolean;
    }[];
    next_page_url: string | null;
    path: string;
    per_page: number;
    prev_page_url: string | null;
    to: number;
    total: number;
};
