import axios from 'axios';
import { useQuery } from '@tanstack/vue-query';

type FieldType = {
    'label': string;
    'value': string;
}

type UseFieldTypesQueryProps = {
    enabled?: boolean;
}

export function useFieldTypesQuery({ enabled = true }: UseFieldTypesQueryProps = {}) {
    return useQuery<FieldType[]>({
        queryKey: ['fieldTypes'],
        queryFn: async () => {
            const { data } = await axios.get<FieldType[]>(route('microsites.fields.types'));

            return data;
        },
        enabled,
    });
}
