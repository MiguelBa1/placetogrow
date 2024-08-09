import type { PaginationLink } from "@/Components";

export type ApiResourceLink = {
    first: string;
    last: string;
    prev: string | null;
    next: string | null;
};

export type ApiResourceMeta = {
    current_page: number;
    from: number;
    last_page: number;
    path: string;
    per_page: number;
    to: number;
    total: number;
    links: PaginationLink[];
};
