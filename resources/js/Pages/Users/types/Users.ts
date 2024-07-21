import { type PaginatedResponse } from "@/Components";

export type User = {
    id: number;
    name: string;
    email: string;
    roles: {
        name: string;
    }[];
    created_at: string;
};

export type UsersPaginatedResponse = PaginatedResponse<User>;
